<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{   
    public function index(){
        if(Auth::check()){ 
            return true;
        }else { 
            return back();
        }
    }

    public function Login(Request $request)
    {  
        $login =  $request->post(); 
        $email =  $login['email'];
        $password  = $login['password']; 

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            Session::flash('successLoign'  , '{ "correct" : "đăng nhập thành công!" }'); 
            return Response()->json([
                        json_decode(Session::get('successLoign')),
                        Auth::user()
                    ]); 
        } else { 
            Session::flash('failureLoign'  , '{ "error" : "tài khoản hoặc mật khẩu sai!" }'); 
            return json_decode(Session::get('failureLoign'));
        }       
    } 

    public function Logout()
    {
        Auth::logout();  
        return back();
    }
    
}
