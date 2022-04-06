<?php

namespace App\Http\Resources;

use App\Models\BranchSub;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "userNo" => $this->User_no,
            "userName" => $this->User_Name,
            "BranchSubno" => $this->BranchSubno,
            "storeNo" => floatval($this->branchSub->StoreNo),
            "cashAccno" => intval($this->branchSub->CashAccno),
            "saleAccountno" => $this->branchSub->SaleAccno,
        ];
    }
}
