<?php
	/**
	 * 
	 */
	class HomeController extends CI_Controller
	{
		/*
		
		public function index(){

			$this->load->model('AdminModel');
			$queryResult = $this->AdminModel->getCategories();

			$this->load->view('user/homepage',['categoriesData'=>$queryResult]);
		}
		*/

		public function index(){

	    $this->load->model('userModel');
		$queryResult = $this->userModel->get_categories();
		$this->load->view('user/homepage',['categoriesData'=>$queryResult]);
	    }
	}
?>