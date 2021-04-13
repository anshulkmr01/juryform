<?php
    class SetDeadline_UserModel extends CI_Model
    {
        function __construct()
        {
            $this->db = $this->load->database('set_deadline', TRUE);
            $this->db_ = $this->load->database('default', TRUE);
        }
            function getSelectedCases($caseId){
                $userId = $this->session->userdata('userId');
            return $this->db->where(['ID'=>$caseId,'userID'=>$userId])->get('cases')->row('title');
            }

            function getSelectedRuleData($ruleId){
                $rules = $this->db->where(['ID'=>$ruleId])->get('userrules')->result();
                    $rules[0]->sub = $this->ruleDeadlines($rules[0]->ID);
                return $rules;
            }
            
            public function ruleDeadlines($id){
                return $this->db->where(['rule_Id'=>$id])->get('userdeadlines')->result();       
            }


            function saveCase($caseData){

                $userId = $this->session->userData('userId');
                $query = $this->db->insert('savedcases',['userID'=>$userId,'caseID'=>$caseData['caseID'],
                    'caseTitle'=>$caseData['caseTitle'],
                    'motionDate'=>$caseData['motionDate'],
                    'ruleTitle'=>$caseData['ruleTitle'],'ruleDescription'=>$caseData['ruleDescription']]);
                $last_id = $this->db->insert_id();

                if($query):
                foreach ($caseData['deadlineData'] as $deadline) {
                    $data = explode ("/amg/", $deadline);
                   $this->saveDeadlines($data[0],$data[1],$data[2],$data[3],$last_id);
                }
                endif;
                return $query;
            }

            function saveDeadlines($deadlineTitle,$deadlineDesc,$deadlineDate,$deadlineGoogleID,$caseID){
                $query = $this->db->insert('saveddeadlinesforsavedcases',
                    ['caseID'=>$caseID,
                     'deadlineTitle'=>$deadlineTitle,
                     'deadlineDescription'=>$deadlineDesc,
                     'deadlineDate'=>$deadlineDate,
                     'deadlineGoogleID'=>$deadlineGoogleID]);
                return $query;
            }

            function deleteSavedCase($caseID){
                $deadlineGoogleID = $this->db->select('deadlineGoogleID')->where('caseID',$caseID)->get('saveddeadlinesforsavedcases')->result();
                if($this->db->delete('savedcases',['ID'=>$caseID]) && $this->db->delete('saveddeadlinesforsavedcases',['caseID'=>$caseID])){
                    return $deadlineGoogleID;   
                }

            }

            function deleteSavedDeadline($deadlineID){
                return $this->db->delete('saveddeadlinesforsavedcases',['ID'=>$deadlineID]);
            }

            function userCases(){
                $userId = $this->session->userData('userId');
                $cases = $this->db->where(['userId'=>$userId])->get('savedcases')->result();
                 if($cases):
                    $i = 0;
                    foreach ($cases as $case) {
                       $cases[$i]->caseDeadlines = $this->userSavedRulesDeadlines($case->ID);
                       $i++;
                    }
                endif;
                return $cases;
            }

            
            function userSavedRules($caseID){
                $userId = $this->session->userData('userId');
                $cases = $this->db->where(['userId'=>$userId, 'ID'=>$caseID])->get('savedcases')->result();
                if($cases):
                    $i = 0;
                    foreach ($cases as $case) {
                       $cases[$i]->caseDeadlines = $this->userSavedRulesDeadlines($case->ID);
                       $i++;
                    }
                endif;
                return $cases;
            }

            function userSavedRulesDeadlines($caseID){
                $cases = $this->db->where(['caseID'=>$caseID])->order_by("deadlineTitle", "asc")->get('saveddeadlinesforsavedcases')->result();
                return $cases;
            }


            function addCase($caseTitle){
                $userId = $this->session->userData('userId');
                return $this->db->insert('cases',['title'=>$caseTitle, 'userID'=>$userId]);
            }

            function getUserCases(){
                $userId = $this->session->userData('userId');

                return $this->db->where(['userID'=>$userId])->get('cases')->result();
            }

            function editCase($caseId,$caseTitle){
                $ok = $this->db->where('ID',$caseId)->update('cases',['title'=>$caseTitle]);
                if($ok){
                    $this->db->where('caseID',$caseId)->update('savedcases',['caseTitle'=>$caseTitle]);

                    $ID = $this->db->where('caseID',$caseId)->get('savedcases')->result_array();

                    foreach ($ID as $caseId) {
                        $deadlines[] = $this->db->where('caseID',$caseId['ID'])->get('saveddeadlinesforsavedcases')->result_array();
                    }
                    $deadlines = call_user_func_array('array_merge', $deadlines);

                }
                return $deadlines;
            }

            function deleteCase($caseId){
                return $this->db->delete('cases',['ID'=>$caseId]);
            }

            function addUserRule($ruleData){
                $userID = $this->session->userdata('userId');
                return $this->db->insert('userrules',['userID'=>$userID,'title'=>$ruleData['ruleTitle'],'description'=>$ruleData['ruleDescription']]);
            }


            function editUserRule($ruleUpdatedData){
                return $this->db->where('ID',$ruleUpdatedData['ruleId'])
                ->update('userrules'
                    ,['title'=>$ruleUpdatedData['ruleUpdatedTitle']
                    ,'description'=>$ruleUpdatedData['ruleDesc']]);
            }


            function deleteUserRule($ruleId){
                if($this->db->delete('userdeadlines',['rule_Id'=>$ruleId]) && 
                $this->db->delete('userrules',['ID'=>$ruleId])){
                    return true;
                }
            }

            function deleteUser($userId){
                return $this->db_->delete('users',['id'=>$userId]);
            }

            function dublicateUserRule($ruleId){

                $ruleData['rule'] = $this->db->where('ID',$ruleId)->get('userrules')->result();
                $ruleData['deadlines'] = $this->db->where('rule_Id',$ruleId)->get('userdeadlines')->result();
                $userID = $this->session->userdata('userId');
                foreach ($ruleData['rule'] as $rule) {
                    $this->db->insert('userrules',['title'=>$rule->title,'userID'=>$userID,'description'=>$rule->description]);
                    $last_id = $this->db->insert_id();
                }//endif

                foreach ($ruleData['deadlines'] as $deadline) {
                    $this->db->insert('userdeadlines',['title'=>$deadline->title,'description'=>$deadline->description,
                        'deadline_days'=>$deadline->deadline_days,'day_type'=>$deadline->day_type,
                        'rule_Id'=>$last_id]);
                }//endif
                return true;
            }


            function importRule($ruleId){
                $existingRuleId = $this->db->where('ID',$ruleId)->get('userrules')->row('ID');
               // if($existingRuleId != $ruleId){

                $ruleData['rule'] = $this->db->where('ID',$ruleId)->get('rules')->result();
                $ruleData['deadlines'] = $this->db->where('rule_Id',$ruleId)->get('deadlines')->result();

                $userID = $this->session->userdata('userId');
                foreach ($ruleData['rule'] as $rule) {
                    $this->db->insert('userrules',['userID'=>$userID,
                            'title'=>$rule->title,'description'=>$rule->description,'rule_id'=>$ruleId]);
                    $last_id = $this->db->insert_id();
                }//endif

                foreach ($ruleData['deadlines'] as $deadline) {
                    $this->db->insert('userdeadlines',['title'=>$deadline->title,'description'=>$deadline->description,
                        'deadline_days'=>$deadline->deadline_days,'day_type'=>$deadline->day_type,
                        'rule_Id'=>$last_id]);
                }//endif
                    return true;
               // }
                $this->session->set_flashdata("error", "Rule already Exist in Saved Rules");
                return false;
            }

            function getUserRules(){
                $userID = $this->session->userdata('userId');
                return $this->db->where('userID',$userID)->get('userrules')->result();
            }

            function getDeadlines($ruleId){
                $ruleData[] = $this->db->where(['ID'=>$ruleId])->get('userrules')->row('title');
                
                $this->db->order_by('deadline_days', 'ASC');
                $ruleData[] = $this->db->where(['rule_id'=>$ruleId])->get('userdeadlines')->result();
                return $ruleData;
            }

            function saveRefreshToken($refreshToken){
            $this->db_->where('id', $this->session->userData('userId'))->update('users',['googleRefreshToken'=>$refreshToken]);
            }

            function editUserDeadline($deadlineUpdatedData){
            return $this->db->where('ID',$deadlineUpdatedData['deadlineId'])
            ->update('userdeadlines'
                ,['title'=>$deadlineUpdatedData['deadlineUpdatedTitle']
                ,'description'=>$deadlineUpdatedData['deadlineDesc']
                ,'deadline_days'=>$deadlineUpdatedData['deadlineUpdatedDays']
                ,'day_type'=>$deadlineUpdatedData['dayUpdatedType']]);
            }
            
            function addUserDeadline($deadlineData){
                return $this->db->insert('userdeadlines',['title'=>$deadlineData['deadlineTitle'],'description'=>$deadlineData['deadlineDescription'], 'deadline_days'=>$deadlineData['deadLineDays'],'day_type'=>$deadlineData['dayType'],'rule_Id'=>$deadlineData['ruleId']]);
            }

            function deleteUserDeadline($deadlineId){
                return $this->db->delete('userdeadlines',['ID'=>$deadlineId]);
            }

            function addHolidays($holidayData, $userId){
                 return $this->db->insert('userholidays',['userId'=>$userId,'title'=>$holidayData['holidayTitle'], 'date'=>$holidayData['holidayDate']]);
            }

            function deleteHoliday($holidayData, $userId){
                  return $this->db->delete('userholidays',['ID'=>$holidayData, 'userId'=> $userId]);
            }

            function getUserHolidays($userId){

                $this->db->order_by("date", "asc");
                return $this->db->where('userId',$userId)->get('userholidays')->result();
            }

            function searchCasesForDate($dateForCases){
                $parent =  $this->db->where('deadlineDate',$dateForCases)->get('saveddeadlinesforsavedcases')->result();
                $i=0;
                foreach ($parent as $p_cat) {
                    $parent[$i]->sub = $this->searchCaseforDeadline($p_cat->caseID);
                    $i++;
                }
                return $parent;
            }

            function searchCaseforDeadline($id){
            $child = $this->db->where('ID', $id)->get('savedcases');
            $categories = $child->result();
            return $categories;       
        }

    }
?>