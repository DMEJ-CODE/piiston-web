<?php

namespace App\Http\Resources\Workflows;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'symptoms' => $this->symptoms,
            'detected_problem' => $this->detected_problem,
            'root_cause' => $this->root_cause,
            'solution' => $this->solution,
            'recommendation' => $this->recommendation,
            'severity' => $this->severity,
            'created_at' => $this->created_at,
        ];
    }
}
