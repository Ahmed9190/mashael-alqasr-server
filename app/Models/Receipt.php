<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $table = "Receipt";
    protected $primaryKey = "Recno";

    protected $fillable = [
        "Recno",
        "RecYear",
        "Branchno",
        "RecDate",
        "Amount",
        "Description",
        "RecAccno",
        "RecType",
        "Posted",
        "SaleInvNo",
        "Createduserno",
        "CreatedDate",
    ];
    public $timestamps = false;

    protected $casts = [
        "Amount" => "double",
        "userNo" => "int",
        "cashAccNo" => "int",
        "accNo" => "int",
        "amount" => "int",
        "BranchSubno" => "int",
    ];

    public function receiptDetails()
    {
        return $this->hasOne(RecDetail::class, "RecNo", "Recno");
    }
}
