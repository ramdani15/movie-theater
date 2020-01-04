<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Halls;
use App\Movies;
use App\Shows;


class ShowController extends Controller
{
    public function index(Request $request){
    	return Shows::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->addShows($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'date' => 'required|date',
	            'start_time' => 'required|date_format:H:i',
        		'end_time' => 'required|date_format:H:i|after:start_time',
	            'movie_id' => 'required|string',
	            'hall_id' => 'required|string',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $show = Shows::create([
	        	"date" => $request->date,
	        	"start_time" => $request->start_time,
	        	"end_time" => $request->end_time,
	        	'movie_id' => $request->movie_id,
	        	"hall_id" => $request->hall_id,
	        	"created_by" => $request->user()->username,
	        ]);

	        return $show;
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Shows::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'date' => 'required|date',
	            'start_time' => 'required|date_format:H:i',
        		'end_time' => 'required|date_format:H:i|after:start_time',
        		'movie_id' => 'required|string',
	            'hall_id' => 'required|string',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        Shows::where('_id', $id)->update([
	        	"date" => $request->date,
	        	"start_time" => $request->start_time,
	        	"end_time" => $request->end_time,
	        	'movie_id' => $request->movie_id,
	        	"hall_id" => $request->hall_id,
	        ]);

	        return Shows::find($id);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
	        Shows::destroy($id);

	        return response()->json(["status" => 'Deleted'], 200);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }
}
