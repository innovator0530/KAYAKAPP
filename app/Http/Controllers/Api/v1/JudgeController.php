<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use App\Models\Modality;
use App\Models\Participant;
use App\Models\Heat_configuration;
use App\Models\Com_cat_mod_participant;
use App\Models\Heat_score;
use App\Models\Round_heat;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class JudgeController extends Controller
{
    //
    public function index()
    {
        $judge_heat_scores = [];
        $round_heats = Round_heat::where('status', 3)->get();
        foreach ($round_heats as $round_heat) {
            $round_heat->com_cat_mod_participant->participant;
            $round_heat->com_cat_mod_participant->competition;
            $round_heat->com_cat_mod_participant->category->sex;
            $round_heat->com_cat_mod_participant->modality;
            $round_heat->lycra;

            $temp = Heat_score::where('round_heat_id', $round_heat->id)->where('judge_id', auth()->user()->id)->first();

            $temp->round_heat->lycra;

            array_push($judge_heat_scores, $temp);
        }

        return response()->json([
            'message' => 'success',
            'judge_round_heats' => $round_heats,
            'judge_heat_scores' => $judge_heat_scores,
        ], 200);
    }

    public function storeHeatResults(Request $request) {
        $judge_heat_scores = $request->judge_heat_scores;
        foreach ($judge_heat_scores as $judge_heat_score) {
            Heat_score::find($judge_heat_score['id'])
                ->update([
                    'wave_1' => $judge_heat_score['wave_1'],
                    'wave_2' => $judge_heat_score['wave_2'],
                    'wave_3' => $judge_heat_score['wave_3'],
                    'wave_4' => $judge_heat_score['wave_4'],
                    'wave_5' => $judge_heat_score['wave_5'],
                    'wave_6' => $judge_heat_score['wave_6'],
                    'wave_7' => $judge_heat_score['wave_7'],
                    'wave_8' => $judge_heat_score['wave_8'],
                    'wave_9' => $judge_heat_score['wave_9'],
                    'wave_10' => $judge_heat_score['wave_10'],
                    'penal' => $judge_heat_score['penal'],
                ]);
        }

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
