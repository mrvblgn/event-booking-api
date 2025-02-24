<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'venue_id' => 'required|integer|exists:venues,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date'
        ];
    }
} 