<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser{
    protected $fillable = [
        'name', 'email', 'password',
    ];

	protected $dates = [
		'created_at', 'update_at', 'deleted_at'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
