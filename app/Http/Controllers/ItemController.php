<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $page = $request->input('page');
        $Whno = $request->input('storeNo');
        $searchValue = $request->input('query') ?? "";
        $data =  DB::select(
            "SET NOCOUNT ON;
            EXEC sp_QtyByWH_Mobile
                @FromDate = N'2000/01/01',
                @ToDate = N'2100/01/01',
                @Whno = ?,
                @searchValue = ?",
            [$Whno, $searchValue]
        );
        $paginationData = $this->paginate($data, $page);
        // return [
        //     "data" => $paginationData->getCollection()
        // ];
        return ItemsResource::collection($paginationData->getCollection())
            ->additional([
                "hasMore" => $paginationData->hasMorePages(),
            ]);
    }
}
