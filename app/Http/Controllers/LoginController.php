<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function index(){

        return view('products.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
           'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
                return redirect()->route("product.index");
            }
            else{
                return redirect()->route('account.login')->with("Either email or message is incorrect");
            }

        }
        else{
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
    }

    public function register(){
        return view('products.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('account.login');


        }
        else{
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }


    }
}
