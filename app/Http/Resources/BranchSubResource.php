<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchSubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //TODO: select essential rows
        return [
            "Num" => $this->Num,
            "BranchName" => $this->BranchName,
            "StoreNo" => $this->StoreNo,
            "StoreAccNo" => $this->StoreAccNo,
            "CashAccno" => $this->CashAccno,
            "ParentCustAccno" => $this->ParentCustAccno,
            "SellingPriceCat" => $this->SellingPriceCat,
            "WhTransferType" => $this->WhTransferType,
            "CreditLimit" => $this->CreditLimit,
            "CreditPeriod" => $this->CreditPeriod,
            "OpenBalance" => $this->OpenBalance,
        ];
    }
}
