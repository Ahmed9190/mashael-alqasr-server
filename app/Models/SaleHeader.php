<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleHeader extends Model
{
    use HasFactory;
    protected $table = "SaleHeader";
    protected $primaryKey = "invno";

    protected $fillable = ["invno"];
    public $timestamps = false;

    protected $casts = ["total" => "decimal:2"];

    public function invoiceItems()
    {
        return $this->hasMany(Invdetail::class, "Invno", "invno");
    }

    public function getCreatedUser()
    {
        return $this->hasOne(User::class, "User_no", "Createduserno");
    }

    public static function getNextInvNo()
    {
        return SaleHeader::latest("invno")->first("invno")->invno + 1;
    }
}
