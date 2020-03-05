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
					$this->session->set_userdata('adminemail',$adminemail);
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

		public function settings(){
			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->view('admin/settings');
		}

		public function changePassword(){

			$this->form_validation->set_rules('newAdminName','Admin Name','trim|required',
									array(['required'=>'%s is Required']));
			$this->form_validation->set_rules('newAdminPassword','Admin Password','trim|required',
									array(['required'=>'%s is Required']));

			if($this->form_validation->run()){
			$adminId = $this->input->post('adminId');
			$adminName = $this->input->post('newAdminName');
			$adminPassword = $this->input->post('newAdminPassword');

			$this->load->model('AdminModel');
			if($this->AdminModel->changeAdminCredentials($adminId,$adminName,$adminPassword)){
					$this->session->set_flashdata('logout_success','Credentials Changed Succesfully, Login Again');
					$this->session->unset_userdata('adminId');
					return redirect('admin/AdminLogin');
			}
			}
			else{
				$this->load->view('admin/settings');
			}
		}

		public function recoverPassword(){
				$this->load->view('admin/recoverPassword');
		}
		public function sendPasswordEmail(){
			$this->form_validation->set_rules('adminEmail','Admin Email','trim|required',array(['required' => '$s is Required']));

				$adminemail = $this->input->post('adminEmail');

			if ($this->form_validation->run()) {
				$adminEmail = $this->input->post('adminemail');
				$this->load->model('AdminModel');
				if($this->AdminModel->checkAdmin($adminemail)){
					echo "ok";
				}
				else{
					$this->session->set_flashdata('error','Unuthorised Email Address');
					return redirect('admin/AdminLogin/recoverPassword');
				}
			}

			else
			{
				$this->load->view('admin/recoverPassword');
			}
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

			$categoryId = $_GET['categoryId'];
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

		public function deleteSelectedCategories(){
					$categoryIds = $this->input->post('categoryIds');
					$this->load->model('AdminModel');
					foreach ($categoryIds as $categoryId) {

						if(!$this->AdminModel->deleteCategory($categoryId))
						{
								$this->session->set_flashdata('error','Category Deletion Failed');
								return redirect('admin/AdminLogin/welcome');
						}

					}
						$this->session->set_flashdata('success','Category Deleted Successfully');
						return redirect('admin/AdminLogin/welcome');

								
		}

		public function deleteSelectedFields(){
			$fieldIds = $this->input->post('fieldId');
			$this->load->model('AdminModel');
					foreach ($fieldIds as $fieldId) {

						if(!$this->AdminModel->deleteField($fieldId))
						{
								$this->session->set_flashdata('error','Field Deletion Failed');
								return redirect('admin/AdminLogin/createField');
						}

					}
						$this->session->set_flashdata('success','Field Deleted Successfully');
						return redirect('admin/AdminLogin/createField');
		}


		public function editCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

				$categoryName = $_GET['categoryName'];
				$categoryId = $_GET['categoryId'];
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

			if(isset($_GET['categoryId'])){
					$this->session->set_userdata('categoryId',$_GET['categoryId']);
			}

			if(isset($_GET['categoryName'])){
				$this->session->set_userdata('categoryName',$_GET['categoryName']);
			}

				$categoryName = $this->session->userdata('categoryName');
				$categoryId = $this->session->userdata('categoryId');

		
			$categoryData['categoryId'] = $categoryId;

			$categoryData['categoryName'] = $categoryName;

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
			'allowed_types'=>'docx',
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
		function deleteDocument(){

			$this->load->model('AdminModel');
			// $documentId = $this->input->post('documentId');
			// $documentName = $this->input->post('documentName');
			 $documentId = $_GET['documentId'];
			 $documentName = $_GET['documentName'];
			$filePath = "uploads/";

			if($this->AdminModel->deleteDocuments($documentId))
			{
					unlink($filePath.$documentName.".docx");
					$this->session->set_flashdata('success','Document Deleted Successfully');
					return redirect('admin/AdminLogin/documents');

			}
			else{
					$this->session->set_flashdata('error','Document Deletion Failed');
					return redirect('admin/AdminLogin/documents');

			}
		}

		function deleteSelected(){

			$docNames = $this->input->post('docName');

			$this->load->model('AdminModel');
			$filePath = "uploads/";

			foreach ($docNames as $docName) {
			$docDetails = explode('&', $docName);

			$documentId = $docDetails[0];
			$documentName = $docDetails[1];

				if($this->AdminModel->deleteDocuments($documentId))
				{
						unlink($filePath.$documentName.".docx");

				}
				else{
						$this->session->set_flashdata('error','Error in Deletion');
						return redirect('admin/AdminLogin/documents');

				}
			}

						$this->session->set_flashdata('success','Selected Documents Deleted Successfully');
						return redirect('admin/AdminLogin/documents');
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
				$docRevisedDate = $this->input->post('docRevisedDate');

				$old_name = $filePath.$documentName.".docx";
				$new_name = $filePath.$documentUpdatedName.".docx";

				if(isset($_POST['submit'])){

				if(file_exists($filePath.$documentName.".docx"))
		        { 

		        	if(file_exists($filePath.$documentUpdatedName.".docx")){
						$this->session->set_flashdata('error','File Name Already Used, Chose another name');
						return redirect('admin/AdminLogin/documents');
		        	}
		        	if(rename( $old_name, $new_name))
		        	{

		        	$newPath = base_url($new_name);
		        	$this->load->model('AdminModel');
		        	if($this->AdminModel->updateDocumentName($documentId, $documentUpdatedName,$newPath,$docRevisedDate)){

		        		$this->session->set_flashdata('success','Rename Successfully');
						return redirect('admin/AdminLogin/documents');
		        	}

		        	}
		        	else{
					$this->session->set_flashdata('error','File Renaming Error');
					return redirect('admin/AdminLogin/documents');
		        	}
		        }
		        else
		        {
					$this->session->set_flashdata('error','File Does not exist in Storage');
					return redirect('admin/AdminLogin/documents');
		        }
		    }

		    elseif(isset($_POST['reviseDate'])){
		    	
	        	$this->load->model('AdminModel');
	        	$newPath = base_url($old_name);
		    	if($this->AdminModel->updateDocumentName($documentId, $documentUpdatedName,$newPath,$docRevisedDate)){

		        		$this->session->set_flashdata('success','Date Revised Successfully');
						return redirect('admin/AdminLogin/documents');
		        	}
		    }

			}
		        else
		        {
					$this->session->set_flashdata('error','Document Name is Required');
					return redirect('admin/AdminLogin/documents');
		        }

		}

		public function createField(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->model('AdminModel');
			$fieldList = $this->AdminModel->allFieldList();	
			$documents = $this->AdminModel->allDocuments();
			$this->load->view('admin/createField',['filedList'=>$fieldList, 'documents'=>$documents]);
		
		}

		public function fieldValidate(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');


			$this->form_validation->set_rules('labelName','Field Label Name','trim|required',
									array(['required'=>'%s is Required']));

			$this->form_validation->set_rules('labelText','Text to Replace','trim|required',
									array(['required'=>'%s is Required']));

			// $this->form_validation->set_rules('documents','Select','trim|required',
			// 						array(['required'=>'%s at least one option']));

			if($this->form_validation->run()){

				$fieldsData = $this->input->post();

				$radioOption = $fieldsData['radio_flavour'];
				$labelName = $fieldsData['labelName'];
				$labelText = $fieldsData['labelText'];
				$labelText = str_replace(' ','',$labelText);

				if($radioOption == 'multipleDocuments'){
					$selectedDocuments = $fieldsData['documents'];
				}
				else{
					$selectedDocuments[] = $radioOption;
				}

				$this->load->model('AdminModel');

				if($this->AdminModel->addField($labelName,$labelText,$selectedDocuments)){
					
					$this->session->set_flashdata('success','Fields Created Successfully');
					return redirect('admin/AdminLogin/createField');

				}
			else{
					$this->session->set_flashdata('error','Fields Adding Failed');
					return redirect('admin/AdminLogin/createField');

			}

			}
			else{
			$this->session->set_flashdata('error','Input required data');
			return redirect('admin/AdminLogin/createField');
		}
		}

		public function fieldUpdate(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->form_validation->set_rules('labelName','Field Label Name','trim|required');

			$this->form_validation->set_rules('labelText','Text to Replace','trim|required');

			if($this->form_validation->run()):
				$labelName = $this->input->post('labelName');
				$labelText = $this->input->post('labelText');
				$fieldId = $this->input->post('fieldId');

				$labelText = str_replace(' ','',$labelText);
				$this->load->model('AdminModel');

				if($this->AdminModel->updateField($labelName,$labelText,$fieldId)){
					
					$this->session->set_flashdata('success','Fields Updated Successfully');
					return redirect('admin/AdminLogin/createField');

				}
			else{
					$this->session->set_flashdata('error','Field Updatings Failed');
					return redirect('admin/AdminLogin/createField');

			}

			endif;

			$this->session->set_flashdata('emptyFields','Both Fields are Required for Updation');
			return redirect('admin/AdminLogin/createField');
		}

		function deleteField($fieldId){
			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->model('AdminModel');

			if($this->AdminModel->deleteField($fieldId))
			{
					$this->session->set_flashdata('success','Field Deleted Successfully');
					return redirect('admin/AdminLogin/createField');

			}
			else{
					$this->session->set_flashdata('error','Field Deletion Failed');
					return redirect('admin/AdminLogin/createField');

			}
		}

}
?>