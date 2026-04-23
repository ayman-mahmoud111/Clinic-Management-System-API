<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
        'name'=>$this->name,
        'phone'=>$this->phone,
        'age'=>$this->age,
        'address'=>$this->address,
        'created_at'=>$this->created_at->format('y-m-d'),

       ];
    }
}
