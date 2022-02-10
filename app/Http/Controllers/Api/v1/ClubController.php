<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Http\Controllers\Controller;
use Validator;

class ClubController extends Controller
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
        $clubs = Club::where('name', '!=', 'Independiente')->get();
        return response()->json([
            'message' => 'success',
            'clubs' => $clubs
        ], 200);
    }

    public function getClubOptions()
    {
        $clubOptions = collect([]);
        $clubs = Club::all();
        foreach ($clubs as $club) {
            if ($club->name != "Independiente") {
                $clubOptions->push($club->name);
            }
        }
        return response()->json([
            'message' => 'success',
            'clubs' => $clubOptions,
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $clubId)
    {
        $club = Club::find($clubId);
        return response()->json([
            'message' => 'success',
            'club' => $club
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
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $club = Club::create(array_merge(
            $validator->validated(),
        ));
        return response()->json([
            'message' => 'Club successfully registered',
            'club' => $club
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
        // Update club
        $request->validate([
            'name' => 'required|string|between:1,100',
        ]);
        $club = Club::find($request->id);
        $club -> update([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Club successfully updated',
            'club' => $club
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $clubId)
    {
        //delete club
        $club = Club::find($clubId);
        foreach ($club->participants as $participant) {
            $participant -> update([
                'club_id' => 1,
            ]);
        }
        $club -> delete();
        $clubs = Club::where('name', '!=', 'Independiente')->get();
        return response()->json([
            'message' => 'successfully deleted',
            'clubs' => $clubs
        ], 200);
    }
}
