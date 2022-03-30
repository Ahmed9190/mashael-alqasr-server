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
            "WHno" => $this->WHno,
            //TODO:think to remove it if it is not necessary 
            "creditLimit" => floatval($this->branchSub->CreditLimit),
            "storeNo" => floatval($this->branchSub->StoreNo),
            "cashAccno" => intval($this->branchSub->CashAccno),
            "saleAccountno" => $this->branchSub->SaleAccno,
        ];
    }
}
