<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Role;

class RoleRepository
{

    public function getAllRoles()
    {
        $roles= Role::orderBy('description','asc')->select('id','description')->get();
        $role_response=[];
        foreach ($roles as $key=> $role)
        {

            $role_response[$key]['id']=$role->id;
            $role_response[$key]['description']=trans('user.'.$role->description);
        }
        return json_encode($role_response);
    }
}