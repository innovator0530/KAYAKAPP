<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Competition;
use App\Models\Competition_type;
use App\Models\Status;
use App\Models\Lycra;
use App\Models\Modality;
use App\Models\Category;
use App\Models\Com_cat_mod_participant;
use App\Models\Round_heat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
// use Carbon\Carbon;

class CompetitionController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
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
        $competitions = Competition::all();
        foreach ($competitions as $competition) {
            $competition->competition_type;
            $competition->status;
            $competition->isOpening = true;
            $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $competition->id)->get();
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
            if (count($round_heats) > 0) {
                $competition->isOpening = false;
            }
        }
        return response()->json([
            'message' => 'success',
            'competitions' => $competitions
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $competitionId)
    {
        $competition = Competition::find($competitionId);
        $competition->competition_type;
        $competition->status;
        $competition->date = $competition->getDateAttribute();

        $categoryNames = collect([]);
        foreach ($competition->categories as $category) {
            $temp = $category->sex->name;
            $categoryNames->push("$category->name, $temp");
        }
        $competition->categoryNames = $categoryNames;

        $modalityNames = collect([]);
        foreach ($competition->modalities as $modality) {
            $modalityNames->push($modality->name);
        }
        $competition->modalityNames = $modalityNames;

        $lycraNames = [];
        foreach ($competition->lycras as $lycra_id) {
            $lycra = Lycra::find($lycra_id);
            array_push($lycraNames, $lycra->name);
        }
        $competition->lycraNames = $lycraNames;

        return response()->json([
            'message' => 'success',
            'competition' => $competition,
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:1,100',
            'competition_type' => 'required|string',
            'description' => 'required|string',
            'place' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'organizer' => 'required',
            'ranking_score' => 'required',
            'status' => 'required',
            'lycra' => 'required',
            'modality' => 'required',
            'category' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->file('logo')){
            $result = $request->file('logo')->store('public');
        } else {
            $result = null;
        }

        $competition_type = Competition_type::where('name', $request->competition_type)->first();
        $status = Status::where('name', $request->status)->first();
        $lycra_ids = [];
        foreach (explode(',', $request->lycra) as $lycra_name) {
            $lycra = Lycra::where('name', $lycra_name)->first();
            array_push($lycra_ids, $lycra->id);
        }

        $competition = new Competition;
        $competition->title = $request->title;
        $competition->description = $request->description;
        $competition->place = $request->place;
        $competition->setDateAttribute($request->date);
        $competition->time = $request->time;
        $competition->organizer = $request->organizer;
        $competition->ranking_score = $request->ranking_score;
        $competition->lycras = $lycra_ids;
        $competition->competition_type()->associate($competition_type);
        $competition->status()->associate($status);
        if ($result != null) {
            $competition->logo = explode("/",$result)[1];
        }
        $competition->save();

        $modalities = Modality::whereIn('name', explode(",",$request->modality))->get();
        $modalityIds = collect([]);
        foreach ($modalities as $modality) {
            $modalityIds->push($modality->id);
        }
        $competition->modalities()->attach($modalityIds);

        $categoryNames = collect([]);
        foreach (explode(",",$request->category) as $str) {
            $str = explode(",",$str);
            $categoryNames->push($str[0]);
        }
        $categories = Category::whereIn('name', $categoryNames)->get();
        $categoryIds = collect([]);
        foreach ($categories as $category) {
            $categoryIds->push($category->id);
        }
        $competition->categories()->attach($categoryIds);

        return response()->json([
            'message' => 'Competición Creada Correctamente',
            'competition' => $competition
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
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:1,100',
            'competition_type' => 'required|string',
            'description' => 'required|string',
            'place' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'organizer' => 'required',
            'ranking_score' => 'required',
            'status' => 'required',
            'lycra' => 'required',
            'modality' => 'required',
            'category' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->file('logo')){
            $result = $request->file('logo')->store('public');
        } else {
            $result = null;
        }

        $competition_type = Competition_type::where('name', $request->competition_type)->first();
        $status = Status::where('name', $request->status)->first();
        $lycra_ids = [];
        foreach (explode(",",$request->lycra) as $lycra_name) {
            $lycra = Lycra::where('name', $lycra_name)->first();
            array_push($lycra_ids, $lycra->id);
        }

        $competition = Competition::find($request->id);
        $competition->title = $request->title;
        $competition->description = $request->description;
        $competition->place = $request->place;
        $competition->setDateAttribute($request->date);
        $competition->time = $request->time;
        $competition->organizer = $request->organizer;
        $competition->ranking_score = $request->ranking_score;
        $competition->lycras = $lycra_ids;
        $competition->competition_type_id = $competition_type->id;
        $competition->status_id = $status->id;
        if ($result != null) {
            $competition->logo = explode("/",$result)[1];
        }
        $competition->save();

        $modalities = Modality::whereIn('name', explode(",",$request->modality))->get();
        $modalityIds = collect([]);
        foreach ($modalities as $modality) {
            $modalityIds->push($modality->id);
        }
        $competition->modalities()->sync($modalityIds);

        $categoryNames = collect([]);
        foreach (explode(",",$request->category) as $str) {
            $str = explode(",",$str);
            $categoryNames->push($str[0]);
        }
        $categories = Category::whereIn('name', $categoryNames)->get();
        $categoryIds = collect([]);
        foreach ($categories as $category) {
            $categoryIds->push($category->id);
        }
        $competition->categories()->sync($categoryIds);

        return response()->json([
            'message' => 'Competición Actualizada Correctamente',
            'competition' => $competition
        ], 201);
    }

    public function statusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $status = Status::where('name', $request->status)->first();

        $competition = Competition::find($request->id);
        $competition -> update([
            'status_id' => $status->id,
        ]);

        $competitions = Competition::all();
        foreach ($competitions as $competition) {
            $competition->competition_type;
            $competition->status;
        }
        return response()->json([
            'message' => "success",
            'competitions' => $competitions
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $competitionId)
    {
        //delete competition
        $competition = Competition::find($competitionId);
        $competition -> delete();

        $competitions = Competition::all();
        foreach ($competitions as $competition) {
            $competition->competition_type;
            $competition->status;
        }
        return response()->json([
            'message' => 'Competición Eliminada Correctamente',
            'competitions' => $competitions
        ], 200);
    }
}
