<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        Log::info("QTY" . $request->input("storeNo"));
        $items = Item::where("QTY" . $request->input("storeNo"), ">", 0);
        if ($request->input("query"))
            $items->where(
                function ($items) use ($request) {
                    $items->where("itemDesc", "LIKE", "%" . $request->input("query") . "%")
                        ->orWhere("itemno", "LIKE", "%" . $request->input("query") . "%");
                },
            );

        $items = $items->simplePaginate();

        return ItemsResource::collection($items->getCollection())->additional([
            "hasMore" => $items->hasMorePages(),
        ]);
    }
}
