<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRequest;
use App\Http\Resources\RecDetailResource;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Http\Resources\ReceiptResource;
use DateTime;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {

        $receipt = Receipt::join("RecDetail", "RecDetail.RecNo", "Receipt.Recno")->where(
            "Createduserno",
            "=",
            $request->input("Createduserno")
        )->orderBy('Receipt.Recno', 'DESC');

        if ($request->input("AccNo"))
            $receipt->where(
                "RecDetail.Accno",
                "=",
                $request->input("AccNo")
            );
        if ($request->input("from"))
            $receipt->whereDate(
                "Receipt.CreatedDate",
                ">=",
                $request->input("from"),
            );
        if ($request->input("to"))
            $receipt->whereDate(
                "Receipt.CreatedDate",
                "<=",
                $request->input("to"),
            );
        $receipt = $receipt->simplePaginate();

        return ReceiptResource::collection($receipt->getCollection())->additional([
            "hasMore" => $receipt->hasMorePages(),
        ]);
    }

    public function show($RecNo)
    {
        $receipt = Receipt::find($RecNo);
        return new RecDetailResource($receipt);
    }

    public function store(ReceiptRequest $request)
    {
        $newRecNo = strval($this->getLastRecNo() + 1);

        $receipt = Receipt::create(
            [
                "Recno" => $newRecNo,
                "RecYear" => 2017,
                "Branchno" => 1,
                "RecDate" => date('Y-m-d'),
                "Amount" => $request->input("amount"),
                "Description" => $request->input("description"),
                "RecAccno" => $request->input("cashAccNo"),
                "RecType" => 1,
                "Posted" => 1,
                "SaleInvNo" => 0,
                "Createduserno" => $request->input("userNo"),
                "CreatedDate" => date('Y-m-d H:i:s'),
            ]
        );

        $receipt->Recno = $newRecNo;

        RecDetailController::store($request, $newRecNo);

        return new ReceiptResource($receipt);
    }

    private function getLastRecNo()
    {
        return Receipt::latest("Recno")->first("Recno")->Recno;
    }
}
