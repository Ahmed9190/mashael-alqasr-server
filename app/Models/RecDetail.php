<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecDetail extends Model
{
    use HasFactory;

    protected $table = "RecDetail";
    protected $primaryKey = "Seq";

    protected $fillable = [
        "RecNo",
        "RecYear",
        "Branchno",
        "AccNo",
        "AccYear",
        "Amount",
        "Description",
        "PaidType",
        "BranchSubno",
        // "CostCenterno",
    ];
    public $timestamps = false;
}
