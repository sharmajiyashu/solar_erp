<?php

namespace App\Http\Requests\Solar;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_slot_id' => 'required|exists:service_slots,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ];
    }
}
