<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class PermissionController extends Controller
{
    public function isSuper($role) {
        if($role == 'super'){
            return true;
        }
        return false;
    }

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
    	if(Self::isSuper($role) || Self::isAdmin($role) || Self::isStaff($role)){
    		return true;
    	}
    	return false;
    }

    public function usersPermission($username, $user_id){
        $user = User::find($user_id);
        $owner = User::where('username', $username)->first();
        if($user != null || $owner != null){
            if(Self::isSuper($owner->role)){
                if($user->_id != $owner->_id){
                    return true;
                }
            } else if(Self::isCustomer($user->role)){
                if(Self::addShows($owner->role) || $user->_id == $owner->_id){
                    return true;
                }
            } else if(Self::isStaff($user->role)){
                if(Self::isAdmin($owner->role) || $user->_id == $owner->_id){
                    return true;
                }
            } else if(Self::isAdmin($user->role)){
                if($user->_id == $owner->_id){
                    return true;
                }
            }
            return false;
        }
        return abort(404);

    }
}
