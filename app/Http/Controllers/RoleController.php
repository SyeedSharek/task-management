<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Traits\AppResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use AppResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        
            $roles = Role::with('permissions')->latest()->get();
            return response()->json(['message' => 'All Roles', 'roles' => $roles ]);
        
    }

    public function allPermission(){
        if(Auth::check()){            
            $permissions = Permission::all();
            return response()->json(['message' => 'All Permissions', 'permissions' => $permissions ]);
        }else{
            return $this->errorResponse('Unauthorized', 401);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        if(Auth::check()){
            $validation= Validator::make($request->all(),[
                'name'=>'required|unique:roles','name',   
                'permissions' => 'required|array',             

            ]);                 
           if($validation->fails()){
            return $this->errorResponse('Validation Fail',400,$validation->errors()->toArray());
           }

            $role = Role::create(['name'=>$request->name,'guard_name'=>'api']);
            // $permission = Permission::create(['name' => $request->name]);
           
            $role->syncPermissions($request->permissions);
         

            return $this->successResponse($role, 'Role created successfully', 201);
        }
        else{
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(Auth::check()){            
            $role = Role::with('permissions')->findOrFail($id);
            return response()->json(['message' => 'Role Details', 'role' => $role ]);
        }else{
            return $this->errorResponse('Unauthorized', 401);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if(Auth::check()){
            
            $validation= Validator::make($request->all(),[
             
                
                'name' => 'required', Rule::unique('roles')->ignore($role->id),
                'permissions' => 'required|array',                             

            ]);  
              
            if ($validation->fails()) {           
                return $this->errorResponse('Validation failed', 400, $validation->errors()->toArray());
             }
               
            $role->update(['name'=>$request->name,'guard_name'=>'api']);           
    
            // if ($request->has('permissions')) {
            //     $role->syncPermissions($request->permissions);
            // }
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
    
                // Sync permissions by name
                $role->syncPermissions($permissions);
            }

            return $this->successResponse($role, 'Role Update successfully', 201);
        }
        else{
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        if(Auth::check()){
            $role = Role::find($id);
       
            if (!$role) {
                return $this->errorResponse('Role not found', 404);
            }
            $role->delete();
            return $this->successResponse('Role deleted successfully', 204);  // No Content status code 204  for delete operation.

        }
        else{
            return $this->errorResponse('Unauthorized', 401);
        }
    }
}
