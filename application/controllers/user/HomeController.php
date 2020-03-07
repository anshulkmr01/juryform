<?php
	/**
	 * 
	 */
	class HomeController extends CI_Controller
	{
		function __construct(){
			parent::__construct();
			if(!$this->session->userdata('userId'))
				return redirect('loginUser');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

	    $this->load->model('UserModel');
		$this->load->model('AdminModel');
		}

		public function index(){

		$queryResult = $this->UserModel->get_categories();
		$this->load->view('user/homepage',['categoriesData'=>$queryResult]);
	    }

	    public function settings(){
	    	$this->load->view('user/userProfile');
	    }

	    function changePassword(){
	    	$this->form_validation->set_rules('currentPassword','Current Password','required',array('required'=>'%s is required'));
	    	$this->form_validation->set_rules('newPassword','New Password','required',array('required'=>'%s is required'));
	    	if($this->form_validation->run()){
	    		$changePasswordData = $this->input->post();
	    		if($this->UserModel->changePassword($changePasswordData)){
	    			$this->session->set_flashdata('success', 'Password Successfully Changed. Login Again');
	    			$this->session->unset_userdata('userId');
	    			return redirect('loginUser');
	    		}
	    		else {
	    			$this->session->set_flashdata('error','current password is incorrect');
	    			return redirect('userProfile');
	    		}
	    	}
	    	else{
				$this->load->view('user/userProfile');
	    	}
	    }

	    function getDynamicFields(){
	    	$categoryNames = $this->input->post();
			$fieldList[] = $this->AdminModel->fieldList('allDocuments');
	    	foreach ($categoryNames['docPath'] as $categoryData) {

	    		$docID = explode ("/amg/", $categoryData)[1];  
	    		$docPath[] = explode ("/amg/", $categoryData)[0];  

				$fieldList[] = $this->AdminModel->fieldList($docID);
	    	}
	    	$fieldList = call_user_func_array('array_merge', $fieldList);
	    	$fieldList = call_user_func_array('array_merge', $fieldList);

			$newArray = json_decode(json_encode($fieldList), true);
			$newArray = $this->unique_key($newArray,'FieldName');

			$this->load->view('user/dynamicFields',['fieldList'=>$newArray,'documents'=>$docPath]);
	    }

	    function unique_key($array,$keyname){

		 $new_array = array();
		 foreach($array as $key=>$value){

		   if(!isset($new_array[$value[$keyname]])){
		     $new_array[$value[$keyname]] = $value;
		   }

		 }
		 $new_array = array_values($new_array);
		 return $new_array;
		}
	}
?>