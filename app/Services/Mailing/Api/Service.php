<?php

namespace App\Services\Mailing\Api;

use App\Exceptions\Mailing\ApiException;
use App\Models\Options;
use GuzzleHttp\Client;

class Service
{
    private const API_URL = 'https://api.chatapp.online/v1/';
    private const TOKEN_OPTION_CODE = 'CHATAPP_TOKEN';
    private array $queryParams = [];
    private ?array $token = null;

    private function getQueryParamsForRefreshToken(string $token): array
    {
        return [
            'method' => 'tokens/refresh',
            'headers' => [
                'Refresh' => $token,
            ],
            'params' => null,
        ];
    }

    private function getRefreshToken(string $token): array
    {
        $this->queryParams = $this->getQueryParamsForRefreshToken($token);
        return $this->getTokenFromApi();
    }

    private function getToken(): array
    {
        $token = $this->getTokenBase();
        $this->token = $token;
        return $token;
    }

    private function getTokenBase(): array
    {
        $option = Options::where('code', self::TOKEN_OPTION_CODE);

        $token = $option->first();

        if ($token === null) {
            $getNewTokenRes = $this->getNewToken();
            Options::create([
                'code' => self::TOKEN_OPTION_CODE,
                'value' => json_encode($getNewTokenRes),
            ]);
            return $getNewTokenRes;
        }

        $token = json_decode($token->value, true);

        $nowUtc = now('UTC')->timestamp;

        $needSave = false;

        if ($token['refreshTokenEndTime'] < $nowUtc) {
            $token = $this->getNewToken();
            $needSave = true;
        }

        if ($token['accessTokenEndTime'] < $nowUtc) {
            $token = $this->getRefreshToken($token['refreshToken']);
            $needSave = true;
        }

        if ($needSave) {
            $option->update(['value' => $token]);
        }

        return $token;
    }

    private function apiQueryBase(): array
    {
        if (!isset($this->queryParams['headers'])) {
            $this->queryParams['headers'] = [
                'Content-Type' => 'application/json',
            ];
        }

        if (isset($this->queryParams['headersJson'])) {
            $this->queryParams['headers'] = $this->queryParams['headersJson'];
            $this->queryParams['headers']['Content-Type'] = 'application/json';
        }

        $options = [
            'headers' => $this->queryParams['headers'],
        ];

        if (isset($this->queryParams['params'])) {
            $options['json'] = $this->queryParams['params'];
        }

        $body = ((new Client())->post(
            self::API_URL . $this->queryParams['method'],
            $options
        ))->getBody();

        $res = (array) json_decode((string) $body, true);

        return $res;
    }

    private function checkValidTokenResponse(array $res): bool
    {
        return isset($res['success'])
            && $res['success'] === true
            && isset($res['data']['cabinetUserId'])
            && is_numeric($res['data']['cabinetUserId']);
    }

    private function getQueryParamsForNewToken(): array
    {
        return [
            'method' => 'tokens',
            'params' => [
                'email' => env('CHATAPP_EMAIL', ''),
                'password' => env('CHATAPP_PASSWORD', ''),
                'appId' => env('CHATAPP_APPID', ''),
            ],
        ];
    }

    private function apiQuery(): array
    {
        $res = $this->apiQueryBase();

        if (
            isset($res['success'])
            && $res['success'] === false
            && isset($res['error']['code'])
            && $res['error']['code'] === 'ApiInvalidTokenError'
        ) {
            $token = false;
            $queryParamsSave = $this->queryParams;
            if (
                isset($this->token['refreshTokenEndTime'])
                && $this->token['refreshTokenEndTime'] > now('UTC')->timestamp
            ) {
                $this->queryParams = $this->getQueryParamsForRefreshToken($this->token['refreshToken']);
                $res = $this->apiQueryBase();
                if ($this->checkValidTokenResponse($res)) {
                    $token = $res['data'];
                }
            }
            if ($token === false) {
                $this->queryParams = $this->getQueryParamsForNewToken();
                $res = $this->apiQueryBase();
                if ($this->checkValidTokenResponse($res)) {
                    $token = $res['data'];
                }
            }
            if ($token === false) {
                throw new ApiException('API query get new token failed');
            }
            $this->token = $token;
            $this->queryParams = $queryParamsSave;
            $res = $this->apiQueryBase();
        }

        return $res;
    }

    private function checkGoodSendMsgResult(array $res): bool
    {
        return isset($res['success'], $res['data']['id'], $res['data']['chatId'])
            && $res['success'] === true
            && is_string($res['data']['id'])
            && is_string($res['data']['chatId'])
            && $res['data']['id'] !== ''
            && $res['data']['chatId'] !== '';
    }

    public function sendMsg(string $phone, string $msg): array
    {
        $chatId = $phone;
        $token = $this->getToken();
        $licenseId = env('CHATAPP_LICENSE_ID', '');
        $messengerType = 'grWhatsApp';

        $this->queryParams = [
            'method' => "licenses/$licenseId/messengers/$messengerType/chats/$chatId/messages/text",
            'headersJson' => [
                'Authorization' => $token['accessToken'],
            ],
            'params' => [
                'text' => $msg,
            ],
        ];
        $res = $this->apiQuery();

        \Log::info('$res---------: ' . var_export($res, 1));

        if ($this->checkGoodSendMsgResult($res)) {
            return $res['data'];
        }

        throw new ApiException('API send message failed');
    }

    private function getTokenFromApi(): array
    {
        $res = $this->apiQuery();

        if ($this->checkValidTokenResponse($res)) {
            return $res['data'];
        }

        throw new ApiException('Get token from API failed');
    }

    private function getNewToken(): array
    {
        $this->queryParams = $this->getQueryParamsForNewToken();
        return $this->getTokenFromApi();
    }
}
