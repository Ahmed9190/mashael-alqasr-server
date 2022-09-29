<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\RecDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ReceiptResource extends JsonResource
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
            "number" => $this->Recno,
            "total" => $this->Amount,
            "description" => $this->Description,
            "customerName" => Account::getName(RecDetail::where("RecNo", $this->Recno)->first("AccNo")->AccNo),
            "createdDate" => date($this->CreatedDate),
        ];
    }
}
