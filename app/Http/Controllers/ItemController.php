<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsResource;
use App\Models\BranchSub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    private function paginate($data, $page = 1, $pageSize = 15)
    {
        $collect = collect($data);
        return new LengthAwarePaginator(
            $collect->forPage($page, $pageSize),
            $collect->count(),
            $pageSize,
            $page
        );
    }

    public function index(Request $request)
    {

        $page = $request->input("page");
        $Whno = $request->input("storeNo");
        $searchValue = $request->input("query") ?? "";
        $branchSubNo = $request->input("branchSubNo");

        $data =  DB::select(
            "SET NOCOUNT ON;
            EXEC sp_QtyByWH_Mobile
                @Whno = ?,
                @searchValue = ?,
                @branchSubNo = ?",
            [$Whno, $searchValue, $branchSubNo]
        );

        $user = User::where("BranchSubno", $branchSubNo)
            ->select(["SaleRetail", "SaleWhole", "SaleVIP", "SalePromotion"])->first();

        $paginationData = $this->paginate($data, $page);

        $request->merge([
            "AllowedSaleForUser" => $user->toArray()
        ]);
        return ItemsResource::collection($paginationData->getCollection())
            ->additional([
                "handshakeCode" => $request->input("handshakeCode"),
                "hasMore" => $paginationData->hasMorePages(),
            ]);
    }
}
