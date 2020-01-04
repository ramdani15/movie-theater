<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Shows;
use App\Seats;
use App\Tickets;

class TicketController extends Controller
{
    public function index(Request $request){
    	if(app(PermissionController::class)->addShows($request->user()->role)){
            $ticket = Tickets::all();
        } else {
            $ticket = Tickets::where('customer_id',$request->user()->_id)->get();
        }
    	return $ticket;
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->addShows($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'show_id' => 'required|string',
	            'seat_id' => 'required|string',
	            'customer_id' => 'required|string',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $show = Shows::find($request->show_id);
	        // check seat
	        $seat = Seats::where('_id', $request->seat_id)
	        			 ->where('hall_id', $show->hall_id)->get();
	        if($seat->isEmpty()){
	        	return response()->json(["status" => "Invalid Seat"], 400);
	        }

	        // exist ticket
	        $ticket = Tickets::where('show_id', $request->show_id)
	        				->where('seat_id', $request->seat_id)->get();
	        if(!$ticket->isEmpty()){
	        	return response()->json(["status" => "Ticket Already Exists"], 400);
	        }

	        $ticket = Tickets::create([
	        	"show_id" => $request->show_id,
	        	"seat_id" => $request->seat_id,
	        	"customer_id" => $request->customer_id,
	        	"created_by" => $request->user()->username,
	        ]);

	        return $ticket;
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	if(app(PermissionController::class)->addShows($request->user()->role)){
            $ticket = Tickets::find($id);
        } else {
            $ticket = Tickets::where('_id', $id)
            				 ->where('customer_id',$request->user()->_id)->get();
        }
    	return $ticket;
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->addShows($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'show_id' => 'required|string',
	            'seat_id' => 'required|string',
	            'customer_id' => 'required|string',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        $show = Shows::find($request->show_id);
	        // check seat
	        $seat = Seats::where('_id', $request->seat_id)
	        			 ->where('hall_id', $show->hall_id)->get();
	        if($seat->isEmpty()){
	        	return response()->json(["status" => "Invalid Seat"], 400);
	        }

	        // exist ticket
	        $ticket = Tickets::where('_id', '!=', $id)
	        				 ->where('show_id', $request->show_id)
	        				 ->where('seat_id', $request->seat_id)->get();
	        if(!$ticket->isEmpty()){
	        	return response()->json(["status" => "Ticket Already Exists"], 400);
	        }

	        Tickets::where('_id', $id)->update([
	        	"show_id" => $request->show_id,
	        	"seat_id" => $request->seat_id,
	        	"customer_id" => $request->customer_id,
	        ]);

	        return Tickets::find($id);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->isSuper($request->user()->role) ||
    		app(PermissionController::class)->isAdmin($request->user()->role)){
	        Tickets::destroy($id);

	        return response()->json(["status" => 'Deleted'], 200);
    	}
    	return response()->json(["status" => 'You don\'t have permission'], 403);
    }
}
