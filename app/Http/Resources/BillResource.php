<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
        'amount' => $this->amount,
        'status' => $this->status,
        'paid_at' => $this->paid_at,

        'patient' => [
            'id' => $this->patient->id,
            'name' => $this->patient->name,
        ],

        'appointment' => [
            'id' => $this->appointment->id,
            'date' => $this->appointment->start_time,
        ],
    ];
        
    }
}
