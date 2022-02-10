<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends Controller
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
        $categories = Category::orderBy('year1', 'asc')->get();
        foreach ($categories as $category) {
            $category->sex;
        }
        return response()->json([
            'message' => 'success',
            'categories' => $categories
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        $category->sex;
        return response()->json([
            'message' => 'success',
            'category' => $category
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
            'description' => 'required|string|max:1000',
            'year1' => 'required|integer',
            'year2' => 'required|integer',
            'sex' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $sex = Sex::where('name', $request->sex)->first();
        $sex->categories()->create([
            'name' => $request->name,
            'description' => $request->description,
            'year1' => $request->year1,
            'year2' => $request->year2,
        ]);
        return response()->json([
            'message' => 'Category successfully registered',
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
        // Update Category
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'description' => 'required|string|max:1000',
            'year1' => 'required|integer',
            'year2' => 'required|integer',
            'sex' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $sex = Sex::where('name', $request->sex)->first();
        $category = Category::find($request->id);
        $category -> update([
            'name' => $request->name,
            'description' => $request->description,
            'year1' => $request->year1,
            'year2' => $request->year2,
            'sex_id' => $sex->id,
        ]);
        return response()->json([
            'message' => 'Category successfully updated',
            'category' => $category
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $categoryId)
    {
        //delete Category
        $category = Category::find($categoryId);
        $category -> delete();
        $categories = Category::orderBy('year1', 'asc')->get();
        foreach ($categories as $category) {
            $category->sex;
        }
        return response()->json([
            'message' => 'successfully deleted',
            'categories' => $categories
        ], 200);
    }

    public function getCategoryOptions()
    {
        $categoryOptions = collect([]);
        $categories = Category::all();
        foreach ($categories as $category) {
            $temp = $category->sex->name;
            $categoryOptions->push("$category->name, $temp");
        }
        return response()->json([
            'message' => 'success',
            'categories' => $categoryOptions,
        ], 200);
    }
}
