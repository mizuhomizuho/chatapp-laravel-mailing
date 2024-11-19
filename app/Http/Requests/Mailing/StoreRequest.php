<?php

namespace App\Http\Requests\Mailing;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|array',
            'phone.*' => ['required', 'string', 'max:32'],
            'message' => ['required', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'phone.*' => 'Phone',
            'message' => 'Message',
        ];
    }
}
