<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsResource;
use App\Models\BranchSub;
use App\Models\UserItemQuantities;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $branchSub = BranchSub::find($request->input("branchSubNo"));

        $items = UserItemQuantities::where("user_store_no", $request->input("storeNo"))
            ->join("Items", "user_item_quantities.item_no", "Items.itemno")
            ->select([
                "item_no",
                "itemDesc",
                "SellPrice" . $branchSub->SellingPriceCat . " as sell_price",
                "available_qty",
                "WholeSaleActive",
                "WholeSaleQty",
                "WholeSalePrice",
                "VIPSaleActive",
                "VIPSaleQty",
                "VIPSalePrice",
                "PromotionQtyReq",
                "PromotionQtyFree",
            ]);
        if ($request->input("query"))
            $items->where(
                function ($items) use ($request) {
                    $items->where("itemDesc", "LIKE", "%" . $request->input("query") . "%")
                        ->orWhere("itemno", "LIKE", "%" . $request->input("query") . "%");
                },
            );

        $items = $items->simplePaginate();

        return ItemsResource::collection($items->getCollection())
            ->additional([
                "hasMore" => $items->hasMorePages(),
            ]);
    }
}
