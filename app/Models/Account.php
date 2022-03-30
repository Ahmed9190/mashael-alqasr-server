<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Account extends Model
{
    use HasFactory;
    protected $table = 'Account';
    protected $primaryKey = "AccNo";

    protected $casts = ["CreditLimit" => "decimal:2", "VATno" => "int"];

    static public function getName($accNo)
    {
        return Account::find($accNo)->AccName;
    }

    static public function getVATnum($accNo)
    {
        return Account::find($accNo)->VATno;
    }
}
