<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleHeaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "invno" => $this->invno,
            "CustName" => $this->CustName,
            "invdate" => date("Y-m-d", strtotime($this->invdate)),
            "total" => floatval($this->total)
        ];
    }
}
