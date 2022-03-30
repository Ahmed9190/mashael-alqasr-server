<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $createdUser = $this->getCreatedUser;
        $invoiceItems = $this->invoiceItems;

        return [
            "invno" => $this->invno,
            "invdate" => $this->invdate,
            "CustName" => $this->CustName,
            //TODO:remove all comments
            "total" => floatval($this->total),
            "DiscountTotal" => floatval($this->DiscountTotal),
            // "netTotal" => floatval($this->netTotal),
            "VATamount" => floatval($this->VATamount),
            "TotAfterVAT" => floatval($this->TotAfterVAT),
            "PayType" => $this->PayType == "3" ? "آجل" : "نقدي",
            "customerVATno" => intval(Account::getVATnum($this->Custno)),
            "invoiceItems" => InvdetailResource::collection($invoiceItems),
        ];
    }
}
