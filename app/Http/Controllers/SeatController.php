<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Seats;

class SeatController extends Controller
{
    public function index(Request $request){
    	return Seats::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'code' => 'required|string|max:255',
	            'hall_id' => 'required',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $seat = Seats::create([
	        	"code" => $request->code,
	        	"hall_id" => $request->hall_id,
	        ]);

	        return $seat;
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Seats::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'code' => 'required|string|max:255',
	            'hall_id' => 'required',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        Seats::where('_id', $id)->update([
	        	"code" => $request->code,
	        	"hall_id" => $request->hall_id,
	        ]);

	        return Seats::find($id);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
	        Seats::destroy($id);

	        return response()->json(["status" => 'Deleted'], 200);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }
}
