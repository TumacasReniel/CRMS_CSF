<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubUnit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'sub_unit_name' => $this->sub_unit_name, 
            'sub_unit_types'=> $this->sub_unit_types ? SubUnitType::collection($this->sub_unit_types) : [],
            'sub_unit_pstos'=> $this->pstos ? PSTO::collection($this->pstos) : [],
        ];
    }
}
