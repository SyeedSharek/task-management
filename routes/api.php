<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[AuthController::class,'register']);
// Public or authenticated routes
   Route::group(['middleware' => 'api', 'prefix' => 'auth'], function() {  
  // Authentication routes
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/logout', [AuthController::class, 'logout']);
  
  // Role routes (with prefix 'role')
  Route::prefix('role')->group(function () {
      Route::get('/index', [RoleController::class, 'index']);
  });
});



Route::group([
  'middleware' => ['api', 'permission:user list|create user|edit user|delete user|role list|create role|edit role|delete role'],
  'prefix' => 'auth'
], function($router) {  

   

    // User Controller 
    Route::controller(UserController::class)->prefix('user')->group(function(){
      Route::get('/index','index');
      Route::delete('/delete/{id}','destroy');
      Route::get('/permissions', 'getUserPermissions');
      Route::get('/rolewisepermission','RoleWisePermission');



    });


    // Role Controller 
    Route::controller(RoleController::class)->prefix('role')->group(function(){
      // Route::get('/index','index');
      Route::get('/allPermission','allPermission');
      Route::post('/store','store');
      Route::post('/update/{role}','update');
      Route::delete('/delete/{id}','destroy');
      Route::get('/show/{id}','show');

    });

  
   
    // Task Controller     

      Route::controller(TaskController::class)->prefix('task')->group(function(){
 
      Route::get('/index','index');
      Route::post('/store','store');
      Route::post('/update/{id}','update');
      Route::delete('/delete/{id}','destroy');
      Route::get('/show/{id}','show');
      Route::put('/status/{id}','status');
      Route::get('/search','search');
    });

});
