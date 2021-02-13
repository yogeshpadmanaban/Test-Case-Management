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
use App\TestCase;
use Config;
use Session;


class TestCaseController extends Controller
{
	//to view test case listing
	public function test_case_listing(Request $request)
	{
		$data['menu']="test_case_list";
		$data['category']=Category::where('status','=','0')->get();
		$categories = Category::where('parent_id', '=', 0)->get();
        $allCategories = Category::pluck('module_name','id')->all();
		return view('test_case.list',compact('categories','allCategories'),['menu'=>$data['menu'],'category'=>$data['category']]);
	}

	//to fetch test case data
	public function fetch_test_case_details(Request $request)
	{
		$sort=$_REQUEST['order'];
		$search=(isset($_REQUEST['search']))?$_REQUEST['search']:'';
		$limit=(int)$_REQUEST['limit'];
		$offset=(int)$_REQUEST['offset'];

		$result =TestCase::where('deleted_at',NULL); // to get except soft-deleted data->get(); // to get except soft-deleted data
		$data['totalRecords']=$result->count();

		$result_two= DB::table('Db_interview_cases.test_cases',  'tc')
			->select('tc.*','cd.module_name as module_name')
			->leftJoin('db_interview_section.categories AS cd', 'cd.id', '=', 'tc.category_id')
			->where('tc.summary','LIKE','%'.$search.'%')
			->where('tc.deleted_at',NULL) // to get except soft-deleted data
			->where('tc.status','!=','2')
			->limit($limit)->offset($offset)
			->orderBy('tc.id',$sort)
			->get();

		$data['records']=$result_two;
		$data['num_rows'] = $result_two->count();

		foreach ($data['records'] as $key => $value)
		{

			$file_name = "";

			if($data['records'][$key]->attachment!=''){

				$file_name_arr = explode('/',$data['records'][$key]->attachment);
				$file_name = end($file_name_arr);

				$finfo = pathinfo($data['records'][$key]->attachment);

				if($finfo['extension'] == 'pdf')
				{
					$attachment = 'assets/images/pdf-file.svg';
				}
				if($finfo['extension'] == 'docx')	
				{
					$attachment = 'assets/images/doc-file.svg';
				}

				$data['records'][$key]->attachment="<a href='javascript:void(0)' data-file='".$file_name."' data-folder='attachment' data-toggle='lightbox2' data-title='Attachments' data-footer='' class='attachment-lightBox download_file'><img src=".url($attachment)." class='img-fluid' style='width:50px;height:50px'></a>";	

			}
			else{
				$data['records'][$key]->attachment="-";	

			}

			if($data['records'][$key]->description!='')
			{
				$description = substr($data['records'][$key]->description,0,20);
				$data['records'][$key]->description = '<div data-title="'.$data['records'][$key]->description.'">'.$description.'...</span>  </div>';
			}
			else
			{
				$data['records'][$key]->description='-';	
			} 
			$data['records'][$key]->check_box="<input type='checkbox' value='".$data['records'][$key]->id."' name='data' style='width:20px;height:20px'>";	
			
			$data['records'][$key]->action=" <i class='fa fa-edit'  onclick='ajx_test_case_edit(this.id)' style='font-size:18px;cursor:pointer' data-toggle='tooltip' data-placement='top' title='Edit'  id='".base64_encode($data['records'][$key]->id)."'></i></a>&nbsp;

			<i class='fa fa-trash record_delete' style='font-size:20px;cursor:pointer' data-toggle='tooltip' data-placement='top' title='Delete' data-url='test_case_delete/' data-id='".base64_encode($data['records'][$key]->id)."'></i>";
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
		$data['test_case']=TestCase::where('id',base64_decode($id))->first();	
		if($data['test_case']['category_id']!=''){

			$data['category']=Category::where('id',$data['test_case']['category_id'])->get();		
		}
		$data['menu']="test_case_list";
		return ['test_case' => $data['test_case'],'category'=>$data['category'],'menu'=>$data['menu']];
		exit();
	}

	//to delete data of particular id
	public function delete($id)
	{
		TestCase::where('id',base64_decode($id))->update(['status' => '2']);	
		TestCase::find(base64_decode($id))->delete();
		return response()->json([
		'success' => 'Record has been deleted successfully!'
		]);
	}

	//to store test case details 
	public function store(Request $request)
	{
		$id=$request['hdn_id'];

		$attach='';

		if($request->file('attachment'))
		{
			$destinationPath = 'uploads/attachment/'; // upload path
			$files = $request->file('attachment');
			$profile_path = date('YmdHis') . "." . $files->getClientOriginalExtension();
			$files->move($destinationPath, $profile_path);
			$attach=$destinationPath.$profile_path;
		}

		if($id!='')	
		{
			if(($request['temp_attachment']!='')&&($attach==''))
			{	
				$attach=$request['temp_attachment'];
			}
		}

		$test_case_data = [
			'category_id'=>$request->input('module'),
			'summary'=>$request->input('summary'),
			'description'=>$request->input('description'),
			'attachment'=>$attach,
		];

		TestCase::on('mysql2')->updateOrCreate(['id'=>$id],$test_case_data); 
		Session::flash('session_msg','Test Case data updated successfully!');
		return redirect()->to('test_case');		
	
	}
}