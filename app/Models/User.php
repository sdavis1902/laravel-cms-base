<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser{
    protected $fillable = [
        'name', 'email', 'password',
    ];

	protected $dates = [
		'created_at', 'update_at', 'deleted_at', 'last_login'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function setVar( $key, $value ){
        if( !$value ) return;
        $var = UserVar::where('user_id', '=', $this->attributes['id'])->where('key', '=', $key)->first();
        if( $var ){
            $var->value = $value;
            $var->save();
        }else{
            $var = new UserVar;
            $var->user_id = $this->attributes['id'];
            $var->key = $key;
            $var->value = $value;
            $var->save();
        }
    }

    public function getVar( $key ){
        $var = UserVar::where('user_id', '=', $this->attributes['id'])->where('key', '=', $key)->first();
        return $var ? $var->value : false;
    }

    public function deleteVar($key){
        UserVar::where('user_id', '=', $this->attributes['id'])->where('key', '=', $key)->delete();
    }
}
