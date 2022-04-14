<?php

namespace App\Http\Resources;

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


        return [
            "number" => $this->item_no,
            "name" => $this->itemDesc,
            "availableQty" => intval($this->available_qty),
            "sellPrice" => floatval($this->sell_price),
        ] + $this->additionalColumns;
    }

    private function addWholeSaleIfActive()
    {
        if (
            $this->WholeSaleActive &&
            $this->WholeSaleQty > 0 &&
            $this->available_qty > $this->WholeSaleQty &&
            $this->WholeSalePrice > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "wholeSale" => [
                        "qty" => intval($this->WholeSaleQty),
                        "price" => floatval($this->WholeSalePrice),
                    ]
                ]
            );
    }

    private function addVIPSaleIfActive()
    {
        if (
            $this->VIPSaleActive &&
            $this->VIPSaleQty > 0 &&
            $this->available_qty > $this->VIPSaleQty &&
            $this->VIPSalePrice > 0
        )
            $this->additionalColumns = array_merge(
                $this->additionalColumns,
                [
                    "vipSale" => [
                        "qty" => intval($this->VIPSaleQty),
                        "price" => floatval($this->VIPSalePrice),
                    ]
                ]
            );
    }
    private function addPromotionSaleIfActive()
    {
        if (
            $this->available_qty >= $this->PromotionQtyReq + $this->PromotionQtyFree &&
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
}
