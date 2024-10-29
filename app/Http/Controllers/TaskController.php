<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\AppResponse;
use Illuminate\Auth\Events\Validated;

class TaskController extends Controller
{
    use AppResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tasks = Task::all();   
        if(auth()->check()){
             $tasks = Task::with('user')->get();    
              
        return response()->json(['message' => 'All Tasks', 'tasks' => $tasks]);
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
        if(auth()->check()){
             $validation = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'required|max:1000',
            'status'=>'nullable',
            'due_date'=>'required|date_format:Y-m-d',
            'user_id'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 

         ]);

        
         if ($validation->fails()) {           
            return $this->errorResponse('Validation failed', 400, $validation->errors()->toArray());
         }
        
           else{
            $validatedData = $validation->validated();

               $validatedData['status'] = $validatedData['status'] ?? 'Pending';
            
             if($request->hasFile('image')){
                $imageName = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validatedData['image'] = $imageName;
            }else {
                $validatedData['image'] = null; 
            }
            
            $task = Task::create($validatedData);
            return $this->successResponse($task, 'Task Created Successfully', 201);
          }

         }
         else{
            return $this->errorResponse('Unauthorized', 401);
         }
        
    }

  
    public function show(string $id)
    {
        if(auth()->check()){

             $task = Task::findOrFail($id);
             $users = User::all();
             return response()->json(['message'=>'Task Details','task'=>$task,'users'=>$users],200);
        }
       
        else{
            return $this->errorResponse('Unauthorized', 401);
        }
       
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        if(Auth()->check()){
            $task = Task::find($id);
    
            if(!$task){
                return $this->errorResponse('Task ID is not found', 404);
            } 
    
            $validation = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|max:1000',
                'status' => 'nullable',
                'due_date' => 'required|date_format:Y-m-d',
                'user_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
   
            if ($validation->fails()) {
                return $this->errorResponse('Validation failed', 400, $validation->errors()->toArray());
            }
    
            $validatedData = $validation->validated();
                
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();                
                $request->image->move(public_path('images'), $imageName);
                $validatedData['image'] = $imageName;
    
               
                if ($task->image && file_exists(public_path('images/' . $task->image))) {
                    unlink(public_path('images/' . $task->image));
                }
            } else {             
                $validatedData['image'] = null;
            }
    

            $task->update($validatedData);

            return $this->successResponse($task, 'Task updated successfully', 200);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
    

    public function status(Request $request, $id){

        
        $validation = Validator::make($request->all(),[
             'status' =>'required|in:Completed,In Progress,Pending'
        ]);

        if ($validation->fails()) {           
            return $this->errorResponse('Validation failed', 400, $validation->errors()->toArray());
        }

        $task = Task::find($id);
    
        if(!$task){
            return $this->errorResponse('Task ID is not found', 404);
        }

        if($request->has('status')){
            $task->status = $request->status;
            $task->save();
            return $this->successResponse($task, 'Task status updated successfully',200);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::find($id);
       
        if (!$task) {
            return $this->errorResponse('Task not found', 404);
        }

        if($task->image && file_exists(public_path('images/'.$task->image))){
            unlink(public_path('images/'.$task->image)); 
        }

        $task->delete();

        return $this->successResponse( 'Task deleted successfully',200);
    }

    public function search(Request $request)
{
    if (auth()->check()) {
      
        $query = Task::with('user');

     
        $search = $request->query('search');
        

        if (!empty($search)) {
            $query->where('status', 'LIKE', '%' . $search . '%');
        }


        if (!empty($search)) {
            $query->orWhere('due_date', 'LIKE', '%' . $search . '%');
        }
        $tasks = $query->get();

     
        return response()->json(['task'=>$tasks],200);
       

       

    } else {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}



}
