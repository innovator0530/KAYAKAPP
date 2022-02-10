<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Club;
use App\Models\Sex;
use App\Http\Controllers\Controller;
use Validator;

class ParticipantController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $participants = Participant::all();
        foreach ($participants as $participant) {
            $participant->sex;
            $participant->club;
            $participant->ranking = $participant->ranking==1 ? "Ranking SI" : "Ranking NO";
        }
        return response()->json([
            'message' => 'success',
            'participants' => $participants
        ], 200);
    }
    /**
     * Response one data by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Request $request, $participantId)
    {
        $participant = Participant::find($participantId);
        $participant->sex;
        $participant->club;
        $participant->birthday = $participant->getDateAttribute();
        $participant->ranking = $participant->ranking==1 ? "Ranking SI" : "Ranking NO";

        return response()->json([
            'message' => 'success',
            'participant' => $participant
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'surname' => 'required|string',
            'dni_ficha' => 'required|string',
            'birthday' => 'required',
            'sex' => 'required|string',
            'club' => 'required',
            'ranking' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $participant = Participant::where('dni_ficha', $request->dni_ficha)->get();
        if (count($participant) != 0) {
            return response()->json([
                'message' => 'Ya existe un federado con ese DNI',
                'participant' => $participant
            ], 201);
        }

        $sex = Sex::where('name', $request->sex)->first();
        $club = Club::where('name', $request->club)->first();
        $participant = new Participant;
        $participant->name = $request->name;
        $participant->surname = $request->surname;
        $participant->dni_ficha = $request->dni_ficha;
        $participant->setDateAttribute($request->birthday);
        $participant->sex()->associate($sex);
        $participant->club()->associate($club);
        $participant->ranking = $request->ranking == "Ranking NO" ?  0 : 1;
        $participant->save();

        return response()->json([
            'message' => 'Participante añadido correctamente',
            'participant' => $participant
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Update participant
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'surname' => 'required|string',
            'dni_ficha' => 'required|string',
            'birthday' => 'required',
            'sex' => 'required|string',
            'club' => 'required|string',
            'ranking' => 'required|string',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $sex = Sex::where('name', $request->sex)->first();
        $club = Club::where('name', $request->club)->first();
        $participant = Participant::find($request->id);
        $participant->name = $request->name;
        $participant->surname = $request->surname;
        $participant->dni_ficha = $request->dni_ficha;
        $participant->setDateAttribute($request->birthday);
        $participant->sex_id = $sex->id;
        $participant->club_id = $club->id;
        $participant->ranking = $request->ranking == "Ranking NO" ?  0 : 1;
        $participant->save();

        return response()->json([
            'message' => 'Participante modificado correctamente',
            'participant' => $participant
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $participantId)
    {
        //delete participant
        $participant = Participant::find($participantId);
        $participant -> delete();
        $participants = Participant::all();
        foreach ($participants as $participant) {
            $participant->sex;
            $participant->club;
            
            $participant->ranking = $participant->ranking==1 ? "Ranking SI" : "Ranking NO";
        }

        return response()->json([
            'message' => 'success',
            'participants' => $participants
        ], 200);
    }
}
