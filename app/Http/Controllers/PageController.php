<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Page;
use App\Models\Folder;

class PageController extends Controller {

	public function getPage( $url ){
		if( preg_match('/\//', $url ) ) abort('404');

		$page = Page::where('url', '=', $url)->first();
		if( !$page ) abort('404');

		return view('front.page', [
			'page' => $page
		]);
	}

	public function getHomePage(){
		$folder = Folder::orderBy('order', 'asc')->first();
		$page = Page::where('folder_id', '=', $folder->id)->orderBy('order', 'asc')->first();
		return $this->getPage($page->url);
	}
}
