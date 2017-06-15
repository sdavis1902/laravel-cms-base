<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVar extends Model {
    use SoftDeletes;

    protected $table = 'user_vars';
    protected $fillable = ['key', 'value'];
    protected $dates = ['deleted_at'];
    protected $touches = ['User'];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }
}
