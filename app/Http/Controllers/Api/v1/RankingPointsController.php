<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Ranking;
use App\Models\Ranking_position_point;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class RankingPointsController extends Controller
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
    public function getRankingPointsById(Request $request, $rankingId)
    {
        $ranking_points = Ranking_position_point::where('ranking_id', $rankingId)->get();
        foreach ($ranking_points as $ranking_point) {
            $ranking_point->ranking;
        }
        return response()->json([
            'message' => 'success',
            'ranking_points' => $ranking_points
        ], 200);
    }

    public function getAllRankings()
    {
        $rankings = Ranking::all();
        return response()->json([
            'message' => 'success',
            'rankings' => $rankings
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRankingById(Request $request, $rankingId)
    {
        $ranking = Ranking::find($rankingId);
        return response()->json([
            'message' => 'success',
            'ranking' => $ranking,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRanking(Request $request)
    {
        // Update ranking_points
        $request->validate([
            'name' => 'required|string|between:1,100',
            'year' => 'required',
        ]);
        $ranking = Ranking::find($request->id);
        $ranking -> update([
            'name' => $request->name,
            'year' => $request->year,
        ]);
        return response()->json([
            'message' => 'ranking successfully updated',
            'ranking' => $ranking
        ], 201);
    }
}
