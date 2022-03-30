<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'Users';
    protected $primaryKey = "User_no";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'User_no',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "user_Privilege",
        "Createduserno",
        "CreatedDate",
        "Suspended",
        "CashierAcc",
        "CustAcc",
        "DefaultPrinter",
        "SalePrintType",
        "CityID",
        "user_pwd"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'BranchSubno' => 'int',
        'WHno' => 'int',
        // 'CreatedDate' => 'datetime',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return Hash::make($this->user_pwd);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'User_no';
    }

    public function branchSub()
    {
        return $this->hasOne(BranchSub::class, "Num", "BranchSubno");
    }
}
