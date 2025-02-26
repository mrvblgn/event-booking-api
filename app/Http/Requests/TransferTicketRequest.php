<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_code' => 'required|string|exists:tickets,ticket_code',
            'email' => 'required|email|exists:users,email'
        ];
    }
} 