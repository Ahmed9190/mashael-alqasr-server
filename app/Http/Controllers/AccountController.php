<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $customers = Account::where("Sellerno", $request->input("BranchSubno"))->get();
        return CustomerResource::collection($customers)->additional([
            //TODO:implement pagination
            "hasMore" => false,
        ]);
    }

    public function show($id)
    {
        $customer = Account::find($id);
        return new CustomerResource($customer);
    }
}
