<?php
	class HomeController extends CI_Controller{

		public function __construct(){
		parent::__construct();
			if(!$this->session->userdata('adminId'))
				return redirect('admin');
				$this->load->model('AdminModel');
				$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}

		public function settings(){
			$this->load->view('admin/jury_forms/settings');
		}

		public function welcome()
		{	
			$queryResult = $this->AdminModel->getCategories();
			$this->session->set_userdata('categoryData_',$queryResult->result());
			#$documentsNumbers = $this->AdminModel->getDocumentsList($CategoryId);

			$this->load->view('admin/jury_forms/dashboard',['categoriesData'=>$queryResult]);
		}

		public function createCategory(){


			$this->load->view('admin/jury_forms/createCategory');
		}

		public function categoryValidate(){
			$this->form_validation->set_rules('newCategory','Category','trim|required',
									array(['required'=>'%s is Required']));
			if($this->form_validation->run()):
				$categoryName = $this->input->post('newCategory');

				$this->load->model('AdminModel','newCategory');

				if($this->newCategory->addCategory($categoryName)){
					$this->session->set_flashdata('success','Category Created Successfully');
					return redirect('admin/jury_forms/categories');

				}
			else{
					$this->session->set_flashdata('error','Category Adding Failed');
					return redirect('admin/jury_forms/create_new_category');

			}

			endif;


			$this->load->view('admin/createCategory');
		}

		public function deleteCategory(){

			$categoryId = $_GET['categoryId'];
			
			
			if($this->AdminModel->deleteCategory($categoryId))
			{
					$this->session->set_flashdata('success','Category Deleted Successfully');
					return redirect('admin/jury_forms/categories');

			}
			else{
					$this->session->set_flashdata('error','Category Deletion Failed');
					return redirect('admin/jury_forms/categories');

			}
		}

		public function deleteSelectedCategories(){
					$categoryIds = $this->input->post('categoryIds');
					
					foreach ($categoryIds as $categoryId) {

						if(!$this->AdminModel->deleteCategory($categoryId))
						{
								$this->session->set_flashdata('error','Category Deletion Failed');
								return redirect('admin/jury_forms/categories');
						}

					}
						$this->session->set_flashdata('success','Category Deleted Successfully');
						return redirect('admin/jury_forms/categories');

								
		}

		public function deleteSelectedFields(){
			$fieldIds = $this->input->post('fieldId');
			
					foreach ($fieldIds as $fieldId) {

						if(!$this->AdminModel->deleteField($fieldId))
						{
								$this->session->set_flashdata('error','Field Deletion Failed');
								return redirect('admin/jury_forms/dynamic_fields');
						}

					}
						$this->session->set_flashdata('success','Field Deleted Successfully');
						return redirect('admin/jury_forms/dynamic_fields');
		}

		public function assignNewDocument($fieldID){

			$documents = $this->AdminModel->allFilterDocuments($fieldID);
			$this->load->view('admin/jury_forms/assignNewDocument',['documents'=>$documents, 'fieldID'=>$fieldID]);
		}

		public function assignMoreDocuments(){

			$docData = $this->input->post();
			if ($docData['fieldID'] && $docData['documents']) {
				if($this->AdminModel->assignMoreDocuments($docData['fieldID'],$docData['documents'])){
						$this->session->set_flashdata('success','Document Added Successfully');
						return redirect('admin/jury_forms/dynamic_fields');
					}
			} else {
				$this->session->set_flashdata('error','No Document selected');
						return redirect($this->agent->referrer());
			}
		}

		public function editCategory(){
				$categoryName = $_GET['categoryName'];
				$categoryId = $_GET['categoryId'];
				$this->load->view('admin/jury_forms/updateCategory',['categoryName'=>$categoryName,'categoryId'=>$categoryId]);
		}

		public function updateCategory(){


			$this->form_validation->set_rules('editCategory','Category Name','trim|required',
											array('required' => '%s is Required'));

			$editCategory = $this->input->post('editCategory');
			$CategoryId = $this->input->post('categoryId');

			if($this->form_validation->run()){
				
				if($this->AdminModel->updateCategoryName($CategoryId,$editCategory)){

					$this->session->set_flashdata('success','Name Changed Successfully');
					return redirect('admin/jury_forms/categories');
				}
				else{
					$this->session->set_flashdata('error','Name Could not Changed');
					return redirect('admin/jury_forms/categories');
				}
			}
			else
			{
				$this->load->view('admin/jury_forms/updateCategory',['categoryName'=>$editCategory,'categoryId'=>$CategoryId]);
			}
		}

		public function documents(){


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

			
			$documentList = $this->AdminModel->getDocumentsList($categoryData['categoryId']);

			if($categoryData){
				$multipleData = array('categoryData' => $categoryData, 'documentList' => $documentList);
				$this->load->view('admin/jury_forms/documents',['multipleData'=>$multipleData]);
			}
			else
			{
				$this->load->view('admin/jury_forms/documents',['multipleData'=>$multipleData]);
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
				
				$revised_date = $this->return_revised_date($data['full_path']);	
				$image_path[$key]['file_name'] = base_url("uploads/".$data['raw_name'].$data['file_ext']);
				$image_name[$key]['file_name'] = $data['raw_name'];

				
				if(!$this->AdminModel->addDocuments($categoryData['categoryId'],$image_path[$key]['file_name'],$image_name[$key]['file_name'],$revised_date))
				{
					$this->session->set_flashdata('error','Documents Adding Failed');
					return redirect('admin/jury_forms/HomeController/documents');
				}
			}
			else{
				$upload_error = $this->upload->display_errors();
					$this->session->set_flashdata('upload_error',$upload_error);
				return redirect('admin/jury_forms/HomeController/documents');
			}

			}

			$this->session->set_flashdata('success','Documents Added Successfully');
					return redirect('admin/jury_forms/HomeController/documents');
		}

		function return_revised_date($file_name)
		{
			require("doc2txt.class.php");
			$docObj = new Doc2Txt($file_name);
			$lines = $docObj->convertToText();
			$para_array = explode('-flag-',$lines);
			$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
			$revised_date = '00000';
			$flag = false;
			foreach ($para_array as $key => $value) {
				if ($value == "" || substr($value,0,3) != "New") {
					unset($para_array[$key]);
				} else {
					$string = $value;
					// if(stripos($value, "Revised") !== false){ //This code is for checking if date is new o revised
						// echo "It has revised date<br>";
						$string_array = array_reverse(explode(" ", $string));
						// print_r($string_array);

						foreach ($string_array as $key => $value) {
							$value = substr($value,0,4);
								if (is_numeric($value)) {
									$revised_year = $value;
									$year_key = $key;
									$month_key = $key+1;
									foreach ($months as $key_ => $value_) {
										if ($value_ == $string_array[$month_key]) {
											$revised_month =  $value_;
											$revised_date = $revised_month." ".$revised_year;
											$flag = true;
										break;
										}
									}
									if ($flag) {
										break;
									} else {
										continue;
									}
								}
						}
					// }  //This code is for checking if date is new o revised
					// else{
					// 	echo "It has new date<br>";
					// 	$line_array = explode(' ',$string);
					// 	$revised_date = $line_array[1]." ".$line_array[2];
					// 	$revised_date = str_replace(';','',$revised_date);
					// }
				}
			}
			return $revised_date;
		}
		function deleteDocument(){
			
			// $documentId = $this->input->post('documentId');
			// $documentName = $this->input->post('documentName');
			 $documentId = $_GET['documentId'];
			 $documentName = $_GET['documentName'];
			$filePath = "uploads/";

			if($this->AdminModel->deleteDocuments($documentId))
			{
					unlink($filePath.$documentName.".docx");
					$this->session->set_flashdata('success','Document Deleted Successfully');
					return redirect('admin/jury_forms/HomeController/documents');

			}
			else{
					$this->session->set_flashdata('error','Document Deletion Failed');
					return redirect('admin/jury_forms/HomeController/documents');

			}
		}

		function deleteSelected(){

			$docNames = $this->input->post('docName');

			
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
						return redirect('admin/jury_forms/HomeController/documents');

				}
			}

						$this->session->set_flashdata('success','Selected Documents Deleted Successfully');
						return redirect('admin/jury_forms/HomeController/documents');
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
						return redirect('admin/jury_forms/HomeController/documents');
		        	}
		        	if(rename( $old_name, $new_name))
		        	{

		        	$newPath = base_url($new_name);
		        	
		        	if($this->AdminModel->updateDocumentName($documentId, $documentUpdatedName,$newPath,$docRevisedDate)){

		        		$this->session->set_flashdata('success','Rename Successfully');
						return redirect('admin/jury_forms/HomeController/documents');
		        	}

		        	}
		        	else{
					$this->session->set_flashdata('error','File Renaming Error');
					return redirect('admin/jury_forms/HomeController/documents');
		        	}
		        }
		        else
		        {
					$this->session->set_flashdata('error','File Does not exist in Storage');
					return redirect('admin/jury_forms/HomeController/documents');
		        }
		    }

		    elseif(isset($_POST['reviseDate'])){
		    	
	        	
	        	$newPath = base_url($old_name);
		    	if($this->AdminModel->updateDocumentName($documentId, $documentUpdatedName,$newPath,$docRevisedDate)){

		        		$this->session->set_flashdata('success','Date Revised Successfully');
						return redirect('admin/jury_forms/HomeController/documents');
		        	}
		    }

			}
		        else
		        {
					$this->session->set_flashdata('error','Document Name is Required');
					return redirect('admin/jury_forms/HomeController/documents');
		        }

		}

		public function createField(){
			$fieldList = $this->AdminModel->allFieldList();	
			$documents = $this->AdminModel->allDocuments();
			$this->load->view('admin/jury_forms/createField',['filedList'=>$fieldList, 'documents'=>$documents]);
		
		}

		public function fieldValidate(){



			$this->form_validation->set_rules('labelName','Field Label Name','trim|required',
									array(['required'=>'%s is Required']));

			$this->form_validation->set_rules('labelText','Text to Replace','trim|required',
									array(['required'=>'%s is Required']));

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

				if($this->AdminModel->addField($labelName,$labelText,$selectedDocuments)){
					
					$this->session->set_flashdata('success','Fields Created Successfully');
					return redirect('admin/jury_forms/dynamic_fields');

				}
			else{
					$this->session->set_flashdata('error','Fields Adding Failed');
					return redirect('admin/jury_forms/dynamic_fields');

			}

			}
			else{
			$this->session->set_flashdata('error','Input required data');
			return redirect('admin/jury_forms/dynamic_fields');
		}
		}

		public function removeDocumentFromKeyword($docID,$keywordID){
			if($this->AdminModel->removeDocumentFromKeyword($docID,$keywordID)){
				$this->session->set_flashdata('success','Document Removed from Keyword');
					return redirect('admin/jury_forms/dynamic_fields');
			}
		}

		public function fieldUpdate(){


			$this->form_validation->set_rules('labelName','Field Label Name','trim|required');

			$this->form_validation->set_rules('labelText','Text to Replace','trim|required');

			if($this->form_validation->run()):
				$labelName = $this->input->post('labelName');
				$labelText = $this->input->post('labelText');
				$fieldId = $this->input->post('fieldId');

				$labelText = str_replace(' ','',$labelText);
				

				if($this->AdminModel->updateField($labelName,$labelText,$fieldId)){
					
					$this->session->set_flashdata('success','Fields Updated Successfully');
					return redirect('admin/jury_forms/dynamic_fields');

				}
			else{
					$this->session->set_flashdata('error','Field Updatings Failed');
					return redirect('admin/jury_forms/dynamic_fields');

			}

			endif;

			$this->session->set_flashdata('emptyFields','Both Fields are Required for Updation');
			return redirect('admin/jury_forms/dynamic_fields');
		}

		function deleteField($fieldId){

			

			if($this->AdminModel->deleteField($fieldId))
			{
					$this->session->set_flashdata('success','Field Deleted Successfully');
					return redirect('admin/jury_forms/dynamic_fields');

			}
			else{
					$this->session->set_flashdata('error','Field Deletion Failed');
					return redirect('admin/jury_forms/dynamic_fields');

			}
		}

}
?>