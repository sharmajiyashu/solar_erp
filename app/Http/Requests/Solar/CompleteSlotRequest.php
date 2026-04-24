<?php

namespace App\Http\Requests\Solar;

use Illuminate\Foundation\Http\FormRequest;

class CompleteSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminUser() ?? false;
    }

    public function rules(): array
    {
        return [
            'slot_id' => 'required|exists:service_slots,id',
            'verification_code' => 'required|string|size:6',
        ];
    }
}
