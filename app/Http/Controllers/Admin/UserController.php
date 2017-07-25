<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Sentinel;
use Validator;
use Illuminate\Http\Request;
use Session;
use Reminder;
use Mail;
use Illuminate\Validation\Rule;
use Google2FA;

use App\Models\User;

class UserController extends Controller {
	public function getIndex(){
		$users = User::get();

		return view('admin.user.index', [
			'users' => $users
		]);
	}

	public function postEdit(Request $request, $id = 0){
		$rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
			'email' => [
                'required',
				'email',
                Rule::unique('users')->where(function($q) use ($id){
                    if($id) $q->where('id', '<>', $id);
                })
            ],
            'password' => (!$id ? 'required|' : '') . 'between:8,24|confirmed'
        ];

        $v = Validator::make($request->all(), $rules);

        if( $v->fails() ){
            return redirect('admin/user/'. ( $id ? "edit/$id" : 'create' ))->withErrors($v)->withInput();
        }

	    $credentials = [
			'email'    => $request->input('email'),
			'password' => $request->input('password')
		];

        if( $id ){
            $user = User::find($id);
            if( !$user ) return redirect('admin/user')->withMessage('Could not find user with the provided id');
			$user = Sentinel::update($user, $credentials);
        }else{
			$user = Sentinel::registerAndActivate($credentials);
        }

		$user->first_name = $request->input('first_name');
		$user->last_name  = $request->input('last_name');
        $user->save();

        $message = $id ? 'User has been updated' : 'User has been created';
        return redirect('admin/user')->withMessage($message);
	}

	public function getEdit(Request $request, $id = 0){
		$user = $id ? User::find($id) : null;

		if($id && !$user) return redirect('admin/user')->withMessage('Could not find user');

		return view('admin.user.edit', [
			'user' => $user
		]);
	}

	public function postCreate(Request $request){
		return $this->postEdit($request, 0);
	}

	public function getCreate(Request $request){
		return $this->getEdit($request, 0);
	}

	public function getDelete($id){
		$user = User::find($id);

		if(!$user) return redirect('admin/user')->withError('Could not find user');
		$user->delete();

		return redirect('admin/user')->withMessage('User has been removed');
	}

	public function get2faEnable(){
        $user = Sentinel::getUser();

        $secret = Google2FA::generateSecretKey();
        $user->setVar('2fa_secret', $secret);

        return redirect('admin/user/2fa')->withMessage('Two Factor Authentication has been enabled');
    }

    public function get2faDisable(){
        $user = Sentinel::getUser();
        $user->deleteVar('2fa_secret');
        $user->deleteVar('2fa_enabled');

        return redirect('admin/user/2fa')->withMessage('Two Factor Authentication has been disabled');
    }

    public function post2fa(Request $request){
        $code = $request->input('code');

        if(!$code) return redirect('admin/user/2fa')->withError('Invalid Code, could not activate Two Factor Authentication');

        $user = Sentinel::getUser();
        $secret = $user->getVar('2fa_secret');
        if(!$secret) return redirect('admin/user/2fa')->withError('Two Factor Authentication is not yet enabled');

        $result = Google2FA::verifyKey($secret, $code);

        if(!$result) return redirect('admin/user/2fa')->withError('Invalid Code, please try again');

        $user->setVar('2fa_enabled', 1);

        return redirect('admin/user/2fa')->withMessage('Two Factor Authentication is active and confirmed');
    }

	public function get2fa(){
        $user = Sentinel::getUser();
        $secret = $user->getVar('2fa_secret');
        $enabled = $user->getVar('2fa_enabled');

        $qr_url = null;
        if($secret){
            $qr_url = Google2FA::getQRCodeGoogleUrl(
                env('APP_TITLE'),
                $user->email,
                $secret
            );
        }

        return view('admin.user.2fa', [
            'user'    => $user,
            'qr_url'  => $qr_url,
            'enabled' => $enabled
        ]);
    }
}
