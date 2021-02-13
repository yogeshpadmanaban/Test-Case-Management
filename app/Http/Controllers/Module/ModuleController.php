<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Category;

use Config;
use Session;


class ModuleController extends Controller
{
	//to view category listing
	public function module_listing(Request $request)
	{
		$categories = Category::where('parent_id', '=', 0)->get();
        $allCategories = Category::pluck('module_name','id')->all();
	    return view('module_list',compact('categories','allCategories'));
	}

	//to store category details 
	public function store(Request $request)
	{
		$id=$request['hdn_id'];

		$this->validate($request, [
			// 'parent_id' => 'required',
			'module_name' => 'required',
		]); 

		$category_data = [
			'parent_id'=>empty($request->input('module')) ? 0 : $request->input('module'),
			'module_name'=>$request->input('module_name')
		];

		Category::updateOrCreate(['id'=>$id],$category_data); 
		Session::flash('session_msg','Category data updated successfully!');
		return redirect()->to('module');	
	
	}
}