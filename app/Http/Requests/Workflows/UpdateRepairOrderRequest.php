<?php

namespace App\Http\Requests\Workflows;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:REQUESTED,IN_PROGRESS,DIAGNOSIS,WAITING_PARTS,ESTIMATE_PENDING,ESTIMATE_APPROVED,COMPLETED,DELIVERED,READY,CLOSED,CANCELLED',
            'internal_notes' => 'nullable|string',
            'problem_description' => 'nullable|string',
            'priority' => 'nullable|string|in:LOW,MEDIUM,HIGH,URGENT',
            'assigned_mechanic_id' => 'nullable|exists:users,id',
            'workshop_bay_id' => 'nullable|exists:workshop_bays,id',
        ];
    }
}
