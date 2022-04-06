<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "BranchSubno" => "required|numeric",
            "whno" => "required|numeric",
            "Accno" => "required|numeric",
            "SaleAccno" => "required|numeric",
            "AccName" => "required|string",
            "total" => "required|numeric",
            "notes" => "nullable|string",
            "PayType" => "required|numeric",
            "Createduserno" => "required|numeric",
            "Custno" => "required|numeric",
            "items" => [
                "*" => [
                    "Itemno" => "required|string",
                    "QTY" => "required|numeric",
                    "unitPrice" => "required|numeric",
                    "ItemDesc" => "required|string",
                    "freeQty" => "required|numeric",
                    "SaleType" => "required|numeric",
                ]
            ]

            // "PayType" => "number:required",
            // "Custno" => "number:required",
            // "vatAccNo" => "number",
            // "notes" => "this is a note",
            // "userno" => "number",
            // "Salesman" => "number:required",
            // "whno" => "number:required",
            // "Accno" => "number:required",
            // "sellPriceNo" => "number:required",
            // "customerName" => "string:required",
            // "details" => [
            //     "itemno" => "number:required",
            //     "qty" => "number:required",
            //     "freeQty" => "number:required"
            // ]
        ];
    }
}
