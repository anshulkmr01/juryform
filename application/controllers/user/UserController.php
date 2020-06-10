<?php
	class UserController extends CI_Controller
	{
		function __construct(){
			parent::__construct();
			$this->load->model('UserModel');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}
		function loginUser()
		{if($this->session->userdata('userId'))
				return redirect('home');
			//load login Page
			$this->load->view('user/login');
		}

		function signupUser()
		{
			//load Signup Page
			$this->load->view('user/signup');
		}

		function recoverPassword()
		{
			//load recover Password Page
			$this->load->view('user/recoverPassword');
		}

		function newPassword(){
			if(!$this->session->userdata('userEmail') || !$this->session->userdata('recoveryKey')){
				$this->session->set_flashdata('warning',"Link Expired");
				return redirect('loginUser');
			}
			//Load New Password Page
			$this->load->view('user/newPassword');
		}

		function resetPassword($userEmail,$recoveryKey){
			if($this->UserModel->recoveryKey($userEmail,$recoveryKey)){
                return redirect('newPassword');
			}
		}

		//Setting new Password after recovering
		function setNewPassword(){
			if(!$this->session->userdata('userEmail') || !$this->session->userdata('recoveryKey')){
				$this->session->set_flashdata('warning',"Link Expired");
				return redirect('loginUser');
			}
			$this->form_validation->set_rules('newPassword','Password','required|trim',
												['required'=>'%s is required']);
			if($this->form_validation->run()){
				$newPassword = $this->input->post('newPassword');
				if($this->UserModel->setNewPassword($newPassword)){
					$this->session->set_flashdata('success','Your Password has changed successfully');
					return redirect('loginUser');
				}
				else{
					$this->session->set_flashdata('error','Password could not updated');
					return redirect('loginUser');
				}
			}
			else{
				$this->load->view('user/newPassword');
			}
		}


		function recoverPasswordSendEmail()
		{	
			$this->form_validation->set_rules('userEmail','Email','trim|required|valid_email',
												array('required'=>'%s is required'));
			if($this->form_validation->run()){
				$userEmail = $this->input->post('userEmail');
				if($this->UserModel->sendRecoverEmail($userEmail)){

				$this->session->set_flashdata('success',"A reset link has sent to your registered email");
				return redirect('recoverPassword');
				}
			}
			else{
				$this->load->view('user/recoverPassword');
			}
		}

		function userLogout()
		{

			$this->session->unset_userdata('userId');
			$this->session->set_flashdata('success',"Logout successfully");
			//load Signup Page
			return redirect('loginUser');
		}

		function registerUser()
		{

			$userdata = $this->input->post();
			$this->form_validation->set_rules('fullName','Name','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('userEmail','Email','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('telephone','Telephone Number','trim|required|regex_match[/^[0-9]{10}$/]',
											array('required' => '%s is Required','regex_match'=>'%s Format is incorrect'));
			$this->form_validation->set_rules('password','Password','trim|required',
											array('required' => '%s is Required'));

			if($this->form_validation->run()){
				if($this->UserModel->checkIfUserExist($userdata)){
				if($this->UserModel->addUser($userdata)){
				$this->session->set_flashdata('success','Registered successfully, Please check you email and complete verification');
				return redirect('loginUser');
			}
			else{
				$this->session->set_flashdata('error','Adding user to database failed');
				return redirect('loginUser');
			}
		}
		}
		else{
			$this->load->view('user/signup');
		}
		}

		function validateUser()
		{

			$userdata = $this->input->post();
			$this->form_validation->set_rules('userEmail','Email','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('userPassword','Password','trim|required',
											array('required' => '%s is Required'));
			if($this->form_validation->run()){
				$userId = $this->UserModel->validateUser($userdata);
				if($userId){

					//Set user Id into user session
					$this->session->set_userdata('userId',$userId);

					//Redired Authenticated to User Homepage
					return redirect('home');
			}
			else{
				$this->session->set_flashdata('error','Wrong password');
				return redirect('loginUser');
			}
		}
		else{
			$this->load->view('user/login');
		}
		}

		function verifyUser($userEmail,$recivedKey){
			if($this->UserModel->verifyUser($userEmail,$recivedKey)){
				$this->session->set_flashdata('success','Thaks for verifying your email, <br> You can login now');
				return redirect('loginUser');
			}
		}
		
		public function test_email()
			{
				 $this->load->library('email');

					$config['protocol']    = 'smtpout.secureserver.net';
					$config['smtp_host']    = 'localhost';
					$config['smtp_port']    = '25';
					$config['smtp_timeout'] = '600';

					$config['smtp_user']    = 'info@kennerlawgroup.com';    //Important
					$config['smtp_pass']    = 'Maria!$%';  //Important
                    
                    $config['charset']    = 'utf-8';
                    $config['newline']    = "\r\n";
                    $config['mailtype'] = 'html'; // or html
                    $config['validation'] = TRUE; // bool whether to validate email or not 

                    $this->email->initialize($config);
                    $this->email->set_mailtype("html"); 
                    $this->email->set_newline("\r\n");

                    $message = "test";
                    $message .= 'Dear User, <br><br>';
                    $message .= 'Please Click on Below button and Create a new password. <br><br>';

                    $this->email->from('vishal@unicorptravel.com', 'Jury Forms');
                    $this->email->to('anshulkmr01@gmail.com');

                    $this->email->subject('Recover your Jury Form Password, Please Verify your Email');
                    $this->email->message($message);
                    $query = $this->email->send();

                    echo "query messag".$query."<br>";

					echo $this->email->print_debugger();
			}

	}
?>