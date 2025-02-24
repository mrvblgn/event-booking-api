<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'venue_id' => 'sometimes|integer|exists:venues,id',
            'start_date' => 'sometimes|date|after:now',
            'end_date' => 'sometimes|date|after:start_date'
        ];
    }
} 