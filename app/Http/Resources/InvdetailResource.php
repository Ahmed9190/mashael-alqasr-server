<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvdetailResource extends JsonResource
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
            "Itemno" => $this->Itemno,
            "QTY" => intval($this->QTY),
            "unitPrice" => floatval($this->unitPrice),
            "ItemDesc" => $this->ItemDesc,
            "freeQty" => intval($this->FreeQty),
            "SaleType" => intval($this->SaleType),
        ];
        // return [
        //     "invno" => $this->invno,
        //     "invdate" => $this->invdate,
        //     "CustName" => $this->CustName,
        //     "total" => $this->total,
        //     "DiscountTotal" => $this->DiscountTotal,
        //     "netTotal" => $this->netTotal,
        //     "VATamount" => $this->VATamount,
        //     "TotAfterVAT" => $this->TotAfterVAT,
        //     "User_Name" => $this->User_Name,
        //     "PayType" => $this->PayType,
        //     "VATnum" => $this->VATnum,
        //     "invoiceItems" => InvdetailResource::collection($this->invoiceItems),
        // ];
        // return [
        //     "itemno" => $this->Itemno,
        //     "QTY" => $this->QTY,
        //     "unitPrice" => $this->unitPrice,
        //     "itemDesc" => $this->itemDesc,
        //     "PromotionQtyReq" => $this->PromotionQtyReq,
        //     "PromotionQtyFree" => $this->PromotionQtyFree,
        //     "totalPrice" => $this->totalPrice,
        //     "freeQty" => $this->freeQty1,
        // ];
    }
}
