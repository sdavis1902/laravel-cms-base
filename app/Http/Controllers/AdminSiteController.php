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
			$folder->order = Folder::get()->count()+1;
        }

        $folder->url = $request->input('url');
        $folder->name = $request->input('name');
        $folder->status = $request->input('status');
        $folder->visible = $request->input('visible');
        $folder->save();

        $message = $id ? 'Folder has been updated' : 'Folder has been created';
        return redirect('admin/site')->withMessage($message);
    }

    public function getFolder($id = 0){
        $folder = $id ? Folder::find($id) : null;

        return view('admin.site.folder', [
            'folder'    => $folder
        ]);
    }

	public function getFolderDelete($id){
		$folder = Folder::find($id);

		if(!$folder) return redirect('admin/site')->withError('Invalid Folder ID.  Could not delete Folder.');

		$folder->delete();
		return redirect('admin/site')->withMessage('Folder has been deleted');
	}

	public function getFolderChangeOrder($id, $direction){
		$direction = $direction == 'asc' ? 'asc' : 'desc';
		$folder = Folder::find($id);

		if(!$folder) return redirect('admin/site')->withError('Invalid Folder ID.  Could not re order Folder.');

		$neworder = $direction == 'desc' ? $folder->order + 1 : $folder->order - 1;
		$swapfolder = Folder::where('order', '=', $neworder)->first();

		if( $swapfolder ){
			$swapfolder->order = $folder->order;
			$swapfolder->save();
			$folder->order = $neworder;
			$folder->save();

			return redirect('admin/site')->withMessage('Folder has been re ordered');
		}

		return redirect('admin/site')->withMessage('Folder could not be re ordered, nothing to swap with');
	}

	public function getFolderChangeVisible($id){
		$folder = Folder::find($id);

		if(!$folder) return redirect('admin/site')->withError('Invalid Folder ID.  Could not change folder visibility.');

		$folder->visible = $folder->visible == 'Yes' ? 'No' : 'Yes';
		$folder->save();

		return redirect('admin/site')->withMessage('Folder visibility has been updated');
	}

	public function getFolderChangeStatus($id){
		$folder = Folder::find($id);

		if(!$folder) return redirect('admin/site')->withError('Invalid Folder ID.  Could not change folder status.');

		$folder->status = $folder->status == 'Active' ? 'Inactive' : 'Active';
		$folder->save();

		return redirect('admin/site')->withMessage('Folder status has been updated');
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
			$page->order = Page::get()->count()+1;
        }

        $page->url = $request->input('url');
        $page->name = $request->input('name');
        $page->content = $request->input('content');
        $page->layout_id = $request->input('layout');
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

        return view('admin.site.page', [
            'page'      => $page,
            'folders'   => $folders,
			'layouts'   => $layouts
        ]);
    }

	public function getPageDelete($id){
		$page = Page::find($id);

		if(!$page) return redirect('admin/site')->withError('Invalid Page ID.  Could not delete Page.');

		$page->delete();
		return redirect('admin/site')->withMessage('Page has been deleted');
	}

	public function getPageChangeOrder($id, $direction){
		$direction = $direction == 'asc' ? 'asc' : 'desc';
		$page = Page::find($id);

		if(!$page) return redirect('admin/site')->withError('Invalid Page ID.  Could not re order Page.');

		$neworder = $direction == 'desc' ? $page->order + 1 : $page->order - 1;
		$swappage = page::where('folder_id', '=', $page->folder_id)->where('order', '=', $neworder)->first();

		if( $swappage ){
			$swappage->order = $page->order;
			$swappage->save();
			$page->order = $neworder;
			$page->save();

			return redirect('admin/site')->withMessage('Page has been re ordered');
		}

		return redirect('admin/site')->withMessage('Page could not be re ordered, nothing to swap with');
	}

	public function getPageChangeVisible($id){
		$page = Page::find($id);

		if(!$page) return redirect('admin/site')->withError('Invalid Page ID.  Could not change page visibility.');

		$page->visible = $page->visible == 'Yes' ? 'No' : 'Yes';
		$page->save();

		return redirect('admin/site')->withMessage('Page visibility has been updated');
	}

	public function getPageChangeStatus($id){
		$page = Page::find($id);

		if(!$page) return redirect('admin/site')->withError('Invalid Page ID.  Could not change page status.');

		$page->status = $page->status == 'Active' ? 'Inactive' : 'Active';
		$page->save();

		return redirect('admin/site')->withMessage('Page status has been updated');

	}
}
