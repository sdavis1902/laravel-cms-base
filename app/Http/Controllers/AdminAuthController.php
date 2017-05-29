<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Sentinel;
use Validator;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Reminder;
use Mail;

class AdminAuthController extends Controller {

    public function postLogin(Request $request){
        $v = Validator::make($request->all(), [
            'email'                => 'required',
            'password'             => 'required',
        ]);

        if ($v->fails()) {
            return redirect('admin/auth/login')->with('message', 'Login error.  Please make sure that your account login information is correct.');
        }

        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ];

		if( !User::get()->count() ){// no users, make this the first user
			$user = Sentinel::registerAndActivate($credentials);
		}

        try{
            $user = Sentinel::authenticate($credentials);
        }catch(\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e){
            return redirect('admin/auth/login')->with('message', 'Locked out.  There have been to many login errors. Please try again in 1 hour');
        }

        if( !$user ){
            return redirect('admin/auth/login')->with('message', 'Login error.  Please make sure that your account login information is correct and type in a valid captcyha code.');
        }

        return redirect()->intended('admin/site');
    }

	public function getLogin(){
		$new = User::get()->count() ? 0:1;
        return view('admin.auth.login', ['new' => $new]);
    }

    public function getLogout(){
        Sentinel::logout(null, true);
        return redirect('admin/auth/login');
    }

    public function postForgotPassword(Request $request){
        $message = 'If the email address you provided was found in our system, then you should recieve a password reset email shortly.';

        if( !$request->input('email') ) return redirect( 'admin/auth/login' )->with('message', $message);
        $user = User::where('email', '=', $request->input('email'))->first();
        if( !$user ) return redirect( 'admin/auth/login' )->with('message', $message);

        // wahoo, we have a valid user
        $reminder = Reminder::exists($user);
        if( !$reminder ) $reminder = Reminder::create($user);

        $data = [
            'userid' => $user->id,
            'code'   => $reminder->code,
            'name'   => $user->first_name.' '.$user->last_name
        ];
        Mail::send('emails.auth.password_reminder', $data, function($m) use ($user){
            $m->subject('Password Reset');
            $m->from(env('ADMIN_EMAIL'), env('ADMIN_EMAIL_NAME'));
            $m->to($user->email, $user->first_name.' '.$user->last_name);
        });

        return redirect('admin/auth/login')->with('message', $message);
    }

    public function getForgotPassword(){
        return view('admin.auth.forgot_password');
    }

    public function postForgotPasswordConfirmation(Request $request, $userid, $code){
        if( !$request->has('userid') || !$request->has('code') ) return redirect('auth/login')->with('message', 'Your password could not be reset');

        $v = Validator::make($request->all(), [
            'new_password'              => 'required|confirmed|between:8,24',
            'new_password_confirmation' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('admin/auth/forgot-password-confirmation/'.$request->input('userid').'/'.$request->input('code'))->withErrors($v->errors());
        }

        $user = Sentinel::findById($request->input('userid'));
        $reminder = Reminder::complete($user, $request->input('code'), $request->input('new_password'));

        $message = 'Your password has successfully been reset.';
        if( !$reminder ){
            $message = 'Your password could not be reset';
        }

        return redirect('admin/auth/login')->with('message', $message);
    }

    public function getForgotPasswordConfirmation( $user_id, $code ){
        $user = User::find($user_id);
        if( !$user ) return redirect('admin/auth/login')->with('message', 'Could not find user');
        $reminder = Reminder::exists( $user );
        if( !$reminder || $reminder->code != $code ) return redirect('admin/auth/login')->with('message', 'Invalid Reminder URL');

        return view('admin.auth.forgot_password_confirmation', [
            'code'  => $code,
            'userid'=> $user->id
        ]);
    }
}
