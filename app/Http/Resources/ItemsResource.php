<?php

namespace App\Http\Resources;

use App\Models\BranchSub;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    private $additionalColumns = [];
    private $availableQty = 0.0;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $branchSub = BranchSub::find($request->input("branchSubNo"));
        $this->availableQty = floatval($this["QTY" . $branchSub->StoreNo]);

        $this->addWholeSaleIfActive();
        $this->addVIPSaleIfActive();
        $this->addPromotionSaleIfActive();


        return [
            "itemno" => $this->itemno,
            "itemDesc" => $this->itemDesc,
            "availableQty" => $this->availableQty,
            "SellPrice" => $this["SellPrice" . $branchSub->SellingPriceCat],
        ] + $this->additionalColumns;
    }

    private function addWholeSaleIfActive()
    {
        if (
            $this->WholeSaleActive &&
            $this->WholeSaleQty > 0 &&
            $this->availableQty > $this->WholeSaleQty &&
            $this->WholeSalePrice > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "wholeSale" => [
                        "qty" => $this->WholeSaleQty,
                        "price" => $this->WholeSalePrice
                    ]
                ]
            );
    }

    private function addVIPSaleIfActive()
    {
        if (
            $this->VIPSaleActive &&
            $this->VIPSaleQty > 0 &&
            $this->availableQty > $this->VIPSaleQty &&
            $this->VIPSalePrice > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "vipSale" => [
                        "qty" => $this->VIPSaleQty,
                        "price" => $this->VIPSalePrice
                    ]
                ]
            );
    }
    private function addPromotionSaleIfActive()
    {
        if (
            $this->availableQty >= $this->PromotionQtyReq + $this->PromotionQtyFree &&
            $this->PromotionQtyReq > 0 &&
            $this->PromotionQtyFree > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "promotionSale" => [
                        "qtyReq" => $this->PromotionQtyReq,
                        "qtyFree" => $this->PromotionQtyFree,
                    ]
                ]
            );
    }
}
