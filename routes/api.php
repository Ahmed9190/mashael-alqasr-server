<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CreditLimitController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReceiptController;
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

const version = 3.0;
const apkUrl = "https://www.mediafire.com/file/doma1jph19lc18j/app-release-19.apk/file";

date_default_timezone_set("Asia/Riyadh");

Route::post('login', [AuthController::class, "login"]);

Route::get("config", [ConfigController::class, "show"]);

Route::get('apk-url', function () {
  return ["data" => apkUrl];
});

Route::group(['middleware' => "auth:api"], function () {
  Route::resource('customer', AccountController::class)->only(["index", "show"]);
  Route::resource('invoice', InvoiceController::class)->only(["index", "store", "show"]);
  Route::resource('receipt', ReceiptController::class)->only(["index", "store", "show"]);
  Route::get('items', [ItemController::class, "index"]);
  Route::get("credit-restrictions", [CreditLimitController::class, 'show']);
});
