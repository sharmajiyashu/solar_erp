<?php

namespace App\Http\Requests\Solar;

use Illuminate\Foundation\Http\FormRequest;

class AssignSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminUser() && $this->user()->can('service_assign');
    }

    public function rules(): array
    {
        return [
            'slot_id' => 'required|exists:service_slots,id',
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}
