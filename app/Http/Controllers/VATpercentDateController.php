<?php

namespace App\Http\Controllers;

use App\Models\VATpercentDate;

class VATpercentDateController extends Controller
{
    public static function show()
    {
        $lastDate = VATpercentDate::max("VATdate");
        $lastVat = VATpercentDate::where("VATdate", $lastDate)->get(['VATpercent'])[0]->VATpercent;
        return $lastVat;
    }
}
