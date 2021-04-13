<?php

	class SetDeadline_AdminModel extends CI_Model
	{
		function __construct()
		{
			$this->db = $this->load->database('set_deadline', TRUE);
			$this->db_ = $this->load->database('default', TRUE);
		}

		////////////////////////////////////////////////////////////// Jury Forms
		function getUsers(){
			return $this->db_->get('users')->result();
		}

		function deleteUser($userId){
			return $this->db_->delete('users',['id'=>$userId]);
		}

		function ifUserExist($userId){
			return $this->db_->where(['id'=>$userId])->get('users')->row();
		}
		////////////////////////////////////////////////////////////// Jury Forms
		
		function addRule($ruleData){
			return $this->db->insert('rules',['title'=>$ruleData['ruleTitle'],'description'=>$ruleData['ruleDescription']]);
		}

		function addDeadline($deadlineData){
			return $this->db->insert('deadlines',['title'=>$deadlineData['deadlineTitle'],'description'=>$deadlineData['deadlineDescription'], 'deadline_days'=>$deadlineData['deadLineDays'],'day_type'=>$deadlineData['dayType'],'rule_Id'=>$deadlineData['ruleId']]);
		}

		function getRules(){
			return $this->db->get('rules')->result();
		}


        public function getRulesData(){
	        $parent = $this->db->get('rules');
	        $categories = $parent->result();
	        $i=0;
	        foreach($categories as $p_cat){
	            $categories[$i]->sub = $this->ruleDeadlines($p_cat->ID);
	            $i++;
	        }
	        return $categories;
   		 }

	    public function ruleDeadlines($id){
	        $child = $this->db->where('rule_id', $id)->get('deadlines');
	        $categories = $child->result();
	        return $categories;       
	    }

		function getDeadlines($ruleId){
			return $this->db->where(['rule_id'=>$ruleId])->get('deadlines')->result();
		}

		function deleteRule($ruleId){

			if($this->db->delete('deadlines',['rule_Id'=>$ruleId]) && $this->db->delete('rules',['ID'=>$ruleId])){
				return true;
			}
		}

		function dublicateRule($ruleId){
                $ruleData['rule'] = $this->db->where('ID',$ruleId)->get('rules')->result();
                $ruleData['deadlines'] = $this->db->where('rule_Id',$ruleId)->get('deadlines')->result();

                foreach ($ruleData['rule'] as $rule) {
                    $this->db->insert('rules',['title'=>$rule->title,'description'=>$rule->description]);
                    $last_id = $this->db->insert_id();
                }//endif

                foreach ($ruleData['deadlines'] as $deadline) {
                    $this->db->insert('deadlines',['title'=>$deadline->title,'description'=>$deadline->description,
                        'deadline_days'=>$deadline->deadline_days,'day_type'=>$deadline->day_type,
                        'rule_Id'=>$last_id]);
                }//endif
                return true;
		}

		function deleteDeadline($deadlineId){
			return $this->db->delete('deadlines',['ID'=>$deadlineId]);
		}

		function editRule($ruleUpdatedData){
			return $this->db->where('ID',$ruleUpdatedData['ruleId'])
			->update('rules'
				,['title'=>$ruleUpdatedData['ruleUpdatedTitle']
				,'description'=>$ruleUpdatedData['ruleDesc']]);
		}

		function editDeadline($deadlineUpdatedData){
			return $this->db->where('ID',$deadlineUpdatedData['deadlineId'])
			->update('deadlines'
				,['title'=>$deadlineUpdatedData['deadlineUpdatedTitle']
				,'description'=>$deadlineUpdatedData['deadlineDesc']
				,'deadline_days'=>$deadlineUpdatedData['deadlineUpdatedDays']
				,'day_type'=>$deadlineUpdatedData['dayUpdatedType']]);
		}

	}
?>