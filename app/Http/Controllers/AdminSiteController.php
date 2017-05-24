<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\PageLayout;
use App\Models\Folder;

class AdminSiteController extends Controller {
	public function getIndex(){
        $folders = Folder::with(['pages' => function($q){
            $q->orderBy('order','asc');
        }])->orderBy('order', 'asc')->get();

        return view('admin.site.index', [
            'folders' => $folders
        ]);
    }

	public function postFolder(Request $request, $id = 0){
        $rules = [
            'name' => 'required',
            'url'  => 'required'
        ];
        $v = Validator::make($request->all(), $rules);

        if( $v->fails() ){
            return redirect('admin/site/folder'. ($id?"/$id":''))->withErrors($v)->withInput();
        }

        if( $id ){
            $folder = Folder::find($id);
            if( !$folder ) return redirect('admin/site')->withError('Could not find folder with the provided id');
        }else{
            $folder = new Folder;
        }

        $folder->url = $request->input('url');
        $folder->name = $request->input('name');
        $folder->order = $request->input('order');
        $folder->status = $request->input('status');
        $folder->visible = $request->input('visible');
        $folder->save();

        $message = $id ? 'Folder has been updated' : 'Folder has been created';
        return redirect('admin/site')->withMessage($message);
    }

    public function getFolder($id = 0){
        $folder = $id ? Folder::find($id) : null;

		$max_order = Folder::where('status', '=', 'Active')->where('visible', '=', 'Yes')->get()->count();
		if( !$folder || $folder->status != 'Active' || $folder->visible == 'No' ) $max_order++;

        return view('admin.site.folder', [
            'folder'    => $folder,
			'max_order' => $max_order
        ]);
    }

	public function postPage(Request $request, $id = 0){
        $rules = [
            'name' => 'required',
            'url'  => 'required'
        ];
        $v = Validator::make($request->all(), $rules);

        if( $v->fails() ){
            return redirect('admin/site/page'. ($id?"/$id":''))->withErrors($v)->withInput();
        }

        if( $id ){
            $page = Page::find($id);
            if( !$page ) return redirect('admin/site')->withError('Could not find page with the provided id');
        }else{
            $page = new Page;
        }

        $page->url = $request->input('url');
        $page->name = $request->input('name');
        $page->content = $request->input('content');
        $page->layout_id = $request->input('layout');
        $page->order = $request->input('order');
        $page->status = $request->input('status');
        $page->visible = $request->input('visible');
        $page->folder_id = $request->input('folder');
        $page->save();

        $message = $id ? 'Page has been updated' : 'Page has been created';
        return redirect('admin/site')->withMessage($message);

    }

    public function getPage($id = 0){
        $folders = Folder::get();
		if( $folders->count() === 0 ){
			return redirect('admin/site/folder')->withMessage('There are no folders, you must create one before you can create a page');
		}
        $page = $id ? Page::find($id) : null;
        $layouts = PageLayout::get();

		$max_order = Page::where('status', '=', 'Active')->where('visible', '=', 'Yes')->get()->count();
		if( !$page || $page->status != 'Active' || $page->visible == 'No' ) $max_order++;

        return view('admin.site.page', [
            'page'      => $page,
            'folders'   => $folders,
			'layouts'   => $layouts,
			'max_order' => $max_order
        ]);
    }
}