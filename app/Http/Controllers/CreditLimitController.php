<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BranchSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreditLimitController extends Controller
{
    public function show(Request $request)
    {
        $customerMaxAllowableCredit = $this->getCustomerMaxAllowableCredit($request->AccNo);
        $delegateMaxAllowableCredit = $this->getDelegateMaxAllowableCredit($request->branchSubno);

        return [
            "data" => max(floatval(round(min($customerMaxAllowableCredit, $delegateMaxAllowableCredit), 2)), 0)
        ];
    }


    private function getCustomerMaxAllowableCredit($AccNo)
    {
        return Account::where('AccNo', $AccNo)->selectRaw('CreditLimit - (OpenBalance + Debit - Credit) as creditLimit')->first()->creditLimit;
    }

    public function getDelegateMaxAllowableCredit($branchSubNo)
    {
        $customersCredit = $this->getDelegateCustomersCreditLimit($branchSubNo);
        $delegateCreditLimit = $this->getDelegateCreditLimit($branchSubNo);
        $delegateMaxAllowableCredit = $delegateCreditLimit - $customersCredit;

        return $delegateMaxAllowableCredit;
    }

    private function getDelegateCustomersCreditLimit($branchSubNo)
    {
        return Account::where('Sellerno', $branchSubNo)
            ->selectRaw("SUM(OpenBalance) + SUM(Debit) - SUM(Credit) as creditLimit")
            ->first()
            ->creditLimit;
    }

    private function getDelegateCreditLimit($branchSubNo)
    {
        return BranchSub::where("Num", $branchSubNo)->first("CreditLimit")->CreditLimit;
    }
}
