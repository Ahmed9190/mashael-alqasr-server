<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSub extends Model
{
    use HasFactory;
    protected $table = 'BranchSub';
    protected $primaryKey = 'Num';


    protected $guarded = ["Num"];
    public $timestamps = false;
    protected $casts = [
        "SaleAccno" => "integer"
    ];

    static public function getStoreNo($branchSubno)
    {
        return BranchSub::where("Num", $branchSubno)->first()->StoreNo;
    }

    // public function user()
    // {
    //     return $this->hasOne(User::class, "Num", "BranchSubno");
    // }
}
