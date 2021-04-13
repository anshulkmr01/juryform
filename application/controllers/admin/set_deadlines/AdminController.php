<?php
	class AdminController extends CI_Controller
	{
		function __construct(){
			parent::__construct();

			$this->load->model('SetDeadline_AdminModel', 'AdminModel');

			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');			
			if(!$this->session->userdata('adminId')){
				$this->session->set_flashdata('warning','Login to Continue');
				return redirect('admin');
			}
		}
		
		function index()
		{
			//$this->load->view('admin/dashboard');
			return redirect('admin/set_deadlines');
		}
		
		function rules()
		{
			//Loading Rule Page
			$rules = $this->AdminModel->getRules();
				$this->load->view('admin/set_deadlines/rules',['rules'=>$rules]);
		}

		function deadline($ruleId)
		{
			//Loading Rule Page
			$this->session->set_userdata('ruleId',$ruleId);
			$rules = $this->AdminModel->getDeadlines($ruleId);
				$this->load->view('admin/set_deadlines/deadline',['deadlines'=>$rules]);
		}

		function addRule(){
				$ruleData = $this->input->post();
				if($this->AdminModel->addRule($ruleData)){
					$this->session->set_flashdata('success',"Rule Added Successfully");
					return redirect('admin/set_deadlines');
				}
				else{
					$this->session->set_flashdata('error',"Adding Rule Failed");
					return redirect('admin/set_deadlines');
			}

		}

		function addDeadline(){

			if($ruleId = $this->session->userdata('ruleId'));
				$deadlineData = $this->input->post();
				if($this->AdminModel->addDeadline($deadlineData)){
					$this->session->set_flashdata('success',"Deadline Added Successfully");
					return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
				}
				else{
					$this->session->set_flashdata('error',"Adding Deadline Failed");
					return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
				}

		}

		function editRule(){
			$ruleUpdatedData = $this->input->post();
			if($this->AdminModel->editRule($ruleUpdatedData)){
				$this->session->set_flashdata('success', 'Cases Updated Successfully');
		        return redirect('admin/set_deadlines');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Updating Case');
		        return redirect('admin/set_deadlines');
				}
		}

		function editDeadline(){

			if($ruleId = $this->session->userdata('ruleId'));
			$deadlineUpdatedData = $this->input->post();
			if($this->AdminModel->editDeadline($deadlineUpdatedData)){
				$this->session->set_flashdata('success', 'Deadline Updated Successfully');
					return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
			}
			else{
				$this->session->set_flashdata('error', 'Error in Updating Deadline');
					return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
				}
		}


		function deleteRule($ruleId){
			if($this->AdminModel->deleteRule($ruleId)){
				$this->session->set_flashdata('success', 'Rule Deleted Successfully');
		        return redirect('admin/set_deadlines');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Deletion Case');
		        return redirect('admin/set_deadlines');
			}
		}

		function dublicateRule($ruleId){

			if($this->AdminModel->dublicateRule($ruleId)){
				$this->session->set_flashdata('success', 'Rule cloned Successfully');
		        return redirect('admin/set_deadlines');
			}
			else{
				$this->session->set_flashdata('error', 'Error');
		        return redirect('admin/set_deadlines');
			}
		}

		function deleteDeadline($deadlineId){
			if($ruleId = $this->session->userdata('ruleId'));
			if($this->AdminModel->deleteDeadline($deadlineId)){
				$this->session->set_flashdata('success', 'Deadline Deleted Successfully');
		        return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
			}
			else{
				$this->session->set_flashdata('error', 'Error in Deletion Deadline');
		        return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
			}
		}


		function deleteSelectedRules(){
			$ruleIds = $this->input->post('ruleIds');
			if($this->input->post('deleteRules')){

			foreach ($ruleIds as $ruleId) {
				if(!$this->AdminModel->deleteRule($ruleId)){
					$this->session->set_flashdata('error', 'Error in Deletion Rule');
			        return redirect('admin/set_deadlines');
				}
			}
				$this->session->set_flashdata('success', 'Rules Deleted Successfully');
		        return redirect('admin/set_deadlines');
			}

			// for multiple submit button
			if($this->input->post('dublicateRules')){

			foreach ($ruleIds as $ruleId) {
				if(!$this->AdminModel->dublicateRule($ruleId)){
				$this->session->set_flashdata('error', 'Error in Cloning');
		        return redirect('admin/set_deadlines');
				}
			}
			$this->session->set_flashdata('success', 'Rule cloned Successfully');
		   	return redirect('admin/set_deadlines');
			}
		}

		function deleteSelectedDeadlines(){
			if($ruleId = $this->session->userdata('ruleId'));
			$deadlineIds = $this->input->post('deadlineIds');

			foreach ($deadlineIds as $deadline) {
				if(!$this->AdminModel->deleteDeadline($deadline)){
					$this->session->set_flashdata('error', 'Error in Deletion Deadline');
		        return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
				}
			}
				$this->session->set_flashdata('success', 'Deadline Deleted Successfully');
		        return redirect('admin/set_deadlines/AdminController/deadline/'.$ruleId);
		}

		function users()
		{
			//Loading users Page
			$users = $this->AdminModel->getUsers();
			$this->load->view('admin/set_deadlines/users',['users'=>$users]);
		}

		function deleteUser($userId){
		if($this->AdminModel->deleteUser($userId)){
				$this->session->set_flashdata('success', 'User Deleted Successfully');
		        return redirect('admin/set_deadlines/users');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Deletion User');
		        return redirect('admin/set_deadlines/users');
			}			
		}

		function deleteSelectedUser(){

			$userIds = $this->input->post('userIds');

			foreach ($userIds as $userId) {
				if(!$this->AdminModel->deleteUser($userId)){
					$this->session->set_flashdata('error', 'Error in Deletion User');
			        return redirect('admin/set_deadlines/users');
				}
			}
				$this->session->set_flashdata('success', 'Users Deleted Successfully');
		        return redirect('admin/set_deadlines/users');
		}

		function userProfile($userId){
			if($this->AdminModel->ifUserExist($userId)){
			$this->session->set_userdata('userId',$userId);
			return redirect(base_url('set_deadlines'));
			}
			else{
				$this->session->set_flashdata('error', 'No user found');
		        return redirect('users');
			}			
		}

		function adminSettings()
		{
			$this->load->view('admin/set_deadlines/adminSettings');
		}

		function adduser()
		{
			$userdata = $this->input->post();
			$this->form_validation->set_rules('userName','Name','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('userEmail','Email','trim|required',
											array('required' => '%s is Required'));
			if($this->form_validation->run()){
				if($this->AdminModel->addUser($userdata)){
				$this->session->set_flashdata('success','User Added Successfully');
				return redirect('users');
			}
			else{
				return redirect('users');
			}
		}
		else{
			$this->session->set_flashdata('modal','adduser');
			$this->load->view('admin/set_deadlines/users');
		}
		}
}
?>