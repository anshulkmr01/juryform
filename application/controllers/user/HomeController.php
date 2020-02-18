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
		$fieldList = $this->AdminModel->fieldList();

		$this->load->view('user/homepage',['categoriesData'=>$queryResult,'fieldList'=>$fieldList]);
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
	}
?>