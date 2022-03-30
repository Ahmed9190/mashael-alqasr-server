<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "AccNo" => $this->AccNo,
            "AccName" => $this->AccName,
            "VATno" => $this->VATno,
            "CreditLimit" => floatval($this->CreditLimit),
            "debts" => $this->calculateDebtsOfCustomer($this->OpenBalance, $this->Debit, $this->Credit),
        ];
    }

    private function calculateDebtsOfCustomer($openBalance, $debit, $credit)
    {
        $debts = $openBalance + $debit - $credit;
        $roundedDebts = round($debts, 2);

        return $roundedDebts >= 0 ? $roundedDebts : 0;
    }
}
