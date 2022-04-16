<?php

namespace App\Http\Resources;

use App\Models\BranchSub;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    private $additionalColumns = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->addWholeSaleIfActive();
        $this->addVIPSaleIfActive();
        $this->addPromotionSaleIfActive();

        $branchSub = BranchSub::find($request->input("branchSubNo"));
        return [
            "number" => $this->itemno,
            "name" => $this->itemDesc,
            "availableQty" => intval($this->CurrQty),
            "sellPrice" => $this->getSalePrice($branchSub->SellingPriceCat),
        ] + $this->additionalColumns;
    }

    private function addWholeSaleIfActive()
    {
        if (
            // $this->WholeSaleActive &&
            $this->WholeSaleQty > 0 &&
            $this->CurrQty > $this->WholeSaleQty &&
            $this->WholeSale > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "wholeSale" => [
                        "qty" => intval($this->WholeSaleQty),
                        "price" => floatval($this->WholeSale),
                    ]
                ]
            );
    }

    private function addVIPSaleIfActive()
    {
        if (
            // $this->VIPSaleActive &&
            $this->VIPsaleQty > 0 &&
            $this->CurrQty > $this->VIPsaleQty &&
            $this->VIPsale > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "vipSale" => [
                        "qty" => intval($this->VIPsaleQty),
                        "price" => floatval($this->VIPsale),
                    ]
                ]
            );
    }
    private function addPromotionSaleIfActive()
    {
        if (
            $this->CurrQty >= $this->PromotionQtyReq + $this->PromotionQtyFree &&
            $this->PromotionQtyReq > 0 &&
            $this->PromotionQtyFree > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "promotionSale" => [
                        "qtyReq" => intval($this->PromotionQtyReq),
                        "qtyFree" => intval($this->PromotionQtyFree),
                    ]
                ]
            );
    }

    private function getSalePrice($salePriceCat)
    {
        $qty = NULL;
        switch ($salePriceCat) {
            case 1:
                $qty = $this->SellPrice1;
                break;
            case 2:
                $qty = $this->SellPrice2;
                break;
            case 3:
                $qty = $this->SellPrice3;
                break;
        }

        return floatval($qty);
    }
}
