<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PageLayout extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

	public function pages(){
		return $this->hasMany('App\Models\Page');
	}
}
