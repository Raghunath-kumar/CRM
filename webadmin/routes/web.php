<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BlankController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ManageuserController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\FollowupsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectprogressController;


Route::get('/dashboard',[DashboardController::class,'index']);
Route::get('/',[LoginController::class,'index']);
Route::get('/register',[RegisterController::class,'index']);
Route::post('/register',[RegisterController::class,'userentry']);








Route::get('/blankpage',[BlankController::class,'index']);
Route::get('/role',[RoleController::class,'rolefunction']);
Route::get('/manageuser',[ManageuserController::class,'managefunction']);
Route::get('/leads',[LeadsController::class,'leadfunction']);
Route::get('/followups',[FollowupsController::class,'followupsfunction']);
Route::get('/projects',[ProjectsController::class,'projectsfunction']);
Route::get('/projectprogressreport',[ProjectprogressController::class,'projectprogressfunction']);
