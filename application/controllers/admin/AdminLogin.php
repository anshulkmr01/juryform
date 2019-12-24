<?php
	class AdminLogin extends CI_Controller{

public function __construct(){

		parent::__construct();
				$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}
		public function index()
		{
			if($this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/welcome');
			$this->load->view('admin/login');
		}

		public function validate(){
			$this->form_validation->set_rules('adminemail','Email','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('adminpassword','Password','trim|required',
											array('required' => '%s is Required'));
			if($this->form_validation->run())
			{
				$adminemail = $this->input->post('adminemail');
				$adminpassword = $this->input->post('adminpassword');
				$this->load->model('AdminModel');
				$adminId = $this->AdminModel->isValidate($adminemail,$adminpassword);
				if($adminId){
					//$this->load->library('session');
					// Setting the user id into user session
					$this->session->set_userdata('adminId',$adminId);
					return redirect('admin/AdminLogin/welcome');
				}
				else{
					$this->session->set_flashdata('login_failed','Invalid Email or Password');
					return redirect('admin/AdminLogin/index');
				}
			}
			else
			{
				$this->load->view('admin/login');
			}
		}

		public function welcome()
		{
			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->model('AdminModel');
			$queryResult = $this->AdminModel->getCategories();
			#$documentsNumbers = $this->AdminModel->getDocumentsList($CategoryId);

			$this->load->view('admin/dashboard',['categoriesData'=>$queryResult]);
		}

		public function logout(){
			$this->session->unset_userdata('adminId');
			$this->session->set_flashdata('logout_success',"Logout Successfully");
			return redirect ('admin/AdminLogin/');
		}

		public function createCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->view('admin/createCategory');
		}

		public function categoryValidate(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');


			$this->form_validation->set_rules('newCategory','Category','trim|required',
									array(['required'=>'%s is Required']));
			if($this->form_validation->run()):
				$categoryName = $this->input->post('newCategory');

				$this->load->model('AdminModel','newCategory');

				if($this->newCategory->addCategory($categoryName)){
					
					$this->session->set_flashdata('success','Category Created Successfully');
					return redirect('admin/adminLogin/createCategory');

				}
			else{
					$this->session->set_flashdata('error','Category Adding Failed');
					return redirect('admin/adminLogin/createCategory');

			}

			endif;


			$this->load->view('admin/createCategory');
		}

		public function deleteCategory(){

			$categoryId = $this->input->post('categoryId');
			$this->load->model('AdminModel');
			if($this->AdminModel->deleteCategory($categoryId))
			{
					$this->session->set_flashdata('success','Category Deleted Successfully');
					return redirect('admin/AdminLogin/welcome');

			}
			else{
					$this->session->set_flashdata('error','Category Deletion Failed');
					return redirect('admin/AdminLogin/welcome');

			}
		}


		public function editCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');


			$categoryName = $this->input->post('categoryName');
			$categoryId = $this->input->post('categoryId');
			$this->load->view('admin/updateCategory',['categoryName'=>$categoryName,'categoryId'=>$categoryId]);
		}

		public function updateCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->form_validation->set_rules('editCategory','Category Name','trim|required',
											array('required' => '%s is Required'));

			$editCategory = $this->input->post('editCategory');
			$CategoryId = $this->input->post('categoryId');

			if($this->form_validation->run()){
				$this->load->model('AdminModel');
				if($this->AdminModel->updateCategoryName($CategoryId,$editCategory)){

					$this->session->set_flashdata('success','Name Changed Successfully');
					return redirect('admin/AdminLogin/welcome');
				}
				else{
					$this->session->set_flashdata('error','Name Could not Changed');
					return redirect('admin/AdminLogin/welcome');
				}
			}
			else
			{
				$this->load->view('admin/updateCategory',['categoryName'=>$editCategory,'categoryId'=>$CategoryId]);
			}
		}

		public function documents(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');
			$categoryData = $this->input->post();
			if($categoryData){

				$this->session->set_userdata('categoryData',$categoryData);
			}
			else
			{
				$categoryData = $this->session->userdata('categoryData');
			}

			$this->load->model('AdminModel');
			$documentList = $this->AdminModel->getDocumentsList($categoryData['categoryId']);

			if($categoryData){
				$multipleData = array('categoryData' => $categoryData, 'documentList' => $documentList);
				$this->load->view('admin/documents',['multipleData'=>$multipleData]);
			}
			else
			{
				$this->load->view('admin/documents',['multipleData'=>$multipleData]);
			}
		}

		public function uploadFiles(){

			$config=[
			'upload_path'=>'./uploads/',
			'allowed_types'=>'doc|docx|odt|word',
			];

			$categoryData = $this->input->post();

			foreach ($_FILES['docFiles']['name'] as $key=>$val) {

		        $_FILES['file']['name']     = $_FILES['docFiles']['name'][$key];
                $_FILES['file']['type']     = $_FILES['docFiles']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['docFiles']['tmp_name'][$key];
                $_FILES['file']['error']     = $_FILES['docFiles']['error'][$key];
                $_FILES['file']['size']     = $_FILES['docFiles']['size'][$key];

			$this->load->library('upload', $config);
            $this->upload->initialize($config);

			if($this->upload->do_upload('file'))
			{
				
				$data = $this->upload->data();
				$image_path[$key]['file_name'] = base_url("uploads/".$data['raw_name'].$data['file_ext']);
				//$image_name[$key]['file_name'] = $data['file_name'];
				$image_name[$key]['file_name'] = $data['raw_name'];

				$this->load->model('AdminModel');
				if(!$this->AdminModel->addDocuments($categoryData['categoryId'],$image_path[$key]['file_name'],$image_name[$key]['file_name']))
				{
					$this->session->set_flashdata('error','Documents Adding Failed');
					return redirect('admin/AdminLogin/documents');
					//$this->load->view('admin/dashboard',['categoryData'=>$categoryData]);
				}
			}
			else{
				$upload_error=$this->upload->display_errors();
					$this->session->set_flashdata('upload_error',$upload_error);
				return redirect('admin/AdminLogin/documents');
			}
			}

			$this->session->set_flashdata('success','Documents Added Successfully');
					return redirect('admin/AdminLogin/documents');
					//$this->load->view('admin/dashboard',['categoryData'=>$categoryData]);




		}
		function deleteDocument($documentId){

			$this->load->model('AdminModel');

			if($this->AdminModel->deleteDocuments($documentId))
			{
					$this->session->set_flashdata('success','Document Deleted Successfully');
					return redirect('admin/AdminLogin/documents');

			}
			else{
					$this->session->set_flashdata('error','Document Deletion Failed');
					return redirect('admin/AdminLogin/documents');

			}
		}

		function readDocument(){

		//	$document = file_get_contents(base_url("uploads/Mohit_kumar.docx"));
			variables: $app= new COM("Word.Application"); $file = "uploads/Mohit_kumar.docx";
			$app->visible = true; $app->Documents->Open($file);
			$app->ActiveDocument->PrintOut();
		//	print_r($document);

		}

		function documentRename(){

			$this->form_validation->set_rules('docUpdatedName','Document Name','trim|required',
											array('required' => '%s is Required'));
			if ($this->form_validation->run()) {

				$filePath = "uploads/";
				$documentName = $this->input->post('docName');
				$documentUpdatedName = $this->input->post('docUpdatedName');
				$documentId = $this->input->post('docId');

				$old_name = $filePath.$documentName.".docx";
				$new_name = $filePath.$documentUpdatedName.".docx";


				if(file_exists($filePath.$documentName.".docx"))
		        { 
		        	if(rename( $old_name, $new_name))
		        	{

		        	$newPath = base_url($new_name);
		        	$this->load->model('AdminModel');
		        	if($this->AdminModel->updateDocumentName($documentId, $documentUpdatedName, $newPath)){

		        		echo "Rename Successfully" ;
		        	}

		        	}
		        	else{
		        		echo "Error Renaming";
		        	}
		        }
		        else
		        {
		        	echo "File Doesen't Exist in Storage";
		        }

			}
			else{

				echo "Enter Name ";
			}

		}
}
?>