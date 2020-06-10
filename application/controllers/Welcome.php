<?php
	/**
	 * 
	 */
	class Welcome extends CI_Controller
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
		$fieldList = $this->AdminModel->AllFieldList();

		$this->load->view('user/homepage',['categoriesData'=>$queryResult,'fieldList'=>$fieldList]);
		}
	}
?>