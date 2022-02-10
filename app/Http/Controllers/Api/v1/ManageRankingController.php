<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use App\Models\Modality;
use App\Models\Participant;
use App\Models\Competition;
use App\Models\Com_cat_mod_participant;
use App\Models\Round_heat;
use App\Models\Ranking_position_point;
use App\Models\Manage_ranking_point;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use PDF;

class ManageRankingController extends Controller
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
    public function getAllCategoryModality()
    {
        $all_category_modality = [];
        $category_modality_with_results = [];
        $categories = Category::all();
        $modalities = Modality::all();
        foreach ($categories as $category) {
            $category->sex;
            foreach ($modalities as $modality) {
                array_push($all_category_modality, $category->name." ".$category->sex->name." ".$modality->name);
                $temps = Manage_ranking_point::where('category_id', $category->id)
                                            ->where('modality_id', $modality->id)->get(); 
                if (count($temps) > 0) {
                    array_push($category_modality_with_results, $category->name." ".$category->sex->name." ".$modality->name);
                }
            }
        }
        return response()->json([
            'message' => 'success',
            'all_category_modality' => $all_category_modality,
            'category_modality_with_results' => $category_modality_with_results,
        ], 200);
    }

    public function getCategoryRankingPoints(Request $request) {
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2])->first();

        $category_ranking_points_temp = [];
        $participant_ids = Manage_ranking_point::select('participant_id')
                                                ->where('category_id', $category->id)
                                                ->where('modality_id', $modality->id)->distinct()->get();
        $competition_ids = Manage_ranking_point::select('competition_id')
                                                ->where('category_id', $category->id)
                                                ->where('modality_id', $modality->id)->distinct()->get();
        foreach ($participant_ids as $participant_id) {
            $category_ranking_point = [];
            $temp = [];
            $participant = Participant::find($participant_id->participant_id);
            $category_ranking_point["participante"] = $participant->name.' '.$participant->surname;
            $points = [];
            foreach ($competition_ids as $competition_id) {
                $competition = Competition::find($competition_id->competition_id);
                $competition->title = (strlen($competition->title) > 15) ? substr($competition->title,0,15).'...' : $competition->title;
                $manage_ranking_point = Manage_ranking_point::where('competition_id', $competition_id->competition_id)
                                                            ->where('category_id', $category->id)
                                                            ->where('modality_id', $modality->id)
                                                            ->where('participant_id', $participant_id->participant_id)->get();
                if (count($manage_ranking_point) > 0) {
                    $category_ranking_point["$competition->title"] = $manage_ranking_point[0]->ranking_points;
                } else {
                    $category_ranking_point["$competition->title"] = 0;
                }
                array_push($points, $category_ranking_point["$competition->title"]);
            }
            array_push($points, 0);
            array_push($points, 0);
            rsort($points);
            $category_ranking_point["suma_3_mejores"] = $points[0] + $points[1] + $points[2];
            $category_ranking_point["1º"] = $points[0];
            $category_ranking_point["2º"] = $points[1];
            $category_ranking_point["3º"] = $points[2];
            array_push($category_ranking_points_temp, $category_ranking_point);
        }
        usort($category_ranking_points_temp, function($a, $b) {
            return $b['suma_3_mejores'] - $a['suma_3_mejores'];
        });
        $category_ranking_points = [];
        foreach ($category_ranking_points_temp as $index => $category_ranking_point) {
            $temp["posicion"] = ($index + 1)."º";
            $temp["participante"] = $category_ranking_point["participante"];
            $temp["suma_3_mejores"] = $category_ranking_point["suma_3_mejores"];
            foreach ($competition_ids as $competition_id) {
                $competition = Competition::find($competition_id->competition_id);
                $competition->title = (strlen($competition->title) > 15) ? substr($competition->title,0,15).'...' : $competition->title;
                $temp["$competition->title"] = $category_ranking_point["$competition->title"];
            }
            $temp["1º"] = $category_ranking_point["1º"];
            $temp["2º"] = $category_ranking_point["2º"];
            $temp["3º"] = $category_ranking_point["3º"];
            
            array_push($category_ranking_points, $temp);
        }

        return response()->json([
            'message' => 'success',
            'category_ranking_points' => $category_ranking_points,
            'competition_number' => count($competition_ids),
        ], 200);
    }

    public function getRegisteredAndNonParticipants($competitionId)
    {
        $registered_participants = [];
        $non_participants = [];

        $participants = Participant::all();
        foreach ($participants as $participant) {
            $participant->sex;
            $participant->club;

            $com_cat_mod_participant = Com_cat_mod_participant::where('participant_id', $participant->id)
                                                                    ->where('competition_id', $competitionId)
                                                                    ->get();
            if (count($com_cat_mod_participant) > 0) {
                array_push($registered_participants, $participant);
            } else {
                array_push($non_participants, $participant);
            }
        }

        return (object)[
            'registered_participants' => $registered_participants,
            'non_participants' => $non_participants,
        ];
    }

    public function getParticipantsForCompetition($competitionId)
    {
        $result = $this->getRegisteredAndNonParticipants($competitionId);
        return response()->json([
            'message' => 'success',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function addParticipantToCompetition(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'surname' => 'required|string',
            'dni_ficha' => 'required|string',
            'birthday' => 'required',
            'sex' => 'required|string',
            'club' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $participant = Participant::where('dni_ficha', $request->dni_ficha)->get();

        if (count($participant) == 0) {
    
            $sex = Sex::where('name', $request->sex)->first();
            $club = Club::where('name', $request->club)->first();
            $participant = new Participant;
            $participant->name = $request->name;
            $participant->surname = $request->surname;
            $participant->dni_ficha = $request->dni_ficha;
            $participant->setDateAttribute($request->birthday);
            $participant->sex()->associate($sex);
            $participant->club()->associate($club);
            $participant->save();
            // var_dump($participant);
            $categories = $this->getCategoryFromParticipant($participant);

            if (count($categories) == 0) {
                // $participant->delete();
                return response()->json([
                    'message' => 'No se incluye al participante',
                    'participant' => $participant
                ], 400);
            }

            foreach ($categories as $category) {
                foreach ($request->modality as $modality_name) {
                    $modality = Modality::where('name', $modality_name)->first();

                    $com_cat_mod_participant = new Com_cat_mod_participant;
                    $com_cat_mod_participant->competition_id = $request->competitionId;
                    $com_cat_mod_participant->participant_id = $participant->id;
                    $com_cat_mod_participant->modality_id = $modality->id;
                    $com_cat_mod_participant->category_id = $category->id;
                    $com_cat_mod_participant->save();
                }
            }

            $result = $this->getRegisteredAndNonParticipants($request->competitionId);
            return response()->json([
                'message' => 'Participante añadido correctamente',
                'registered_participants' => $result->registered_participants,
                'non_participants' => $result->non_participants,
            ], 200);
        } else {
            return response()->json([
                'message' => 'El participante selccionado ya está registrado,',
                'participant' => $participant
            ], 201);
        }
    }

    public function registParticipantToCompetition(Request $request) {
        $validator = Validator::make($request->all(), [
            'modality' => 'required',
            'category' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $participant = Participant::find($request->participantId);
        foreach ($request->category as $category_name) {
            $category = Category::where('name', $category_name)->where('sex_id', $participant->sex_id)->first();
            foreach ($request->modality as $modality_name) {
                $modality = Modality::where('name', $modality_name)->first();
                $com_cat_mod_participant = new Com_cat_mod_participant;
                $com_cat_mod_participant->competition_id = $request->competitionId;
                $com_cat_mod_participant->participant_id = $request->participantId;
                $com_cat_mod_participant->modality_id = $modality->id;
                $com_cat_mod_participant->category_id = $category->id;
                $com_cat_mod_participant->save();
            }
        }

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'Participante añadido correctamente',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function getParticipantCategoryOptions($participantId) {
        $participant = Participant::find($participantId);
        $categories = $this->getCategoryFromParticipant($participant);
        $participant_category_options = [];
        foreach ($categories as $category) {
            array_push($participant_category_options, $category->name);
        }
        return response()->json([
            'message' => 'Success',
            'participant_category_options' => $participant_category_options,
        ], 200);
    }

    public function getModAndCatOfParticipant(Request $request) {
        $modality_id_array = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->pluck('modality_id');
        $modality_id = $modality_id_array->unique();
        $modality_id->all();
        $modality_participant = Modality::whereIn('id', $modality_id)->pluck('name');

        $category_id_array = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->pluck('category_id');
        $category_id = $category_id_array->unique();
        $category_id->all();
        $category_participant = Category::whereIn('id', $category_id)->pluck('name');

        $participant = Participant::find($request->participantId);
        $categories = $this->getCategoryFromParticipant($participant);
        $participant_category_options = [];
        foreach ($categories as $category) {
            array_push($participant_category_options, $category->name);
        }
        $available_category_options = [];
        $categories = Category::where('sex_id', $participant->sex_id)->whereNotIn('name', ['Cadete'])->get();
        foreach ($categories as $category) {
            $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->whereIn('modality_id', $modality_id)->get();
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
            if (count($round_heats) == 0) {
                array_push($available_category_options, $category->name);
            }
        }

        return response()->json([
            'message' => 'Success',
            'modality_participant' => $modality_participant,
            'category_participant' => $category_participant,
            'participant_category_options' => $participant_category_options,
            'available_category_options' => $available_category_options,
        ], 200);
    }

    public function getAvailableCategories(Request $request) {
        $participant = Participant::find($request->participantId);
        $modality_ids = Modality::select('id')->whereIn('name', $request->modality)->get();
        $available_category_options = [];
        $categories = Category::where('sex_id', $participant->sex_id)->whereNotIn('name', ['Cadete'])->get();
        foreach ($categories as $category) {
            $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->whereIn('modality_id', $modality_ids)->get();
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
            if (count($round_heats) == 0) {
                array_push($available_category_options, $category->name);
            }
        }

        return response()->json([
            'message' => 'Success',
            'available_category_options' => $available_category_options,
        ], 200);
    }

    public function updateParticipantToCompetition(Request $request) {
        $validator = Validator::make($request->all(), [
            'modality' => 'required',
            'category' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->delete();
        
        $participant = Participant::find($request->participantId);
        foreach ($request->category as $category_name) {
            $category = Category::where('name', $category_name)->where('sex_id', $participant->sex_id)->first();
            foreach ($request->modality as $modality_name) {
                $modality = Modality::where('name', $modality_name)->first();
                $com_cat_mod_participant = new Com_cat_mod_participant;
                $com_cat_mod_participant->competition_id = $request->competitionId;
                $com_cat_mod_participant->participant_id = $request->participantId;
                $com_cat_mod_participant->modality_id = $modality->id;
                $com_cat_mod_participant->category_id = $category->id;
                $com_cat_mod_participant->save();
            }
        }

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'success',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function unregistParticipantToCompetition(Request $request) {
        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->delete();

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'success',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function getCategoryFromParticipant($participant) {
        $year = date('Y',strtotime($participant->birthday));

        $categories = Category::where('year1', '<=', $year)
                        ->where('year2', '>=', $year)
                        ->where('sex_id', $participant->sex_id)
                        ->get();
        return $categories;
    }

    // public function finalRankingPDF(Request $request) {
    //     $data = $request->data;
    //     $pdf = PDF::loadView('final-ranking', ['data' => $data]);
    //     // return $pdf->stream();
    //     return $pdf->download('demo.pdf');
    // }
}
