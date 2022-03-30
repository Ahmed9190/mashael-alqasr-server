<?php

namespace App\Http\Controllers;

use App\Models\RecDetail;
use Illuminate\Http\Request;

class RecDetailController extends Controller
{
    static public function store(Request $request, $recNo)
    {
        return RecDetail::create([
            "RecNo" => $recNo,
            "RecYear" => 2017,
            "Branchno" => 1,
            "AccNo" => $request->input("accNo"),
            "AccYear" => 2017,
            "Amount" => $request->input("amount"),
            "Description" => $request->input("description"),
            "PaidType" => 1,
            "BranchSubno" => $request->input("BranchSubno"),
            // "CostCenterno" => 0,
        ]);
    }
}
