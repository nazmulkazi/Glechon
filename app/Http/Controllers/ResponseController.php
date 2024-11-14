<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function index(Dataset $dataset)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function create(Dataset $dataset)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Dataset $dataset)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dataset  $dataset
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Dataset $dataset, Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dataset  $dataset
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit(Dataset $dataset, Response $response)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dataset  $dataset
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dataset $dataset, Response $response)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dataset  $dataset
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dataset $dataset, Response $response)
    {
        //
    }
}
