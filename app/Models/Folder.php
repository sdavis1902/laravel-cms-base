<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

	public function visiblepages(){
		return $this->hasMany('App\Models\Page')->where('visible', '=', 'Yes')->where('status', '=', 'Active');
	}

	public function activepages(){
		return $this->hasMany('App\Models\Page')->where('status', '=', 'Active');
	}

	public function pages(){
		return $this->hasMany('App\Models\Page');
	}
}
