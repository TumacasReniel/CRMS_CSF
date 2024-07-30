<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
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
            'name' => $this->name,
            'designation' => $this->designation,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'region'=> $this->region ? new Region( $this->region) : null,
            'service'=> $this->service ? new Services( $this->service) : null,
            'unit'=> $this->unit ? new Unit( $this->unit) : null, 
            'account_type' => $this->account_type,
            // 'created_at' => Carbon::parse($this->created_at)->format("mm/dd/YY"), 
            // 'updated_at' => Carbon::parse($this->updated_at)->format("mm/dd/YY"),
        ];
    }
}
