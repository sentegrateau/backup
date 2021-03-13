<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->all(),
                    'data' => null,
                ], 422);
            }
            
             $partner = new Partner($request->all());
    
             $partner->save();
            
            return response()->json(
            [
                'error' => false,
                'message' => [$partner->name . ' created successfully'],
                'data' => null,
            ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
                ], 400);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $partner = Partner::where('id', $id)->firstOrFail();
            return response()->json(
                [
                    'error' => false,
                    'message' => [],
                    'data' => $partner,
                ]
                );
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'data' => null,
                    ], 400);
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, partner $partner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $partner = Partner::where('id', $id)->delete();
            return response()->json(
                [
                    'error' => false,
                    'message' => ["Deleted Successfully ! "],
                    'data' => null,
                ]
                );
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'data' => null,
                    ], 400);
                }
    }
}
