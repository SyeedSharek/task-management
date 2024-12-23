<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \App\Traits\AppResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    use AppResponse;
    /**
     * Display a listing of the resource.
     */




    public function RoleWisePermission(Request $request){
    
        if(Auth()->check()){
            $user = Auth::user();
            $roleWisePermission =$user->roles()
            ->with('permissions:id,name')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->values();
    
            return response()->json(['message' => 'Role Wise Permission', 'permissions' => $roleWisePermission ]);
        }else{
            return $this->errorResponse('Unauthorized', 401);
        }
    }
    public function index()    {
        
        if(Auth()->check()){
            $users = User::with('roles')->latest()->get(); 
       return response()->json(['message' => 'All Users', 'users' => $users]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete user');
        if(Auth()->check()){
            $user = User::find($id);
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            $user->delete();
            return $this->successResponse('User deleted successfully', 204);  // No Content status code 204  for delete operation.
        }
    }
}
