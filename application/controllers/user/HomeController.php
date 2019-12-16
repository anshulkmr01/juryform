<?php
	/**
	 * 
	 */
	class HomeController extends CI_Controller
	{
		
		public function index(){

			$this->load->model('AdminModel');
			$queryResult = $this->AdminModel->getCategories();

			$this->load->view('user/homepage',['categoriesData'=>$queryResult]);
		}
		
	}
?>