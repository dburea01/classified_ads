<?php

namespace App\Http\Controllers;

use App\Models\ClassifiedAd;
use Illuminate\Http\Request;

class ClassifiedAdController extends Controller
{
    private $organizationId;

    public function __construct(Request $request)
    {
        $this->organizationId = $request->header('x-api-key');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classifiedAds = ClassifiedAd::with('site')->paginate(10);

        return response()->json($classifiedAds, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function show(ClassifiedAd $classifiedAd)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassifiedAd $classifiedAd)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassifiedAd $classifiedAd)
    {
    }
}
