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
            'email' => $this->email,
            'region'=> $this->region ? new Region( $this->region) : null,
            'is_active' => $this->is_active,
            'account_type' => $this->account_type,
            // 'created_at' => Carbon::parse($this->created_at)->format("mm/dd/YY"), 
            // 'updated_at' => Carbon::parse($this->updated_at)->format("mm/dd/YY"),
        ];
    }
}
