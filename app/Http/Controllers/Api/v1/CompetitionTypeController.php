<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Competition_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CompetitionTypeController extends Controller
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
        $competition_types = Competition_type::all();
        return response()->json([
            'message' => 'success',
            'competition_types' => $competition_types
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $competition_typeId)
    {
        $competition_type = Competition_type::find($competition_typeId);
        return response()->json([
            'message' => 'success',
            'competition_type' => $competition_type,
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
            'description' => 'required|max:1000',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $competition_type = Competition_type::create(array_merge(
            $validator->validated(),
        ));
        return response()->json([
            'message' => 'Tipo Competición Creado Correctamente',
            'competition_type' => $competition_type
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
        // Update competition_type
        $request->validate([
            'name' => 'required|string|between:1,100',
            'description' => 'required|max:1000',
        ]);
        $competition_type = Competition_type::find($request->id);
        $competition_type -> update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json([
            'message' => 'Tipo Competición Actualizado Correctamente',
            'competition_type' => $competition_type
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $competition_typeId)
    {
        //delete competition_type
        $competition_type = Competition_type::find($competition_typeId);
        $competition_type -> delete();
        $competition_types = Competition_type::all();
        return response()->json([
            'message' => 'Tipo Competición Eliminado Correctamente',
            'competition_types' => $competition_types
        ], 200);
    }

    public function getTypeOptions()
    {
        $typeOptions = collect([]);
        $competition_types = Competition_type::all();
        foreach ($competition_types as $competition_type) {
            $typeOptions->push($competition_type->name);
        }
        $typeOptions->all();
        return response()->json([
            'message' => 'success',
            'competition_types' => $typeOptions,
        ], 200);
    }
}
