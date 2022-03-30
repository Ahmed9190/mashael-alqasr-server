<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Resources\Json\JsonResource;

class RecDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $details = $this->receiptDetails;

        return [
            "Recno" => $this->Recno,
            "Amount" => $this->Amount,
            "Description" => $this->Description,
            // "RecAccno" => $this->RecAccno,
            // "RecType" => $this->RecType,
            // "SaleInvNo" => $this->SaleInvNo,
            "CreatedDate" => $this->CreatedDate,
            // "AccNo" => $details->AccNo,
            "AccName" => Account::getName($details->AccNo),
        ];
    }
}
