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

class DashboardController extends Controller {
	public function getIndex(){
		return view('welcome');
	}
}
