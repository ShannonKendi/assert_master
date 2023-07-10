<?php

use App\Http\Controllers\API\ProtestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;

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

Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);
Route::post('user/updateUser', [UserController::class, 'update_user']);

Route::get('users', [UserController::class, 'get_all_users']);
Route::get('users/{id}', [UserController::class, 'getSpecificUser']);

Route::post('protests', [ProtestController::class, 'post_protest']);
Route::get('protests', [ProtestController::class, 'get_all_protests']);
Route::delete('protests/{protest_id}', [ProtestController::class, 'delete_protest']);
Route::put('protests/{protest_id}', [ProtestController::class, 'edit_protest']);
Route::get('protests/{user_id}', [ProtestController::class, 'get_user_protests']);
Route::get('protest/{protest_id}', [ProtestController::class, 'get_specific_protest']);

Route::post('protests/emergency', [ProtestController::class, 'emergency']);

Route::get('roles', [RoleController::class, 'get_roles']);
Route::post('roles', [RoleController::class, 'add_role']);


Route::get('volunteers', [UserController::class, 'get_volunteers']);
Route::post('volunteer/register', [UserController::class, 'registerVolunteer']);
Route::post('volunteer/delete/{id}', [UserController::class, 'delete_volunteers']);
Route::post('volunteer/update', [UserController::class, 'update_volunteer']);
