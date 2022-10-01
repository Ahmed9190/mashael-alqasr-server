<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BranchSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserUtilsController extends Controller
{
    public function getCreditLimit(Request $request)
    {
        $customerMaxAllowableCredit = $this->getCustomerMaxAllowableCredit($request->AccNo);
        $delegateMaxAllowableCredit = $this->getDelegateMaxAllowableCredit($request->branchSubno);

        $creditRestriction = min($customerMaxAllowableCredit, $delegateMaxAllowableCredit);
        $roundedCreditRestriction = round($creditRestriction, 2);

        return [
            "data" => $roundedCreditRestriction,
            "customerMaxAllowableCredit" => $customerMaxAllowableCredit,
            "delegateMaxAllowableCredit" => $delegateMaxAllowableCredit
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
        Log::info([
            "customersCredit" => $customersCredit,
            "delegateCreditLimit" => $delegateCreditLimit,
        ]);
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

    public function getOverdueDebts(Request $request)
    {
        $BranchSubno = $request->branchSubno;
        $procedureParams = BranchSub::where("Num", $BranchSubno)->select(["ParentCustAccno as ParentAcc", "CreditPeriod as Period"])->first();

        [$overdueDebts] = DB::select(
            "SET NOCOUNT ON;
            DECLARE @OverdueDebts FLOAT;

            EXEC sp_AccDebitCreditMobile
                    @ParentAcc = ?,
                    @Period = ?,
                    @Value = @OverdueDebts OUTPUT;

            SELECT @OverdueDebts AS 'overdueDebts';",
            [$procedureParams->ParentAcc, intval($procedureParams->Period)]
        );

        $overdueDebts = floatval($overdueDebts->overdueDebts);

        return round($overdueDebts, 2);
    }
}
