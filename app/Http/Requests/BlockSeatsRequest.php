<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockSeatsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id'
        ];
    }
} 