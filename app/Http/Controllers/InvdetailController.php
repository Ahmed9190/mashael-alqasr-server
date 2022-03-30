<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvdetailResource;
use App\Models\Invdetail;

class InvdetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InvdetailResource::collection(Invdetail::all());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new InvdetailResource(Invdetail::find($id));
    }
}
