<?php
	class UserSignup extends CI_Controller{
		public function signup()
		{
			$this->load->view('user/signup');
		}

		public function test(){

			$this->form_validation->set_rules('username','User Name','trim|required',
											array('required' => '%s is Must'));
			$this->form_validation->set_rules('userpassword','Password','trim|required',
											array('required' => '%s is Must'));

			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

			if($this->form_validation->run())
			{
				echo "validated Successfully";
			}

			else
			{
				$this->load->view('user/signup');
			}
		}
}
?>