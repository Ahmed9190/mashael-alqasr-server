<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\SaleHeaderResource;
use App\Models\Invdetail;
use App\Models\Item;
use App\Models\SaleHeader;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {

        $invoices = SaleHeader::where(
            "Createduserno",
            "=",
            $request->input("Createduserno"),
        )->orderBy('invno', 'DESC');

        if ($request->input("Custno"))
            $invoices->where(
                "Custno",
                "=",
                $request->input("Custno")
            );
        if ($request->input("from"))
            $invoices->whereDate(
                "invdate",
                ">=",
                $request->input("from")
            );

        if ($request->input("to"))
            $invoices->whereDate(
                "invdate",
                "<=",
                $request->input("to")
            );

        $invoices = $invoices->simplePaginate();

        return SaleHeaderResource::collection($invoices->getCollection())->additional([
            "hasMore" => $invoices->hasMorePages(),
        ]);
    }

    public function store(InvoiceRequest $request)
    {

        $invno = SaleHeader::getNextInvNo();

        $this->createSaleheader($request, $invno);
        $this->createInvDetails($request, $invno);

        // return $invoice;
        return new InvoiceResource(SaleHeader::find($invno));
    }

    private function createSaleheader(Request $request, $invno)
    {
        $netTotal = $request->input("total") - ($request->input("DiscountTotal") ?? 0); // (?? 0) will be removed when adding discount feature in the mobile app
        $VATamount = $this->getVATamount($request->input("total"));

        $invoice = [
            "invno" => $invno,
            "Branchno" => 1,
            "Year" => 2018,
            "BranchSubno" => $request->input("BranchSubno"),
            "whno" => $request->input("whno"),
            "invdate" => new DateTime('today'),
            "Accno" => $request->input('Accno'),
            "SaleAccno" => $request->input('SaleAccno'),
            "CustName" => $request->input('AccName'),
            "total" => $request->input("total"),
            "DiscountAmount" => 0,
            "DiscountPercent" => 0,
            "DiscountTotal" => 0,
            "netTotal" => $netTotal,
            "CostTotal" => $this->getCostTotal($request->input("items")),
            "notes" => $request->input("notes"),
            "PayType" => $request->input("PayType"),
            "invKind" => 1,
            "Sellerno" => 0,
            "QuotNo" => 0,
            "PrepareNo" => 0,
            "Createduserno" => $request->input('Createduserno'),
            "CreatedDate" => new DateTime('today'),
            "TotQty" => $this->getTotalQuantity($request->input("items")),
            // "NetTotChar" => NULL,
            "Custno" => $request->input('Custno'),
            "Pricing" => 1,
            // "Refno" => 1701,
            // "GroupTotQty" => 3,
            "Posted" => 1,
            "Transno" => 0,
            "VATaccno" => DB::table("Config")->first('VATaccnoOut')->VATaccnoOut,
            "TotAfterVAT" => $netTotal + $VATamount,
            "VATamount" => $VATamount,
        ];

        SaleHeader::insert($invoice);
    }

    private function getCostTotal($items)
    {
        $CostTotal = 0;
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $CostTotal += $item["unitPrice"] * $item["QTY"];
        }
        return $CostTotal;
    }

    private function getTotalQuantity($items)
    {
        return array_reduce($items, function ($accumulator, $currentValue) {
            return $accumulator + $currentValue['QTY'];
        }, 0);
    }
    private function getVATamount($total)
    {
        $vat = VATpercentDateController::show() / 100.0;
        $VATamount = $vat * $total;
        return $VATamount;
    }

    private function createInvDetails(InvoiceRequest $request, $invno)
    {
        Log::info($request);
        $items = $request->input("items");

        $invdetailRows = [];


        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            array_push($invdetailRows, [
                "Invno" => $invno,
                "Branchno" => 1,
                "year" => 2018,
                "Itemno" => $item["Itemno"],
                "ItemDesc" => $item["ItemDesc"],
                "invKind" => 1,
                "QTY" => $item["QTY"],
                "unitPrice" => $item["unitPrice"],
                "Discount" => 0,
                "DiscountAmount" => 0,
                "retQTY" => 0,
                "retGroupQTY" => 0,
                "DeliveryQTY" => 0,
                "DeliveryGroupQTY" => 0,
                "PrevQTY" => 0,
                "PrevGroupQTY" => 0,
                "OrigQTY" => 0,
                "OrigGroupQTY" => 0,
                "Cost" => Item::where("itemno", $item["Itemno"])->first('AvgCost')->AvgCost,
                "Selected" => '0',
                "Unitno" => 1,
                "UnitQty" => 1,
                "Groupno" => 0,
                "GroupQty" => 1,
                "ExchangeRate" => 1,
                "ExpenseRate" => 0,
                // "Partno" => NULL,
                // "GroupUnitPrice" => 0,
                "SaleType" => $item["SaleType"],
                "FreeQty" => $item["freeQty"],
                // "oldIMEI" => NULL,
                // "newIMEI" => NULL,
                "RecycleQty" => 0,
            ]);
        }

        Invdetail::insert($invdetailRows);
    }


    public function show($id)
    {
        $invoice = SaleHeader::find($id);

        return new InvoiceResource($invoice);
    }
}
