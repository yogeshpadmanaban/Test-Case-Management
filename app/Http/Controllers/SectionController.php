<?php

namespace App\Http\Controllers;

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


class SectionController extends Controller
{
	//to view section listing
	public function section_listing(Request $request)
	{
		$data['menu']="section_list";

		$categories = Category::where('parent_id', '=', 0)->get();
        $allCategories = Category::pluck('module_name','id')->all();
		return view('section.list',compact('categories','allCategories'),['menu'=>$data['menu']]);
	}

	//to fetch cad data
	public function fetch_section_details(Request $request)
	{
		$sort=$_REQUEST['order'];
		$search=(isset($_REQUEST['search']))?$_REQUEST['sem_acquire(sem_identifier)rch']:'';
		$limit=(int)$_REQUEST['limit'];
		$offset=(int)$_REQUEST['offset'];

		$result =Category::where('deleted_at',NULL); // to get except soft-deleted data->get(); // to get except soft-deleted data
		$data['totalRecords']=$result->count();

		$result_two=Category::limit($limit)->offset($offset)
					->where('module_name','LIKE','%'.$search.'%')
					->where('deleted_at',NULL) // to get except soft-deleted data
					->orderBy('id',$sort)
					->get();
		$data['records']=$result_two;
		$data['num_rows'] = $result_two->count();

		foreach ($data['records'] as $key => $value)
		{
			$data['records'][$key]->action=" <i class='fa fa-edit'  onclick='ajx_category_edit(this.id)' style='font-size:18px;cursor:pointer' data-toggle='tooltip' data-placement='top' title='Edit'  id='".base64_encode($data['records'][$key]->id)."'></i></a>&nbsp;

			<i class='fa fa-trash record_delete' style='font-size:20px;cursor:pointer' data-toggle='tooltip' data-placement='top' title='Delete' data-url='section_delete/' data-id='".base64_encode($data['records'][$key]->id)."'></i>";
			$data['records'][$key]->check="<input type=checkbox class='btn'id='".$data['records'][$key]->id."' value='' >";
		}
		$data['table_data']='{"total":'.intval( $data['totalRecords'] ).',"recordsFiltered":'.intval( $data['num_rows'] ).',"rows":'.json_encode($data['records']).'}';
        $data['menu']="cad_list";
		echo $data['table_data'];
		exit();
	}

	//to get data of particular id for update
	public function update($id)
	{
		$data['category']=Category::where('id',base64_decode($id))->first();	
		if($data['category']['id']!=''){
			$data['categories']=Category::where('id',$data['category']['id'])->get();		
		}
		$data['menu']="section_list";
		return ['category' => $data['category'],'menu'=>$data['menu']];
		exit();
	}

	//to delete data of particular id
	public function delete($id)
	{
		Category::where('id',base64_decode($id))->update(['status' => '2']);	
		Category::find(base64_decode($id))->delete();
		return response()->json([
		'success' => 'Record has been deleted successfully!'
		]);
	}

	public function module_name_check(Request $request){
		$module_name = $request->module_name;
		$id = $request->id;
		$result=Category::select('module_name')->where('module_name',$module_name)	
							->when($id != "", function($result) use ($id){
								return $result->where('id','!=',$id);
							})
							->where('status','!=','2')
							->exists();
		echo $result;
	}

	//to store section details 
	public function store(Request $request)
	{
		$id=$request['hdn_id'];

		$category_data = [
			'parent_id'=>empty($request->input('module')) ? 0 : $request->input('module'),
			'module_name'=>$request->input('module_name')
		];

		Category::updateOrCreate(['id'=>$id],$category_data); 
		Session::flash('session_msg','Section data updated successfully!');
		return redirect()->to('section');	
	
	}
}