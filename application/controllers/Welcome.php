<?php
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
			return redirect('jury_forms');
		}

		public function select_website($website = "")
		{
			if ($website) {
				$this->session->set_userdata('active_website',$website);
				return redirect($website);
			} else {		
				$this->load->view('select_website');
			}
		}
	}
?>