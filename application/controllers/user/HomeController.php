<?php
	/**
	 * 
	 */
	class HomeController extends CI_Controller
	{
		
		public function index(){
			//$this->load->helper('html');
			//$this->load->helper('globalCssJS');
			$this->load->view('homeView');
		}
		
	}
?>