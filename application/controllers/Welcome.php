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