<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Movies;

class MovieController extends Controller
{
    public function index(Request $request){
    	return Movies::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->isSuper($request->user()->role) ||
    		app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'title' => 'required|string|max:255',
	            'genre' => 'required|string|max:255',
	            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')),
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $movie = Movies::create([
	        	"title" => $request->title,
	        	"genre" => $request->genre,
	        	"year" => $request->year,
	        ]);

	        return $movie;
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Movies::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->isSuper($request->user()->role) ||
    		app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'title' => 'required|string|max:255',
	            'genre' => 'required|string|max:255',
	            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')),
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        Movies::where('_id', $id)->update([
	        	"title" => $request->title,
	        	"genre" => $request->genre,
	        	"year" => $request->year,
	        ]);

	        return Movies::find($id);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->isSuper($request->user()->role) ||
    		app(PermissionController::class)->isAdmin($request->user()->role)){
	        Movies::destroy($id);

	        return response()->json(["status" => 'Deleted'], 200);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }
}
