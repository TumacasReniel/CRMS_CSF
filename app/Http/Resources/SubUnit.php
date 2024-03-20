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
            'sub_unit_name' => $this->title,    
            // 'pstos'=> $this->pstos ? PSTO::collection($this->pstos) : [],
        ];
    }
}
