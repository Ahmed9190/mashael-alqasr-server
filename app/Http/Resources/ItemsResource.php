<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $AllowedSaleForUser = $request->input("AllowedSaleForUser");

        return [
            "number" => $this->itemno,
            "name" => $this->itemDesc,
            "availableQty" => intval($this->CurrQty),
            "sellPrice" => floatval($this->SellPrice),
            "retailSale" => $this->addRetailSaleIfActive($AllowedSaleForUser["SaleRetail"]),
            "wholeSale" => $this->addWholeSaleIfActive($AllowedSaleForUser["SaleWhole"]),
            "vipSale" => $this->addVipSaleIfActive($AllowedSaleForUser["SaleVIP"]),
            "promotionSale" => $this->addPromotionSaleIfActive($AllowedSaleForUser["SalePromotion"]),
        ];
    }
    private function addRetailSaleIfActive($isAllowedForUser)
    {

        return $this->when(
            $isAllowedForUser,
            [
                "qty" => intval($this->CurrQty),
                "price" => floatval($this->SellPrice),
            ]
        );
    }


    private function addWholeSaleIfActive($isAllowedForUser)
    {
        return $this->when(
            $isAllowedForUser &&
                $this->WholeSaleQty > 0 &&
                $this->CurrQty >= $this->WholeSaleQty &&
                $this->WholeSale > 0,
            [
                "qty" => intval($this->WholeSaleQty),
                "price" => floatval($this->WholeSale),
            ]
        );
    }

    private function addVIPSaleIfActive($isAllowedForUser)
    {
        return $this->when(
            $isAllowedForUser &&
                $this->VIPsaleQty > 0 &&
                $this->CurrQty >= $this->VIPsaleQty &&
                $this->VIPsale > 0,
            [
                "qty" => intval($this->VIPsaleQty),
                "price" => floatval($this->VIPsale),
            ]
        );
    }

    private function addPromotionSaleIfActive($isAllowedForUser)
    {
        return $this->when(
            $isAllowedForUser &&
                $this->CurrQty >= $this->PromotionQtyReq + $this->PromotionQtyFree &&
                $this->PromotionQtyReq > 0 &&
                $this->PromotionQtyFree > 0,
            [
                "qtyReq" => intval($this->PromotionQtyReq),
                "qtyFree" => intval($this->PromotionQtyFree),
            ]
        );
    }
}
