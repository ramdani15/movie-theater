<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function isAdmin($role){
    	if($role == 'admin'){
    		return true;
    	}
    	return false;
    }

    public function isStaff($role){
    	if($role == 'staff'){
    		return true;
    	}
    	return false;
    }

    public function isCustomer($role){
    	if($role == 'customer'){
    		return true;
    	}
    	return false;
    }

    public function addShows($role){
    	if(Self::isAdmin($role) || Self::isStaff($role)){
    		return true;
    	}
    	return false;
    }
}
