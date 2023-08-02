<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::post('/userRegister',[UserController::class, 'UserRegistration']);

Route::controller(UserController::class)->group(function (){
   //Api Routes
   Route::post('/userRegister','UserRegistration');
   Route::post('/userLogin','UserLogin');
   Route::get('/Logout','UserLogout');
   Route::post('/SendOTPToEmail','SendOTPToEmail');
   Route::post('/VerifyOTP','VerifyOTP');
   Route::post('/ResetPassword','ResetPassword')->middleware([TokenVerificationMiddleware::class]);
   Route::get('/UserProfile','UserProfile')->middleware([TokenVerificationMiddleware::class]);
   Route::post('/UpdateProfile','UpdateProfile')->middleware([TokenVerificationMiddleware::class]);

   //Page Routes
    Route::get('/register', 'RegistrationPage')->name('register');
    Route::get('/login', 'LoginPage')->name('login');
});

Route::controller(DashboardController::class)->group(function (){
    Route::get('/dashboard', 'Dashboard')->middleware([TokenVerificationMiddleware::class]);
});

Route::controller(CustomerController::class)->group(function (){
    //API Routes - Customer
    Route::post("/create-customer",'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
    Route::get("/list-customer",'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
    Route::post("/delete-customer",'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
    Route::post("/update-customer",'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);
    Route::post("/customer-by-id",'CustomerByID'])->middleware([TokenVerificationMiddleware::class]);
});
