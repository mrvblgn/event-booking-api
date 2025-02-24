<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|integer|exists:events,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id'
        ];
    }
} 