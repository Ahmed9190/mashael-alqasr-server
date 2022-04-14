<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        $lastDate = DB::table("VATpercentDate")->max("VATdate");
        $lastVat = DB::table("VATpercentDate")->where("VATdate", $lastDate)->get(['VATpercent'])[0]->VATpercent;
        $vatNumbers = Config::getVatNumbers();

        return [
            "data" => [
                "vatRate" => floatval($lastVat / 100),
                "version" => version,
            ] + $vatNumbers,
        ];
    }
}
