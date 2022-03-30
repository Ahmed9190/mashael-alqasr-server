<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invdetail extends Model
{
    use HasFactory;
    protected $table = "Invdetail";
    protected $primaryKey = "InvDetailSeq";

    public function saleHeader()
    {
        return $this->belongsTo(SaleHeader::class, "Invno", "invno");
    }
}
