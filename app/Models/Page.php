<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    use SoftDeletes;

    protected $table = 'pages';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

	public function folder(){
		return $this->belongsTo('App\Models\Folder');
	}

	public function layout(){
		return $this->belongsTo('App\Models\PageLayout');
	}
}
