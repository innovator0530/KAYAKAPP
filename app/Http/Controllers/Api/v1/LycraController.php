<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Lycra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LycraController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $lycras = Lycra::all();
        return response()->json([
            'message' => 'success',
            'lycras' => $lycras
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $lycraId)
    {
        $lycra = Lycra::find($lycraId);
        return response()->json([
            'message' => 'success',
            'lycra' => $lycra
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'color' => 'required|string|between:1,100',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $lycra = Lycra::create(array_merge(
            $validator->validated(),
        ));
        return response()->json([
            'message' => 'Lycra successfully registered',
            'lycra' => $lycra
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Update Lycra
        $request->validate([
            'name' => 'required|string|between:1,100',
            'color' => 'required|string|between:1,100',
        ]);

        $lycra = Lycra::find($request->id);
        $lycra -> update([
            'name' => $request->name,
            'color' => $request->color,
        ]);
        return response()->json([
            'message' => 'Lycra successfully updated',
            'lycra' => $lycra
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $lycraId)
    {
        //delete Lycra
        $lycra = Lycra::find($lycraId);
        $lycra -> delete();
        $lycras = Lycra::all();
        return response()->json([
            'message' => 'successfully deleted',
            'lycras' => $lycras
        ], 200);
    }
    public function getLycraOptions()
    {
        $lycraOptions = collect([]);
        $lycraColorOptions = collect([]);
        $lycras = Lycra::orderBy('id')->get();
        foreach ($lycras as $lycra) {
            $lycraOptions->push($lycra->name);
            $lycraColorOptions->push($lycra->color);
        }
        return response()->json([
            'message' => 'success',
            'lycras' => $lycraOptions,
            'lycraColors' => $lycraColorOptions,
        ], 200);
    }
}
