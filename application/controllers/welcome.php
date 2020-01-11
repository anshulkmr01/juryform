<?php
	/**
	 * 
	 */
	class Welcome extends CI_Controller
	{
		
		public function index(){
			return redirect('user/HomeController');
		}
	}
?>