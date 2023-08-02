<?php

namespace App\Http\Controllers;

use App\Helper\JWToken;
use App\Mail\OTPEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    public function RegistrationPage():View{
        return view('auth.register');
    }

    public function LoginPage():View{
        return view('auth.login');
    }

    public function SendOTPPage():View{

    }

    public function VerifyOTPPage():view{

    }

    public function ResetPasswordPage():View{

    }

    public function ProfilePage():View{

    }



    //Api Links
    public function UserRegistration(Request $request){
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'userName' => $request->input('userName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
                //'userImage' => $request->input('userImage'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully..!'
            ],200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed..!'
            ],200);

        }
    }

    public function UserLogin(Request $request){
        $count=User::where('email','=',$request->input('email'))
            ->where('password','=',$request->input('password'))
            ->select('id')->first();

        if($count!==null){
            // User Login-> JWT Token Issue
            $token=JWToken::CreateToken($request->input('email'),$count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful..!',
            ],200)->cookie('token',$token,60*24*30);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);

        }
    }

    public function SendOTPToEmail(Request $request){
        $email=$request->input('email');
        $otp=rand(10000,99999);
        $count=User::where('email','=',$email)->count();

        if($count==1){

            Mail::to($email)->send(new OTPEmail($otp));

            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '5 Digit OTP Code has been send to your email !'
            ],200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }
    }

    public function VerifyOTP(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count==1){

            User::where('email','=',$email)->update(['otp'=>'0']);


            $token=JWToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful..!',
            ],200)->cookie('token',$token,60*24*30);

        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }

    public function ResetPassword(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful..!',
            ],200);

        }
        catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong..!',
            ],200);
        }
    }

    public function UserLogout(){
        return redirect('/userLogin')->cookie('token','',-1);
    }

    public function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful..!',
            'data' => $user
        ],200);
    }

    public function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            $userImage=$request->input('userImage');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password,
                'userImage'=>$userImage
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful..!',
            ],200);

        }
        catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong..!',
            ],200);
        }
    }
}
