<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = "Items";
    protected $primaryKey = "itemno";
    protected $casts = [
        "itemno" => "string",
        "PromotionQtyReq" => "float",
        "PromotionQtyFree" => "float",
        "QTY1" => "integer",
        "QTY2" => "integer",
        "QTY3" => "integer",
        "SellPrice1" => "float",
        "SellPrice2" => "float",
        "SellPrice3" => "float",

        "WholeSaleQty" => "integer",
        "WholeSalePrice" => "float",

        "VIPSaleQty" => "integer",
        "VIPSalePrice" => "float",
    ];

    static public function getAvgCost($itemNo)
    {
        return Item::find($itemNo)->AvgCost;
    }

    static public function getWholeSaleQty($itemNo)
    {
        return Item::find($itemNo)->WholeSaleQty;
    }

    static public function getVIPSaleQty($itemNo)
    {
        return Item::find($itemNo)->VIPSaleQty;
    }
}
