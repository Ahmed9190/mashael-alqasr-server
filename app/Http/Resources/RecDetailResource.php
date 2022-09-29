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
            "number" => $this->Recno,
            "customerName" => Account::getName($details->AccNo),
            "total" => $this->Amount,
            "description" => $this->Description,
            "createdDate" => $this->CreatedDate,
            // "RecAccno" => $this->RecAccno,
            // "RecType" => $this->RecType,
            // "SaleInvNo" => $this->SaleInvNo,
            // "AccNo" => $details->AccNo,
        ];
    }
}
