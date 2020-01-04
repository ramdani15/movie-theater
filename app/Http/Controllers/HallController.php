<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Halls;
use App\Seats;

class HallController extends Controller
{
    public function index(Request $request){
    	return Halls::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'name' => 'required|string|max:255',
	            'seat_amount' => 'required|integer',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $hall = Halls::create([
	        	"name" => $request->name,
	        	"seat_amount" => $request->seat_amount,
	        ]);

	        return $hall;
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Halls::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'name' => 'required|string|max:255',
	            'seat_amount' => 'required|integer',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        Halls::where('_id', $id)->update([
	        	"name" => $request->name,
	        	"seat_amount" => $request->seat_amount,
	        ]);

	        return Halls::find($id);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->isAdmin($request->user()->role)){
	        Halls::destroy($id);

	        // delete seat
	        Seats::where('hall_id', $id)->delete();

	        return response()->json(["status" => 'Deleted'], 200);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }
}
