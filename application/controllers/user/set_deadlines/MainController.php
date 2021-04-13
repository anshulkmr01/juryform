<?php
	/**
	 * 
	 */
	class MainController extends CI_Controller
	{
		function __construct(){
			parent::__construct();
			if(!$this->session->userdata('userId'))
				return redirect('loginUser');

			if(!isset($_SESSION['access_token'])) {
					$this->session->set_flashdata('warning','Connect Google Account to Continue');
					 return redirect('set_deadlines/settings');
			}
			
			$this->load->model('SetDeadline_AdminModel', 'AdminModel');
			$this->load->model('SetDeadline_UserModel', 'UserModel');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}

		function importRules()
		{
			//load rules by admin on User Homepage
			$rules = $this->AdminModel->getRulesData();
			$userRules = $this->UserModel->getUserRules();

			$this->load->view('user/set_deadlines/homepage',['rules'=>$rules,'userrules'=>$userRules]);
			//return redirect('set_deadlines/user_cases');
	    }

	    function userRules(){
			$userRules = $this->UserModel->getUserRules();
			$this->load->view('user/set_deadlines/userRules',['rules'=>$userRules]);
	    }

		public function calendar($year = NULL , $month = NULL)
			{
				$data['calender'] = $this->UserModel->getcalender($year , $month);
				$this->load->view('user/set_deadlines/calview', $data);
			}

	    function userCases(){
	    	//Load Listed Cases in User Home Page
			$cases = $this->UserModel->getUserCases();
			if($cases){
				$this->load->view('user/set_deadlines/cases',['cases'=>$cases]);
			}
			else{
	    	$this->load->view('user/set_deadlines/cases');
			}
	    }


		function listedRules($caseId)
		{
			//Loading Rule Page
			$rules = $this->UserModel->getUserRules();
			$holidays = $this->UserModel->getUserHolidays($this->session->userData('userId'));
			$rules['caseId'] = $caseId;
			$rules['caseTitle'] = $caseTitle = $this->input->get('case');
				$this->load->view('user/set_deadlines/rules',['rules'=>$rules,'holidays'=>$holidays]);
		}
		function editUserRule(){
			$this->form_validation->set_rules('ruleUpdatedTitle','Title','required');
			$this->form_validation->set_rules('ruleDesc','Rules','required');
			if($this->form_validation->run()){
			$ruleUpdatedData = $this->input->post();

			if($this->UserModel->editUserRule($ruleUpdatedData)){
				$this->session->set_flashdata('success', 'Rule Updated Successfully');
		        return redirect('set_deadlines/user_rules');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Updating Rule');
		        return redirect('set_deadlines/user_rules');
				}
			}
			else{
				$this->session->set_flashdata('error', 'Fill Required Fields');
		        return redirect('set_deadlines/user_rules');
			}
		}

		function dublicateRule($ruleId){

			if($this->UserModel->dublicateUserRule($ruleId)){
				$this->session->set_flashdata('success', 'Rule cloned Successfully');
		        return redirect('set_deadlines/user_rules');
			}
			else{
				$this->session->set_flashdata('error', 'Error');
		        return redirect('set_deadlines/user_rules');
			}
		}

		function searchCasesForDate(){
			$dateForCases = $this->input->post('dateForCases');
			$dateForCases = " ".date('m/d/Y',strtotime($dateForCases));
			$deadlinesData = $this->UserModel->searchCasesForDate($dateForCases);
			$this->load->view('user/set_deadlines/deadlineSearchResult',['deadlines'=>$deadlinesData]);
		}

		function addUserRule(){
			$this->form_validation->set_rules('ruleTitle','Title','required');
			$this->form_validation->set_rules('ruleDescription','Rules','required');
			if($this->form_validation->run()){
				$ruleData = $this->input->post();
				if($this->UserModel->addUserRule($ruleData)){
					$this->session->set_flashdata('success',"Rule Added Successfully");
					return redirect('set_deadlines/user_rules');
				}
				else{
					$this->session->set_flashdata('error',"Adding Rule Failed");
					return redirect('set_deadlines/user_rules');
				}
			}
			else{
				$this->session->set_flashdata('error',"Fields Can't be empty");
				return redirect('set_deadlines/user_rules');
			}

		}

		function deleteUserRule($ruleId){
			if($this->UserModel->deleteUserRule($ruleId)){
				$this->session->set_flashdata('success', 'Rule Deleted Successfully');
		        return redirect('set_deadlines/user_rules');
			}
			else{
		        return redirect('set_deadlines/user_rules');
			}
		}

		function addHoliday(){
			$this->form_validation->set_rules('holidayTitle','Holiday Name','required',array('required'=>'%s is required'));
	    	$this->form_validation->set_rules('holidayDate','Date','required',array('required'=>'%s is required'));
	    	if($this->form_validation->run()){
	    		$holidayData = $this->input->post();
	    		if($this->UserModel->addHolidays($holidayData, $this->session->userData('userId'))){

	    			$this->session->set_flashdata('success', 'Holiday Added');
	    			return redirect('set_deadlines/settings');
	    		}
	    		else{

	    			$this->session->set_flashdata('error', 'adding Holidays Failed');
	    			return redirect('set_deadlines/settings');
	    		}

	    	}
	    	else{
	    		$this->session->set_flashdata('error', 'Fill Required fields');
	    		return redirect('set_deadlines/settings');
	    	}
		}

		function deleteHoliday($holidayId){

	    		if($this->UserModel->deleteHoliday($holidayId, $this->session->userData('userId'))){
	    			$this->session->set_flashdata('success', 'Holiday deleted');
	    			return redirect('set_deadlines/settings');
	    		}
	    		else
	    		{
	    			$this->session->set_flashdata('error', 'Error in Holiday deletion');
	    			return redirect('set_deadlines/settings');
	    		}
		}



		function editUserDeadline(){
			if($ruleId = $this->session->userdata('ruleId'));
			$this->form_validation->set_rules('deadlineUpdatedTitle','Title','required');
			$this->form_validation->set_rules('deadlineDesc','Description','required');
			$this->form_validation->set_rules('deadlineUpdatedDays','Days','required');
			if($this->form_validation->run()){
			$deadlineUpdatedData = $this->input->post();
			if($this->UserModel->editUserDeadline($deadlineUpdatedData)){
				$this->session->set_flashdata('success', 'Deadline Updated Successfully');
					return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
			}
			else{
				$this->session->set_flashdata('error', 'Error in Updating Deadline');
					return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
				}
			}
				$this->session->set_flashdata('error', 'Fill Required Fields');
					return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
		}

		function addUserDeadline(){

			if($ruleId = $this->session->userdata('ruleId'));
			$this->form_validation->set_rules('deadlineTitle','Title','required');
			$this->form_validation->set_rules('deadlineDescription','Description','required');
			$this->form_validation->set_rules('deadLineDays','Days','required');
			if($this->form_validation->run()){
				$deadlineData = $this->input->post();
				if($this->UserModel->addUserDeadline($deadlineData)){
					$this->session->set_flashdata('success',"Deadline Added Successfully");
					return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
				}
				else{
					$this->session->set_flashdata('error',"Adding Deadline Failed");
					return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
				}
			}
			else{
				$this->session->set_flashdata('error',"Fields Can't be empty");
				return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
			}

		}

		function deleteUserDeadline($deadlineId){
			if($ruleId = $this->session->userdata('ruleId'));
			if($this->UserModel->deleteUserDeadline($deadlineId)){
				$this->session->set_flashdata('success', 'Deadline Deleted Successfully');
		        return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
			}
			else{
				$this->session->set_flashdata('error', 'Error in Deletion Deadline');
		        return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
			}
		}

		
		function deleteSelectedUserDeadlines(){
			if($ruleId = $this->session->userdata('ruleId'));
			$deadlineIds = $this->input->post('deadlineIds');

			foreach ($deadlineIds as $deadline) {
				if(!$this->UserModel->deleteUserDeadline($deadline)){
					$this->session->set_flashdata('error', 'Error in Deletion Deadline');
		        return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
				}
			}
				$this->session->set_flashdata('success', 'Deadline Deleted Successfully');
		        return redirect('set_deadlines/user_rules/userDeadlines/'.$ruleId);
		}

	    function changePassword(){
	    	$this->form_validation->set_rules('currentPassword','Current Password','required',array('required'=>'%s is required'));
	    	$this->form_validation->set_rules('newPassword','New Password','required',array('required'=>'%s is required'));
	    	if($this->form_validation->run()){
	    		$changePasswordData = $this->input->post();
	    		
	    		if($this->UserModel->changePassword($changePasswordData)){
	    			$this->session->set_flashdata('success', 'Password Successfully Changed. Login Again');
	    			$this->session->unset_userdata('userId');
	    			return redirect('loginUser');
	    		}
	    		else {
	    			$this->session->set_flashdata('error','current password is incorrect');
	    			$userRules = $this->UserModel->getUserRules();
					$this->load->view('user/set_deadlines/userProfile');
	    		}
	    	}
	    	else{
	    		$userRules = $this->UserModel->getUserRules();
				$this->load->view('user/set_deadlines/userProfile');
	    	}
	    }


		function calculateDays(){
			$rulesData = $this->input->post();

			$this->form_validation->set_rules('motionDate','Trigger Date','required',array('required'=>'%s is required'));
			$this->form_validation->set_rules('ruleIds[]','Rule','required',array('required'=>'Select a %s'));
			if($this->form_validation->run()){

			$caseId = $rulesData['caseId'];
			$motionDate = $rulesData['motionDate'];
			$ruleIDs = $rulesData['ruleIds'];

			$userId = $this->session->userData('userId'); //Getting User Id form Session
			$holidays = $this->UserModel->getUserHolidays($userId); // Getting Holiday dates

			$caseTitle = $this->UserModel->getSelectedCases($caseId); // Getting Case Title from case Id

			foreach ($ruleIDs as $ruleID) {
				$rulesFromDB[] = $this->UserModel->getSelectedRuleData($ruleID); // Getting selected rules using ID
			}
				$rulesFromDB['caseTitle'] = $caseTitle;
				$rulesFromDB['motionDate'] = $motionDate;
				$rulesFromDB['caseId'] = $caseId;

				$this->load->view('user/set_deadlines/reviewCase',['caseData'=>$rulesFromDB,'holidays'=>$holidays]);
			}
			else{
				$rules = $this->UserModel->getUserRules();
				$rules['caseId'] = $caseId;
				$this->load->view('user/set_deadlines/rules',['rules'=>$rules]);
			}
		}

		function importRule($ruleId){
			if($this->UserModel->importRule($ruleId)){
				$this->session->set_flashdata('success','Rule Added')	;
			}
			return redirect('set_deadlines/import_rules');
		}

		function saveCase(){
			$caseData = $this->input->post();
			
			$j = 0;
			foreach ($caseData['deadlineData'] as $value) {				
					$caseData['deadlineData'][$j] = $caseData['deadlineTitle'][$j]."/amg/".$caseData['deadlineData'][$j];
					$j++;
				}	
			unset($caseData['deadlineTitle']);
			$i = 0;
			foreach ($caseData['deadlineData'] as $deadlines) {
					 $deadlines = explode ("/amg/", $deadlines);
					$caseData['deadlineData'][$i] .= '/amg/'.$this->saveEventInGoogle($caseData['caseTitle']. " [".$deadlines[0]."]",$deadlines[1],date('Y-m-d', strtotime($deadlines[2])));
					$i++;
					}
					if($this->UserModel->saveCase($caseData)){
						$this->session->set_flashdata('success','Case Populated in profile');
						return redirect('set_deadlines/user_cases');
					}
					else{
						$this->session->set_flashdata('warning','Data have set in calendar but not in database');
						return redirect('set_deadlines/user_cases');
					}
		}

		function saveEventInGoogle($eventTitle,$eventDesc,$eventDate){
			try {

				// Create event on primary calendar
				$event_id = $this->google->CreateCalendarEvent('primary', $eventTitle,$eventDesc,1,$eventDate);
				return $event_id;
				echo json_encode([ 'event_id' => $event_id ]);
				exit();
			}
			catch(Exception $e) {
				header('Bad Request', true, 400);
			    echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() ));
			}
		}
		
		function deleteSavedCase($caseID){
			if($deadlineGoogleID = $this->UserModel->deleteSavedCase($caseID)){

				// deleting event on the primary calendar
				$calendar_id = 'primary';
				foreach ($deadlineGoogleID as $event_id) {
					// Event on primary calendar
					$this->google->DeleteCalendarEvent($event_id->deadlineGoogleID, $calendar_id);
				}

				$this->session->set_flashdata("success","Case Deleted Successfully");
				return redirect('set_deadlines/see_all_events');
			}
			else{
				$this->session->set_flashdata("error","No deadline GoogleId Found");
				return redirect('set_deadlines/see_all_events');
			}
		}

		function deleteSavedDeadline($deadlineID,$googleID =''){

				if($this->UserModel->deleteSavedDeadline($deadlineID)){
				// deleting event on the primary calendar
				$calendar_id = 'primary';
					// Event on primary calendar
				$this->google->DeleteCalendarEvent($googleID, $calendar_id);

				$this->session->set_flashdata("success","Deadline Deleted Successfully");
				return redirect('set_deadlines/see_all_events');
			}
			else{
				$this->session->set_flashdata("error","Deadline could not deleted");
				return redirect('set_deadlines/see_all_events');

			}
		}

		function populatedCase(){
			$cases = $this->UserModel->userCases();
			$this->load->view('user/set_deadlines/populatedCase',['cases'=>$cases]);
		}

		function populatedRules($caseID){
			$userData = $this->UserModel->userSavedRules($caseID);
			$this->load->view('user/set_deadlines/populatedRules',['rulesData'=>$userData]);
		}


		////////////////////////////////////////////////////////////////////
		// Adding Case by User


		function addCase(){
			$this->form_validation->set_rules('caseTitle','Case Title','required');
			if ($this->form_validation->run())
			{
				$title = $this->input->post('caseTitle');
				if($this->UserModel->addCase($title)){
					$this->session->set_flashdata('success', 'Cases Added Successfully');
			        return redirect('set_deadlines/user_cases');
				} else {
					$this->session->set_flashdata('error', 'An Error Occured');
			        return redirect('set_deadlines/user_cases');
				}
			}
			else
			{
				$this->session->set_flashdata('error',"Fields Can't be empty");
			    return redirect('set_deadlines/user_cases');
			}
		}

		function editCase(){
			$caseId = $this->input->post('caseId');
			$caseTitle = $this->input->post('caseTitle');
			$caseData['caseName'] = $caseTitle;
			$caseData['deadlineData'] = $this->UserModel->editCase($caseId,$caseTitle);
			if($caseData['deadlineData']){
				foreach ($caseData['deadlineData'] as $deadlines) {
					$this->updateEventCaseInGoogleCalendar($deadlines,$caseData['caseName']);
				}
				$this->session->set_flashdata('success', 'Cases Updated Successfully');
		        return redirect('set_deadlines/user_cases');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Updating Case');
		        return redirect('set_deadlines/user_cases');
			}
		}


		function updateEventCaseInGoogleCalendar($caseData,$caseTitle)
		{
			// updating event on the primary calendar
			$calendar_id = 'primary';

			// Event on primary calendar
			$event_id = $caseData['deadlineGoogleID'];

			$event_title = $caseTitle." [".$caseData['deadlineTitle']."]";

			$event_description = $caseData['deadlineDescription'];

			// Full day event
			$full_day_event = 1; 
			$event_time = [ 'event_date' => date("Y-m-d", strtotime($caseData["deadlineDate"]))];
			//$event_time = date('Y-m-d', strtotime($caseData['deadlineDate']));
			$this->google->UpdateCalendarEvent($event_id, $calendar_id, $event_title, $event_description, $full_day_event, $event_time);
		}


		function deleteCase($caseId){
			if($this->UserModel->deleteCase($caseId)){
				$this->session->set_flashdata('success', 'Cases Deleted Successfully');
		        return redirect('set_deadlines/user_cases');
			}
			else{
				$this->session->set_flashdata('error', 'Error in Deletion Case');
		        return redirect('set_deadlines/user_cases');
			}
		}

		function deleteSelectedCases(){
			$caseIds = $this->input->post('caseIds');

			foreach ($caseIds as $caseId) {
				if(!$this->UserModel->deleteCase($caseId)){
					$this->session->set_flashdata('error', 'Error in Deletion Case');
			        return redirect('set_deadlines/user_cases');
				}
			}
				$this->session->set_flashdata('success', 'Case Deleted Successfully');
		        return redirect('set_deadlines/user_cases');
		}


		function deleteSelectedUserRules(){
			$ruleIds = $this->input->post('ruleIds');
			if($this->input->post('deleteRules')){

			foreach ($ruleIds as $ruleId) {
				if(!$this->UserModel->deleteUserRule($ruleId)){
					$this->session->set_flashdata('error', 'Error in Deletion Rule');
			        return redirect('set_deadlines/user_rules');
				}
			}
				$this->session->set_flashdata('success', 'Rules Deleted Successfully');
		        return redirect('set_deadlines/user_rules');
			}

			if($this->input->post('dublicateRules')){

			foreach ($ruleIds as $ruleId) {
				if(!$this->UserModel->dublicateUserRule($ruleId)){
				$this->session->set_flashdata('error', 'Error in Cloning');
		        return redirect('set_deadlines/user_rules');
				}
			}
			$this->session->set_flashdata('success', 'Rule cloned Successfully');
		   	return redirect('set_deadlines/user_rules');
			}
		}

		

		function googleDisconnect(){
			$this->google->disconnect();
			return redirect('set_deadlines/settings');
		}

		//Adding Cases by user
		//////////////////////////////////////////////////

		//////////////////////////////////////////////////
		// Adding Deadline by User

		function userDeadlines($ruleId)
		{
			//Loading Rule Page
			$this->session->set_userdata('ruleId',$ruleId);
			$rules = $this->UserModel->getDeadlines($ruleId);
			$this->load->view('user/set_deadlines/userDeadlines',['deadlines'=>$rules]);
		}

		function destroySession(){
			$this->session->sess_destroy();
		}

	}
?>