<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $customers = Account::where([
            ["Sellerno", $request->input("BranchSubno")],
            ["AccName", "LIKE", "%{$request->input("searchValue")}%"],
        ])
            ->simplePaginate();
        return CustomerResource::collection($customers->getCollection())->additional([
            "handshakeCode" => $request->input("handshakeCode"),
            "hasMore" => $customers->hasMorePages(),
        ]);
    }

    public function show($id)
    {
        $customer = Account::find($id);
        return new CustomerResource($customer);
    }
}
