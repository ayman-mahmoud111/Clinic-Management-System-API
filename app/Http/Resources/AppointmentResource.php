<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,

            'patient'=>[
                'id'=>$this->patient->id,
                'name'=>$this->patient->name,
            ],
            
            'doctor'=>[
                'id'=>$this->doctor->id,
                'name'=>$this->doctor->name,
            ]

            

        ];

        
    }
}
