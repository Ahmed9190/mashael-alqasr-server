<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CreditLimitController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReceiptController;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

const version = 1.4;
const apkUrl = "https://www.mediafire.com/file/7ju2x643zaplkmm/app-release-5.apk/file";

date_default_timezone_set("Asia/Riyadh");

Route::post('login', [AuthController::class, "login"]);

Route::get("config", [ConfigController::class, "show"]);

Route::get('apk-url', function () {
  return ["data" => apkUrl];
});

Route::get('test', function () {
  $items = Item::all()->toArray();
  foreach ($items as $item) {
    $submit =  DB::select("
    DECLARE @Result float
    
    EXEC sp_QtyInStock
    N'01-01-2000', 
    N'01-01-2100', 
    50, 
    N'104039000339', 
    @Result OUTPUT
    ");
    DB::table("user_item_quantities")->insert(
      [
        "item_no" => $item['itemno'],
        "user_store_no" => 50,
        "available_qty" => intval($submit[0]->Result),
      ]
    );
  }

  $submit =  DB::select("
    DECLARE @Result float
    
    EXEC sp_QtyInStock
    N'01-01-2000', 
    N'01-01-2100', 
    50, 
    N'104039000339', 
    @Result OUTPUT
  ");
  return ["data" => $submit[0]];
});

Route::group(['middleware' => "auth:api"], function () {
  Route::resource('customer', AccountController::class)->only(["index", "show"]);
  Route::resource('invoice', InvoiceController::class)->only(["index", "store", "show"]);
  Route::resource('receipt', ReceiptController::class)->only(["index", "store", "show"]);
  Route::get('items', [ItemController::class, "index"]);
  Route::get("credit-restrictions", [CreditLimitController::class, 'show']);
});
