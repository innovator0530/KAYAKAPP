<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use App\Models\Modality;
use App\Models\Competition;
use App\Models\Participant;
use App\Models\Heat_configuration;
use App\Models\Com_cat_mod_participant;
use App\Models\Heat_score;
use App\Models\Round_heat;
use App\Models\Role;
use App\Models\Manage_ranking_point;
use App\Models\Ranking_position_point;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LiveManagementController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['initHome', 'getCompetitionHeats', 'initHomeHeatDetails']]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryModalityWithPart($competitionId)
    {
        $category_modality_with_part = [];
        $categories = Category::all();
        $modalities = Modality::all();
        foreach ($categories as $category) {
            $category->sex;
            foreach ($modalities as $modality) {
                $temps = Com_cat_mod_participant::where('competition_id', $competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();
                if (count($temps) > 0) {
                    $option = [
                        "label" => '',
                        "status" => '',
                    ];
                    $option["label"] = $category->name." ".$category->sex->name." ".$modality->name;
                    if (count($temps) < 3) {
                        $option["status"] = 'deactive';
                        array_push($category_modality_with_part, $option);
                    } else {
                        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $competitionId)
                                                            ->where('category_id', $category->id)
                                                            ->where('modality_id', $modality->id)->get();
                        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
                        $isActive = false;
                        $round = -1;
                        $heat = -1;
                        $categoryid = -1;
                        $modalityid = -1;
                        foreach ($round_heats as $round_heat) {
                            if ($round_heat->status == 3) {
                                $isActive = true;
                                $categoryid = $category->id;
                                $modalityid = $modality->id;
                                $round = $round_heat->round;
                                $heat = $round_heat->heat;
                                break;
                            }
                        }
                        $finished = true;
                        if (count($round_heats) == 0) {
                            $finished = false;
                        }
                        foreach ($round_heats as $round_heat) {
                            if ($round_heat->status != 1) {
                                $finished = false;
                                break;
                            }
                        }
                        if ($isActive) {
                            $option["status"] = 'active';
                            $option["categoryid"] = $categoryid;
                            $option["modalityid"] = $modalityid;
                            $option["round"] = $round;
                            $option["heat"] = $heat;
                            array_push($category_modality_with_part, $option);
                        } else if ($finished) {
                            $option["status"] = 'finished';
                            array_push($category_modality_with_part, $option);
                        } else {
                            $option["status"] = '';
                            array_push($category_modality_with_part, $option);
                        }
                    }
                }
            }
        }
        return response()->json([
            'message' => 'success',
            'category_modality_with_part' => $category_modality_with_part
        ], 200);
    }

    public function getParticipantsByCompetitionCategoryModality(Request $request)
    {
        $participants = [];
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2])->first();
        $competition = Competition::find($request->competitionId);
        $temps = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();
        foreach ($temps as $temp) {
            $participant = Participant::find($temp->participant_id);
            $participant->club;
            $participant->sex;
            array_push($participants, $participant);
        }

        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId);
                                                            //  ->where('category_id', $category->id);
                                                            //  ->where('modality_id', $modality->id)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
        $status1 = 0;
        $deleteStatus = true;
        if (count($round_heats) > 0) {
            $status1 = 2;
            foreach ($round_heats as $round_heat) {
                if ($round_heat->status == 3) {
                    $status1 = 1;
                }
            }
        }

        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                                            ->where('category_id', $category->id)
                                                                            ->where('modality_id', $modality->id)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
        $status = 0;
        $deleteStatus = true;
        if (count($round_heats) > 0) {
            $status = 2;
            foreach ($round_heats as $round_heat) {
                if ($round_heat->status != 1) {
                    $status = 1;
                }
            }
            foreach ($round_heats as $round_heat) {
                if ($round_heat->status != 2) {
                    $deleteStatus = false;
                    break;
                }
            }
        }
        // $status = 1;
        return response()->json([
            'message' => 'success',
            'participants_competition_category_modality' => $participants,
            'category_id' => $category->id,
            'modality_id' => $modality->id,
            'competition' => $competition,
            'status' => $status,
            'status1' => $status1,
            'deleteStatus' => $deleteStatus,
        ], 200);
    }

    public function unregistParticipantToCompetitionCategoryModality(Request $request)
    {
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2])->first();

        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->where('category_id', $category->id)
                                    ->where('modality_id', $modality->id)
                                    ->delete();
        $participants = [];
        $temps = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();

        foreach ($temps as $temp) {
            $participant = Participant::find($temp->participant_id);
            $participant->club;
            $participant->sex;
            array_push($participants, $participant);
        }
        return response()->json([
            'message' => 'success',
            'participants_competition_category_modality' => $participants
        ], 200);
    }

    public function createFirstCompetitionBoxes(Request $request)
    {
        $com_cat_mod_participants = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();

        $round_heats = Round_heat::where('com_cat_mod_participant_id', $com_cat_mod_participants[0]->id)->where('round', 1)->get();
        if (count($round_heats) == 0) {
            $all_participant_ids = Manage_ranking_point::select('participant_id')
                                                ->where('category_id', $request->categoryId)
                                                ->where('modality_id', $request->modalityId)->distinct()->get();
            $competition_ids = Manage_ranking_point::select('competition_id')
                                                ->where('category_id', $request->categoryId)
                                                ->where('modality_id', $request->modalityId)->distinct()->get();

            $points = [];
            foreach ($all_participant_ids as $participant_id) {
                $points_temp = [];
                foreach ($competition_ids as $competition_id) {
                    $manage_ranking_point = Manage_ranking_point::where('competition_id', $competition_id->competition_id)
                                                                ->where('category_id', $request->categoryId)
                                                                ->where('modality_id', $request->modalityId)
                                                                ->where('participant_id', $participant_id->participant_id)->get();
                    if (count($manage_ranking_point) > 0) {
                        $temp = $manage_ranking_point[0]->ranking_points;
                    } else {
                        $temp = 0;
                    }
                    array_push($points_temp, $temp);
                }
                array_push($points_temp, 0);
                array_push($points_temp, 0);
                rsort($points_temp);
                array_push($points, ["id" => $participant_id->participant_id, "points" => $points_temp[0] + $points_temp[1] + $points_temp[2]]);
            }
            usort($points, function($a, $b) {
                return $b["points"] - $a["points"];
            });

            $sort_com_cat_mod_participants = [];
            foreach ($com_cat_mod_participants as $com_cat_mod_participant) {
                $com_cat_mod_participant->ranking = 0;
            }
            foreach ($points as $index => $point) {
                foreach ($com_cat_mod_participants as $com_cat_mod_participant) {
                    $com_cat_mod_participant->flag = 0;
                    if ($point["id"] == $com_cat_mod_participant->participant_id) {
                        $com_cat_mod_participant->ranking = $index + 1;
                        array_push($sort_com_cat_mod_participants, $com_cat_mod_participant);
                        break;
                    }
                }
            }
            foreach ($com_cat_mod_participants as $com_cat_mod_participant) {
                if ($com_cat_mod_participant->ranking == 0) {
                    array_push($sort_com_cat_mod_participants, $com_cat_mod_participant);
                }
            }

            $heat_configuration = Heat_configuration::where('participant_number', count($com_cat_mod_participants))->first();
            $heat_number = count($heat_configuration->assign_array);
            $lycras = Competition::find($request->competitionId)->lycras;
            foreach ($sort_com_cat_mod_participants as $index => $com_cat_mod_participant) {
                $i = $index + 1;
                $round_heat = new Round_heat;
                $round_heat->round = 1;
                $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                $round_heat->com_cat_mod_participant_id = $com_cat_mod_participant->id;
                $round_heat->lycra_id = $lycras[floor($index / $heat_number)];
                $round_heat->ranking = $com_cat_mod_participant->ranking;
                $round_heat->save();
            }

            return response()->json([
                'message' => 'success',
                'points' => $points
            ], 200);
        }
    }

    public function deleteCompetitionBoxes(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->delete();

        return response()->json([
            'message' => 'success',
        ], 200);
    }

    public function initHome(Request $request)
    {
        $category_modality = [];
        $competitions = Competition::where('status_id', 3)->get();
        $categories = Category::all();
        $modalities = Modality::all();
        if (count($competitions) > 0) {
            $competition = $competitions[0];
            foreach ($categories as $category) {
                $category->sex;
                foreach ($modalities as $modality) {
                    $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $competition->id)
                                            ->where('category_id', $category->id)
                                            ->where('modality_id', $modality->id)->get();
                    if (count($com_cat_mod_participant_ids) > 0) {
                        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->get();
                        if (count($round_heats) > 0) {
                            array_push($category_modality, $category->name." ".$category->sex->name." ".$modality->name);
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'success',
                'competition' => $competition,
                'category_modality' => $category_modality,
            ], 200);
        }

        return response()->json([
            'message' => 'success',
            'competition' => null,
            'category_modality' => [],
        ], 200);
    }

    public function initHomeHeatDetails(Request $request)
    {
        $competition = Competition::find($request->competitionId);
        if ($competition->status_id == 3) {
            $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                                ->where('category_id', $request->categoryId)
                                                                ->where('modality_id', $request->modalityId)->get();
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                    ->where('round', $request->round)
                                    ->where('heat', $request->heat)->get();
            $isNew = true;
            foreach ($round_heats as $round_heat) {
                if ($round_heat->points != 0) {
                    $isNew = false;
                }
            }
            if ($isNew) {
                $points = [];
                foreach ($round_heats as $round_heat) {
                    $average =  [
                        'wave_1' => 0,
                        'wave_2' => 0,
                        'wave_3' => 0,
                        'wave_4' => 0,
                        'wave_5' => 0,
                        'wave_6' => 0,
                        'wave_7' => 0,
                        'wave_8' => 0,
                        'wave_9' => 0,
                        'wave_10' => 0,
                    ];
                    $heat_scores = Heat_score::where('round_heat_id', $round_heat->id)->get();
                    for ($i = 1; $i < 11; $i++) {
                        $sum = 0;
                        $divider = 0;
                        foreach ($heat_scores as $heat_score) {
                            if ($heat_score['wave_'.$i] > 0) {
                                $sum += $heat_score['wave_'.$i]/1;
                                $divider++;
                            }
                        }
                        $average['wave_'.$i] = $sum / (($divider == 0) ? 1 : $divider);
                    }
                    $ret = $this->sortAverage($average);
                    $first_score = floatval(number_format($ret['first_score'], 2, '.', ''));
                    $second_score = floatval(number_format($ret['second_score'], 2, '.', ''));

                    $round_heat->update([
                        'first_score' => $first_score,
                        'second_score' => $second_score,
                        'points' => $first_score + $second_score,
                    ]);
                    $points["$round_heat->id"] = $round_heat->points;
                }
                arsort($points);
                $index = 1;
                foreach ($points as $key => $point) {
                    $round_heat = Round_heat::find($key);
                    $round_heat->update([
                        'position' => $index,
                    ]);
                    $index++;
                }
            }
            $heat_scores = [];
            $judge_role = Role::where('name', 'Judge')->first();
            foreach ($round_heats as $round_heat) {
                $round_heat->com_cat_mod_participant->participant;
                $round_heat->com_cat_mod_participant->competition;
                $round_heat->com_cat_mod_participant->category->sex;
                $round_heat->com_cat_mod_participant->modality;
                $round_heat->lycra;

                $temps = Heat_score::where('round_heat_id', $round_heat->id)->get();
                if (count($temps) == 0) {
                    foreach ($judge_role->users as $judge) {
                        $heat_score = new Heat_score;
                        $heat_score->round_heat_id = $round_heat->id;
                        $heat_score->judge_id = $judge->id;
                        $heat_score->save();
                    }
                }
                $average =  [
                    'round_heat_id' => 0,
                    'judge_id' => 'Average',
                    'wave_1' => 0,
                    'wave_2' => 0,
                    'wave_3' => 0,
                    'wave_4' => 0,
                    'wave_5' => 0,
                    'wave_6' => 0,
                    'wave_7' => 0,
                    'wave_8' => 0,
                    'wave_9' => 0,
                    'wave_10' => 0,
                ];
                $heat_scores_temp = [];
                $temps = Heat_score::where('round_heat_id', $round_heat->id)->orderBy('judge_id')->get();
                foreach ($temps as $temp) {
                    $temp->round_heat->com_cat_mod_participant->participant;
                    $temp->round_heat->com_cat_mod_participant->competition;
                    $temp->round_heat->com_cat_mod_participant->category->sex;
                    $temp->round_heat->com_cat_mod_participant->modality;
                    $temp->round_heat->lycra;
                    $temp->judge;

                    $average['round_heat_id'] = $temp->round_heat_id;
                    array_push($heat_scores_temp, $temp);
                }
                array_push($heat_scores_temp, $average);
                array_push($heat_scores, $heat_scores_temp);
            }

            return response()->json([
                'message' => 'success',
                'round_heats' => $round_heats,

                'heat_scores' => $heat_scores,
            ], 200);
        } else {
            return response()->json([
                'message' => 'success',
                'round_heats' => [],
                'heat_scores' => [],
            ], 200);
        }

    }

    public function getCompetitionHeats(Request $request)
    {
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2])->first();
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $category->id)
                                                            ->where('modality_id', $modality->id)->get();

        $all_round_heats = [];
        for ($i=1; ;$i++) {
            $array_rounds = [];
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->get();
            if (count($round_heats) > 0) {
                for ($j=1; ;$j++) {
                    $array_heats = [];
                    $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->where('heat', $j)->get();
                    if (count($round_heats) > 0) {
                        foreach ($round_heats as $round_heat) {
                            $round_heat->com_cat_mod_participant->participant;
                            $round_heat->com_cat_mod_participant->competition;
                            $round_heat->com_cat_mod_participant->category->sex;
                            $round_heat->com_cat_mod_participant->modality;
                            $round_heat->lycra;
                            array_push($array_heats, $round_heat);
                        }
                    } else {
                        break;
                    }
                    array_push($array_rounds, $array_heats);
                }
            } else {
                break;
            }
            array_push($all_round_heats, $array_rounds);
        }

        return response()->json([
            'message' => 'success',
            'all_round_heats' => $all_round_heats,
        ], 200);
    }

    public function initCompetitionHeats(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();

        $all_round_heats = [];
        for ($i=1; ;$i++) {
            $array_rounds = [];
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->get();
            if (count($round_heats) > 0) {
                for ($j=1; ;$j++) {
                    $array_heats = [];
                    $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->where('heat', $j)->orderBy('id')->get();
                    if (count($round_heats) > 0) {
                        foreach ($round_heats as $round_heat) {
                            $round_heat->com_cat_mod_participant->participant;
                            $round_heat->com_cat_mod_participant->competition;
                            $round_heat->com_cat_mod_participant->category->sex;
                            $round_heat->com_cat_mod_participant->modality;
                            $round_heat->lycra;
                            array_push($array_heats, $round_heat);
                        }
                    } else {
                        break;
                    }
                    array_push($array_rounds, $array_heats);
                }
            } else {
                break;
            }
            array_push($all_round_heats, $array_rounds);
        }

        return response()->json([
            'message' => 'success',
            'all_round_heats' => $all_round_heats,
        ], 200);
    }

    public function getCompetitionFinalResults(Request $request)
    {
        $manage_ranking_points = Manage_ranking_point::where('competition_id', $request->competitionId)
                                                    ->where('category_id', $request->categoryId)
                                                    ->where('modality_id', $request->modalityId)
                                                    ->orderBy('ranking')
                                                    ->get();
        if (count($manage_ranking_points) > 0) {
            foreach ($manage_ranking_points as $manage_ranking_point) {
                $manage_ranking_point->participant;
            }
            $manage_ranking_points[0]->competition;
            $manage_ranking_points[0]->category->sex;
            $manage_ranking_points[0]->modality;

            return response()->json([
                'message' => 'success',
                'final_results' => $manage_ranking_points,
            ], 200);
        } else {
            $com_cat_mod_participants = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
            foreach ($com_cat_mod_participants as $index => $com_cat_mod_participant) {
                $com_cat_mod_participant->competition;
                $com_cat_mod_participant->category->sex;
                $com_cat_mod_participant->modality;
                $com_cat_mod_participant->participant;
                $com_cat_mod_participant->ranking = $index + 1;
                $com_cat_mod_participant->ranking_points = 0;
            }

            return response()->json([
                'message' => 'success',
                'final_results' => $com_cat_mod_participants,
            ], 200);
        }
    }

    public function setProgressStatus(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $active_round_heats = Round_heat::where('status', 3)->get();
        $affected = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                            ->where('round', $request->round)
                            ->where('heat', $request->heat)->get();

        if (count($active_round_heats) != 0) {
            if ($affected[0]->status == 3) {
                return response()->json([
                    'message' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'failed',
                ], 200);
            }
        } else {
            foreach ($affected as $temp) {
                $temp->update(['status' => 3]);
            }
            return response()->json([
                'message' => 'success',
            ], 200);
        }
    }

    public function initHeatDetails(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                ->where('round', $request->round)
                                ->where('heat', $request->heat)->get();
        $isNew = true;
        foreach ($round_heats as $round_heat) {
            if ($round_heat->points != 0) {
                $isNew = false;
            }
        }
        if ($isNew) {
            $points = [];
            $averagevals = [];
            foreach ($round_heats as $round_heat) {
                $average =  [
                    'wave_1' => 0,
                    'wave_2' => 0,
                    'wave_3' => 0,
                    'wave_4' => 0,
                    'wave_5' => 0,
                    'wave_6' => 0,
                    'wave_7' => 0,
                    'wave_8' => 0,
                    'wave_9' => 0,
                    'wave_10' => 0,
                ];
                $heat_scores = Heat_score::where('round_heat_id', $round_heat->id)->get();
                for ($i = 1; $i < 11; $i++) {
                    $sum = 0;
                    $divider = 0;
                    foreach ($heat_scores as $heat_score) {
                        if ($heat_score['wave_'.$i] > 0) {
                            $sum += $heat_score['wave_'.$i]/1;
                            $divider++;
                        }
                    }
                    $average['wave_'.$i] = $sum / (($divider == 0) ? 1 : $divider);
                }
                array_push($averagevals, $average);
                $ret = $this->sortAverage($average);
                $first_score = floatval(number_format($ret['first_score'], 2, '.', ''));
                $second_score = floatval(number_format($ret['second_score'], 2, '.', ''));

                $round_heat->update([
                    'first_score' => $first_score,
                    'second_score' => $second_score,
                    'points' => $first_score + $second_score,
                ]);
                $points["$round_heat->id"] = $round_heat->points;
            }
            arsort($points);
            $index = 1;
            foreach ($points as $key => $point) {
                $round_heat = Round_heat::find($key);
                $round_heat->update([
                    'position' => $index,
                ]);
                $index++;
            }
        }
        $heat_scores = [];
        $judge_role = Role::where('name', 'Judge')->first();
        foreach ($round_heats as $round_heat) {
            $round_heat->com_cat_mod_participant->participant;
            $round_heat->com_cat_mod_participant->competition;
            $round_heat->com_cat_mod_participant->category->sex;
            $round_heat->com_cat_mod_participant->modality;
            $round_heat->lycra;

            $temps = Heat_score::where('round_heat_id', $round_heat->id)->get();
            if (count($temps) == 0) {
                foreach ($judge_role->users as $judge) {
                    $heat_score = new Heat_score;
                    $heat_score->round_heat_id = $round_heat->id;
                    $heat_score->judge_id = $judge->id;
                    $heat_score->save();
                }
            }
            $average =  [
                'round_heat_id' => 0,
                'judge_id' => 'Average',
                'wave_1' => 0,
                'wave_2' => 0,
                'wave_3' => 0,
                'wave_4' => 0,
                'wave_5' => 0,
                'wave_6' => 0,
                'wave_7' => 0,
                'wave_8' => 0,
                'wave_9' => 0,
                'wave_10' => 0,
            ];
            $heat_scores_temp = [];
            $temps = Heat_score::where('round_heat_id', $round_heat->id)->orderBy('judge_id')->get();
            foreach ($temps as $temp) {
                $temp->round_heat->com_cat_mod_participant->participant;
                $temp->round_heat->com_cat_mod_participant->competition;
                $temp->round_heat->com_cat_mod_participant->category->sex;
                $temp->round_heat->com_cat_mod_participant->modality;
                $temp->round_heat->lycra;
                $temp->judge;

                $average['round_heat_id'] = $temp->round_heat_id;
                array_push($heat_scores_temp, $temp);
            }
            array_push($heat_scores_temp, $average);
            array_push($heat_scores, $heat_scores_temp);
        }
        return response()->json([
            'message' => 'success',
            'round_heats' => $round_heats,
//            'third_score' => $third_score,
            'heat_scores' => $heat_scores,
        ], 200);
    }

    public function sortAverage($average)
    {
        $scores = [];
        for ($i=1; $i<=10; $i++) {
            array_push($scores, $average['wave_'.$i]);
        }
        rsort($scores);
        return [
            'first_score' => $scores[0],
            'second_score' => $scores[1],
            'third_score' => $scores[2],

        ];
    }

    public function storeFinalHeatResults(Request $request)
    {
        // Store scores in Heat_score table
        foreach ($request->heat_scores as $heat_scores) {
            $round_heat = Round_heat::find($heat_scores[0]['round_heat_id']);
            if ($request->status == "close") {
                $round_heat->update([
                    'status' => 1,
                ]);
            }

            $average =  [
                'wave_1' => 0,
                'wave_2' => 0,
                'wave_3' => 0,
                'wave_4' => 0,
                'wave_5' => 0,
                'wave_6' => 0,
                'wave_7' => 0,
                'wave_8' => 0,
                'wave_9' => 0,
                'wave_10' => 0,
            ];
            foreach ($heat_scores as $heat_score) {
                if ($heat_score['judge_id'] != "Average") {
                    $temp = Heat_score::find($heat_score['id']);
                    $temp->update([
                        'wave_1' => $heat_score['wave_1'],
                        'wave_2' => $heat_score['wave_2'],
                        'wave_3' => $heat_score['wave_3'],
                        'wave_4' => $heat_score['wave_4'],
                        'wave_5' => $heat_score['wave_5'],
                        'wave_6' => $heat_score['wave_6'],
                        'wave_7' => $heat_score['wave_7'],
                        'wave_8' => $heat_score['wave_8'],
                        'wave_9' => $heat_score['wave_9'],
                        'wave_10' => $heat_score['wave_10'],
                        'penal' => $heat_score['penal'],
                    ]);
                } else {
                    for ($i = 1; $i <= 10; $i++) {
                        $average['wave_'.$i] = $heat_score['wave_'.$i];
                    }
                    $ret = $this->sortAverage($average);
                    $first_score = floatval(number_format($ret['first_score'], 2, '.', ''));
                    $second_score = floatval(number_format($ret['second_score'], 2, '.', ''));
                    $round_heat->update([
                        'first_score' => $first_score,
                        'second_score' => $second_score,
                    ]);
                }
            }
        }
        // assign position to every participant
        $points = [];
        foreach ($request->round_heats as $temp) {
            $round_heat = Round_heat::find($temp["id"]);
            $first_score = $round_heat->first_score;
            $second_score = $round_heat->second_score;
            if ($temp["penal"] > 2) {
                $temp["penal"] = 2;
            }
            if ($temp["draw"] > 2) {
                $temp["draw"] = 2;
            }
            if ($temp["penal"] == 1) {
                $round_heat->update([
                    'penal' => $temp["penal"],
                    'draw' => $temp["draw"],
                    'points' => $first_score/2 + $second_score + $temp["draw"]/100,
                ]);
            } else if ($temp["penal"] == 2) {
                $round_heat->update([
                    'penal' => $temp["penal"],
                    'draw' => $temp["draw"],
                    'first_score' => 0,
                    'second_score' => 0,
                    'points' => 0,
                ]);
            } else {
                $round_heat->update([
                    'penal' => $temp["penal"],
                    'draw' => $temp["draw"],
                    'points' => $first_score + $second_score + $temp["draw"]/100,
                ]);
            }
            if ($temp["penal"] == 2) {
                $round_heat->update([
                    'position' => count($request->round_heats),
                ]);
            } else {
                $points["$round_heat->id"] = $round_heat->points;
            }
        }
        arsort($points);
        $index = 1;
        foreach ($points as $key => $point) {
            $round_heat = Round_heat::find($key);
            $round_heat->update([
                'position' => $index,
            ]);
            $index++;
        }
        // Create New Round and Manage_ranking
        $round_heat = Round_heat::find($request->heat_scores[0][0]['round_heat_id']);
        $current_round = $round_heat->round;
        $round_heat->com_cat_mod_participant;
        $current_competition = $round_heat->com_cat_mod_participant->competition_id;
        $current_category = $round_heat->com_cat_mod_participant->category_id;
        $current_modality = $round_heat->com_cat_mod_participant->modality_id;
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $current_competition)
                                                            ->where('category_id', $current_category)
                                                            ->where('modality_id', $current_modality)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                ->where('round', $current_round)->get();
        $next_round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                    ->where('round', $current_round+1)->get();
        $isCreatingNew = true;
        $new_round_heats = [];
        $old_round_heats = [];
        $round_heats_number = count($round_heats);
        $ranking_score = Competition::find($current_competition)->ranking_score;
        // Manage Ranking in Final Heat
        if (($round_heats_number < 6) && ($ranking_score == 'Si')) {
            $old_round_heats = $round_heats;
            $manage_ranking_points = Manage_ranking_point::where('competition_id', $current_competition)
                                                        ->where('category_id', $current_category)
                                                        ->where('modality_id', $current_modality)
                                                        ->where('participant_id', $old_round_heats[0]->com_cat_mod_participant->participant_id)
                                                        ->get();
            if (count($manage_ranking_points) == 0) {
                $ranking_id = (Competition::find($current_competition)->competition_type_id == 1) ? 1 : 2;
                foreach ($old_round_heats as $old_round_heat) {
                    $manage_ranking_point = new Manage_ranking_point;
                    $manage_ranking_point->competition_id = $current_competition;
                    $manage_ranking_point->category_id = $current_category;
                    $manage_ranking_point->modality_id = $current_modality;
                    $manage_ranking_point->participant_id = $old_round_heat->com_cat_mod_participant->participant_id;
                    $manage_ranking_point->ranking = $old_round_heat->position;
                    $ranking_position_point = Ranking_position_point::where('position', $old_round_heat->position)
                                                                    ->where('ranking_id', $ranking_id)->first();
                    $manage_ranking_point->ranking_points = $ranking_position_point->points;
                    $manage_ranking_point->save();
                }
            }
        }
        foreach ($round_heats as $round_heat) {
            if (($round_heat->status != 1) || ($round_heats_number < 6)) {
                $isCreatingNew = false;
            }
            if (($round_heat->position == 1) || ($round_heat->position == 2)) {
                array_push($new_round_heats, $round_heat);
            } else {
                if ($round_heats_number >= 6) {
                    array_push($old_round_heats, $round_heat);
                }
            }
        }
        if ($isCreatingNew && (count($new_round_heats) > 0) && (count($next_round_heats) == 0)) {
            // Manage Ranking
            if ($ranking_score == 'Si') {
                $old_points = [];
                $penal_number = 0;
                $ranking_id = (Competition::find($current_competition)->competition_type_id == 1) ? 1 : 2;
                foreach ($old_round_heats as $old_round_heat) {
                    $manage_ranking_point = new Manage_ranking_point;
                    $manage_ranking_point->competition_id = $current_competition;
                    $manage_ranking_point->category_id = $current_category;
                    $manage_ranking_point->modality_id = $current_modality;
                    $manage_ranking_point->participant_id = $old_round_heat->com_cat_mod_participant->participant_id;
                    $manage_ranking_point->save();

                    if ($old_round_heat->penal ==2) {
                        $ranking_position_point = Ranking_position_point::where('position', $round_heats_number-$penal_number)
                                                                        ->where('ranking_id', $ranking_id)->first();
                        $manage_ranking_point->update([
                            'ranking' => $round_heats_number-$penal_number,
                            'ranking_points' => $ranking_position_point->points,
                        ]);
                        $penal_number++;
                    } else {
                        $old_points["$manage_ranking_point->id"] = $old_round_heat->points;
                    }
                }
                arsort($old_points);
                $index = 1;
                foreach ($old_points as $key => $old_point) {
                    $manage_ranking_point = Manage_ranking_point::find($key);
                    $ranking_position_point = Ranking_position_point::where('position', $index + count($new_round_heats))
                                                                    ->where('ranking_id', $ranking_id)->first();
                    $manage_ranking_point->update([
                        'ranking' => $index + count($new_round_heats),
                        'ranking_points' => $ranking_position_point->points,
                    ]);
                    $index++;
                }
            }
            // Create New Round_heat
            $lycras = Competition::find($current_competition)->lycras;
            $heat_configuration = Heat_configuration::where('participant_number', count($new_round_heats))->first();
            $heat_number = count($heat_configuration->assign_array);
            switch (count($new_round_heats)) {
                case 6:
                    foreach ($heat_configuration->assign_array as $index => $heat_items) {
                        for ($i = 1; $i <= $heat_items; $i++) {
                            $round_heat = new Round_heat;
                            $round_heat->round = $current_round + 1;
                            $round_heat->heat = $index + 1;
                            if ($index == 0) {
                                if ($this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 1) != null) {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 1)
                                                                                ->com_cat_mod_participant_id;
                                } else {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, 2, 2)
                                                                                ->com_cat_mod_participant_id;
                                }
                            } else {
                                if ($this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 2) != null) {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 2)
                                                                                ->com_cat_mod_participant_id;
                                } else {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, 2, 1)
                                                                                ->com_cat_mod_participant_id;
                                }
                            }

                            $round_heat->lycra_id = $lycras[$i-1];
                            $round_heat->save();
                        }
                    }
                    break;
                case 12:
                    $prev_heat = 1;
                    $lycra_id = 1;
                    for ($i = 1; $i <= count($new_round_heats); $i++) {
                        $position = ($i % 2 == 0) ? 2 : 1;
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $prev_heat, $position)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycras[$lycra_id-1];
                        $round_heat->save();
                        if ($i % 2 == 0) {
                            $prev_heat++;
                        }
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    break;
                case 20:
                    $lycra_id = 1;
                    $prev_heat_number = count($new_round_heats)/2;
                    $position = 1;
                    $prev_heat = 1;
                    for ($i = 1; $i <= count($new_round_heats); $i++) {
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $prev_heat, $position)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycras[$lycra_id-1];
                        $round_heat->save();
                        $prev_heat++;
                        if ($i == $prev_heat_number) {
                            $prev_heat = 1;
                            $position++;
                        }
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    break;
                default:
                    $lycra_id = 1;
                    for ($i = 1; $i <= count($new_round_heats)/2; $i++) {
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $i, 1)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycras[$lycra_id-1];
                        $round_heat->save();
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    $lycra_id = 2;
                    for ($i = count($new_round_heats)/2; $i >= 1; $i--) {
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $i, 2)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycras[$lycra_id-1];
                        $round_heat->save();
                    }
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $points,
        ], 200);
    }

    public function getFirstSecondParticipant($round_heats, $prev_heat, $position)
    {
        foreach ($round_heats as $round_heat) {
            if (($round_heat->heat == $prev_heat) && ($round_heat->position == $position)) {
                return $round_heat;
            }
        }
        return null;
    }
}
