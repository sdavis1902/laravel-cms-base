<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Sentinel;
use Validator;
use Illuminate\Http\Request;
use Session;
use Reminder;
use Mail;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller {
	public function getIndex(){
		return view('welcome');
	}
}
