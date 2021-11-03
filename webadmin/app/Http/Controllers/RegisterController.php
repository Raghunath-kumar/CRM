<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;

class RegisterController extends Controller
{
    public function index(){
        return view('register');
    }

    public function userentry(Request $request)
    {    
        $request->validate(
        [
            'name'=> 'required',
            'email'=> 'required|email',
            'mobile' => 'required||digits:10'
        ]

        );

        echo"<pre>";
        print_r($request->all());

        $user = new user;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->mobile = $request['mobile'];

        $newstring = substr($request['mobile'], -4);
        
        $user->userid = 'esh'.mt_rand(10,100).$newstring;
        $user->save();
        
        // $lastDigit = $number % 10000;
           // 'esh'.mt_rand(10,100).

       // return view('register');
    }
}
