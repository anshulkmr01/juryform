<?php
	/**
	 * 
	 */
	class Welcome extends CI_Controller
	{
		
		public function index(){
			
	    $this->load->model('userModel');
		$queryResult = $this->userModel->get_categories();

		$this->load->model('AdminModel');
		$fieldList = $this->AdminModel->fieldList();

		$this->load->view('user/homepage',['categoriesData'=>$queryResult,'fieldList'=>$fieldList]);
		}
	}
?>