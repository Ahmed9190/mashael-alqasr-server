<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\SaleHeaderResource;
use App\Models\BranchSub;
use App\Models\Invdetail;
use App\Models\Item;
use App\Models\SaleHeader;
use App\Models\UserItemQuantities;
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

        DB::transaction(function () use ($request, $invno) {
            $this->createInvDetails($request, $invno);
            $this->createSaleheader($request, $invno);
            $this->subtractQuantities($request->input("items"), $request->input("BranchSubno"));
        });

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
            "CreatedDate" => new DateTime(),
            "TotQty" => $this->getTotalQuantity($request->input("items")),
            // "NetTotChar" => NULL,
            "Custno" => $request->input('Custno'),
            "Pricing" => 1,
            // "Refno" => 1701,
            "GroupTotQty" => Invdetail::getGroupTotQty($invno),
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
        $itemsNumbers = array_map(fn ($item) => $item["Itemno"], $items);
        $itemsAvgCosts = Item::whereIn("Itemno", $itemsNumbers)->get("AvgCost")->toArray();
        $avgCosts = array_map(fn ($item) => $item['AvgCost'], $itemsAvgCosts);

        $CostTotal = 0;
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $avgCost = $avgCosts[$i];
            $CostTotal += $avgCost * $item["QTY"];
        }
        return $CostTotal;
    }

    private function getTotalQuantity($items)
    {
        return array_reduce($items, function ($accumulator, $item) {
            return $accumulator + $this->calculateTotalQty($item);
        }, 0);
    }

    private function calculateTotalQty($item)
    {
        return $item['QTY'] + $item['freeQty'];
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

            $unitQty = $this->getUnitQty($item);

            array_push($invdetailRows, [
                "Invno" => $invno,
                "Branchno" => 1,
                "year" => 2018,
                "Itemno" => $item["Itemno"],
                "ItemDesc" => $item["ItemDesc"],
                "invKind" => 1,
                "QTY" => $this->calculateTotalQty($item),
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
                "Cost" => Item::getAvgCost($item["Itemno"]),
                "Selected" => '0',
                "Unitno" => 1,
                "UnitQty" => $unitQty,
                "Groupno" => 0,
                "GroupQty" => $item["QTY"] / $unitQty,
                "ExchangeRate" => 1,
                "ExpenseRate" => 0,
                // "Partno" => NULL,
                "GroupUnitPrice" => $item["unitPrice"],
                "SaleType" => $item["SaleType"],
                "FreeQty" => $item["freeQty"],
                // "oldIMEI" => NULL,
                // "newIMEI" => NULL,
                "RecycleQty" => 0,
            ]);
        }

        Invdetail::insert($invdetailRows);
    }

    private function getUnitQty($item)
    {
        switch ($item['SaleType']) {
            case 1: //إفرادي=1 , عروض مجانية=4
            case 4:
                return 1;
            case 2: //جملة
                return Item::getWholeSaleQty($item["Itemno"]);
            case 3: //كبار العملاء
                return Item::getVIPSaleQty($item["Itemno"]);
        }
    }

    private function subtractQuantities($items, $branchSubno)
    {
        $storeNo = BranchSub::getStoreNo($branchSubno);

        foreach ($items as $invoiceItem) {
            UserItemQuantities::where([
                "user_store_no" => $storeNo,
                "item_no" => $invoiceItem["Itemno"],
            ])->decrement("available_qty", $invoiceItem["QTY"] + $invoiceItem["freeQty"]);
        }
        UserItemQuantities::where("available_qty", 0)->delete();
    }


    public function show($id)
    {
        $invoice = SaleHeader::find($id);

        return new InvoiceResource($invoice);
    }
}
