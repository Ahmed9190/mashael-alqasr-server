<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $table = 'Config';
    protected $casts = [
        "SaleAccno" => "int",
        "CashSaleAccno" => "int",
    ];

    static public function getVatNumbers()
    {
        return Config::first(["SaleAccno", "CashSaleAccno"])->toArray();
    }
}
