<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\BranchSub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['User_no' => $request->input("User_no"), 'password' => $request->input("user_pwd")])) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();

            $token = $user->createToken("user")->accessToken;

            $userWithoutToken = new UserResource(User::find($user['User_no']));

            $userWithToken = $userWithoutToken->additional(["data" => [
                "overdueDebts" => $this->getOverdueDebts($user->BranchSubno),
                "token" => $token,
            ]]);

            return  $userWithToken;
        }
        return response(["error", "Invalid credentials!"], Response::HTTP_UNAUTHORIZED);
    }


    private function getOverdueDebts(int $BranchSubno)
    {
        return Cache::remember("OVERDUE_DEBTS" . $BranchSubno, now()->addHours(5), function () use ($BranchSubno) {
            $procedureParams = BranchSub::where("Num", $BranchSubno)->select(["ParentCustAccno as ParentAcc", "CreditPeriod as Period"])->first();

            [$overdueDebts] = DB::select(
                "SET NOCOUNT ON;
            DECLARE @OverdueDebts FLOAT;
        
            EXEC sp_AccDebitCreditMobile
                    @ParentAcc = ?,
                    @Period = ?,
                    @Value = @OverdueDebts OUTPUT;
                    
            SELECT @OverdueDebts AS 'overdueDebts';",
                [$procedureParams->ParentAcc, $procedureParams->Period]
            );

            $overdueDebts = floatval($overdueDebts->overdueDebts);

            return round($overdueDebts, 2);
        });
    }
}
