<?php

namespace App\Services\Mailing;

use App\Jobs\Mailing\SendJob;
use App\Models\Mailing;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store(array $data): void
    {
        $dataForInsert = [];
        foreach ($data['phone'] as $phoneV) {
            $dataForInsert[] = [
                'phone' => $phoneV,
                'message' => $data['message'],
                'status' => Mailing::STATUS_NEW,
            ];
        }

        $insertIds = [];
        if (isset($dataForInsert[1])) {
            DB::transaction(function() use ($dataForInsert, &$insertIds) {
                Mailing::insert($dataForInsert);
                $lastId = Mailing::orderByDesc('id')->first()->id;
                $insertIds = range($lastId - count($dataForInsert) + 1, $lastId);
            });
        }
        else {
            $insertIds[] = Mailing::create($dataForInsert[0])->id;
        }

        $delaySum = 0;
        foreach ($insertIds as $insertNum => $insertId) {
            if ($insertNum) {
                $randDelayStep = $this->getRandDelay();
            }
            else {
                $randDelayStep = 0;
            }
            SendJob::dispatch(
                $insertId,
                $dataForInsert[$insertNum]['phone'],
                $data['message'],
            )->delay(now('UTC')->addSeconds($delaySum + $randDelayStep));
            $delaySum += $randDelayStep;
        }
    }

    public function getRandDelay(): int
    {
        return rand(5, 50);
    }

    public function setStatus(int $id, string $status): void
    {
        Mailing::where('id', $id)->update(['status' => $status]);
    }
}
