<?php
	class AdminLogin extends CI_Controller{

public function __construct(){

		parent::__construct();
				$this->load->model('AdminModel');
				$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}
		public function index()
		{
			if($this->session->userdata('adminId'))
				return redirect('admin/select_website');
				$this->load->view('admin/login');
		}

		public function select_website($website = "")
		{
			if(!$this->session->userdata('adminId'))
				return redirect('admin');

			if ($website) {
				$this->session->set_userdata('admin_active_website',$website);
				return redirect('admin/'.$website);
			} else {		
				$this->load->view('admin/select_website');
			}
		}

		public function validate(){
			$this->form_validation->set_rules('adminemail','Email','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('adminpassword','Password','trim|required',
											array('required' => '%s is Required'));
			if($this->form_validation->run())
			{
				$adminemail = $this->input->post('adminemail');
				$adminpassword = $this->input->post('adminpassword');
				
				$adminId = $this->AdminModel->isValidate($adminemail,$adminpassword);
				if($adminId){
					//$this->load->library('session');
					// Setting the user id into user session
					$this->session->set_userdata('adminId',$adminId);
					$this->session->set_userdata('adminemail',$adminemail);
					return redirect('admin/select_website');
				}
				else{
					$this->session->set_flashdata('login_failed','Invalid Email or Password');
					return redirect('admin');
				}
			}
			else
			{
				$this->load->view('admin/login');
			}
		}

		public function logout(){
			if ($this->session->userdata('adminId')) {
				$this->session->unset_userdata('adminId');
			}
			$this->session->set_flashdata('logout_success',"Logout Successfully");
			return redirect ('admin');
		}

		public function changePassword(){

			$this->form_validation->set_rules('newAdminName','Admin Name','trim|required',
									array(['required'=>'%s is Required']));
			$this->form_validation->set_rules('newAdminPassword','Admin Password','trim|required',
									array(['required'=>'%s is Required']));

			if($this->form_validation->run()){
			$adminId = $this->input->post('adminId');
			$adminName = $this->input->post('newAdminName');
			$adminPassword = $this->input->post('newAdminPassword');

			
			if($this->AdminModel->changeAdminCredentials($adminId,$adminName,$adminPassword)){
					$this->session->set_flashdata('logout_success','Credentials Changed Succesfully, Login Again');
					$this->session->unset_userdata('adminId');
					return redirect('admin/AdminLogin');
			}
			}
			else{
				$this->load->view('admin/settings');
			}
		}

		public function recoverPassword(){
				$this->load->view('admin/recoverPassword');
		}
		public function sendPasswordEmail(){
			$this->form_validation->set_rules('adminEmail','Admin Email','trim|required',array(['required' => '$s is Required']));

				$adminemail = $this->input->post('adminEmail');

			if ($this->form_validation->run()) {
				$adminEmail = $this->input->post('adminemail');
				
				if($this->AdminModel->checkAdmin($adminemail)){
					echo "ok";
				}
				else{
					$this->session->set_flashdata('error','Unuthorised Email Address');
					return redirect('admin/AdminLogin/recoverPassword');
				}
			}

			else
			{
				$this->load->view('admin/recoverPassword');
			}
		}

}
?>