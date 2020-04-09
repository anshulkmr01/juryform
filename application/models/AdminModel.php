<?php

	class AdminModel extends CI_Model
	{
		function isValidate($adminemail,$adminpassword)
		{
				$query = $this->db->where(['adminemail'=>$adminemail, 'adminpassword'=>md5($adminpassword)])
									->get('admins');
				if($query->num_rows())
				{
					return $query->row()->id;
				}
				else
				{
					return false;
				}
		}

		function getCategories(){
			$query = $this->db->get('documentcategories');
			return $query;
		}

		function addCategory($categoryName){
			return $this->db->insert('documentcategories',['Categoryname'=>$categoryName]);
		}

		function deleteCategory($categoryId){
			$documentsName = $this->db->select('DocumentName')->where(['CategoryId'=>$categoryId])->get('documentnames')->result();
			$filePath = "uploads/";
			if($this->db->delete('documentnames',['CategoryId'=>$categoryId]) && $this->db->delete('documentcategories',['CategoryId'=>$categoryId])){

				foreach ($documentsName as $name) {
					unlink($filePath.$name->DocumentName.".docx");
				}
				return true;
			}
		}


		function updateCategoryName($CategoryId,$editCategory){
			return $this->db->where('CategoryId',$CategoryId)
						->update('documentcategories',['Categoryname'=>$editCategory]);
		}

		function addDocuments($categoryId,$image_path,$image_name){
			return $this->db->insert('documentnames',['	CategoryId'=>$categoryId,'DocumentPath'=>$image_path,'DocumentName'=>$image_name]);

		}

		function getDocumentsList($CategoryId){
		return $this->db->where(['CategoryId'=>$CategoryId])->get('documentnames')->result();
		}

		function allDocuments(){
			return $this->db->get('documentnames')->result();
		}

		function allFilterDocuments($fieldID){
			$data['assignedfields'] = $this->db->where(['FieldID'=>$fieldID])->get('assignedfields')->result();

			$data['allDocuments'] = $this->db->get('documentnames')->result();

			$data = $this->removeDublicateEntry($data['assignedfields'], $data['allDocuments']);

			return $data;
		}

		function removeDublicateEntry($assignedfields, $allDocuments){
			$i = 0;
			$j = 0;
			foreach ($assignedfields as $key => $value) {
				foreach ($allDocuments as $key_ => $value_) {
					if($value->DocumentID == $value_->ID){
						unset($assignedfields[$i]);
						unset($allDocuments[$i]);
					}
					$j++;
				}
				$i++;
			}

			return $allDocuments;
		}

		function deleteDocuments($documentId){
			return $this->db->delete('documentnames',['ID'=>$documentId]);
		}

		function updateDocumentName($documentId,$updateDocumentName, $newPath, $docRevisedDate){
			return $this->db->where('ID',$documentId)
						->update('documentnames',['DocumentName'=>$updateDocumentName, 'DocumentPath'=>$newPath, 'customDate'=>$docRevisedDate]);
		}

		function addField($labelName,$labelText,$selectedDocuments){

			$chekIfFieldNameExist = $this->db->where('FieldName',$labelText)->get('dynamicfields')->result();
			if($chekIfFieldNameExist){
				$this->session->set_flashdata('error','Keyword Already Exist');
				return redirect('admin/AdminLogin/createField');
			}

			$query = $this->db->insert('dynamicfields',['FieldLabel'=>$labelName, 'FieldName'=>$labelText]);
			$FieldID = $this->db->insert_id();

			foreach ($selectedDocuments as $document) {
			$document = explode('/amg/',$document);

				$docID = $document[0];
				$docName = $document[1];
				$this->db->insert('assignedfields',['FieldID'=>$FieldID, 'DocumentID'=>$docID,'DocumentName'=>$docName]);
			}
			return true;
		}

		function assignMoreDocuments($fieldID, $selectedDocuments){

			foreach ($selectedDocuments as $document) {
			$document = explode('/amg/',$document);

				$docID = $document[0];
				$docName = $document[1];
				$this->db->insert('assignedfields',['FieldID'=>$fieldID, 'DocumentID'=>$docID,'DocumentName'=>$docName]);
			}
			return true;
		}


		function updateField($labelName,$labelText,$fieldId){
			return $this->db->where('ID',$fieldId)
							 ->update('dynamicfields',['FieldLabel'=>$labelName, 'FieldName'=>$labelText]);
		}

		function removeDocumentFromKeyword($docID,$keywordID){
			return $this->db->delete('assignedfields',['DocumentID'=>$docID,'FieldID'=>$keywordID]);
		}

		function changeAdminCredentials($adminId,$adminName,$adminPassword){
			return $this->db->where('id',$adminId)
							 ->update('admins',['adminemail'=>$adminName, 'adminpassword'=>md5($adminPassword)]);
		}

		function checkAdmin($adminemail){
			$query =  $this->db->where('adminemail',$adminemail)->get('admins');
			if($query->num_rows())
				{
					return $query->row()->adminpassword;
				}
				else
				{
					return false;
				}
		}

		function fieldList($docID){
			$dynamicfields =  $this->db->where('DocumentID',$docID)->get('assignedfields')->result();
			$i=0;
	        foreach($dynamicfields as $p_cat){

	            $dynamicfields[$i] = $this->DynamicFields($p_cat->FieldID);
	            $i++;
	        }
			return $dynamicfields;
		}

		 public function DynamicFields($id){

	        $this->db->select('*');
	        $this->db->from('dynamicfields');
	        $this->db->where('ID', $id);

	        $child = $this->db->get();
	        $categories = $child->result();
	        return $categories;       
	    }


		function allFieldList(){
			$dynamicfields =  $this->db->get('dynamicfields')->result();
		
			$i=0;
	        foreach($dynamicfields as $p_cat){

	            $dynamicfields[$i]->sub = $this->assignedDocuments($p_cat->ID);
	            $i++;
	        }
			return $dynamicfields;
		}

		 public function assignedDocuments($id){

	        $this->db->select('*');
	        $this->db->from('assignedfields');
	        $this->db->where('FieldID', $id);

	        $child = $this->db->get();
	        $categories = $child->result();
	        return $categories;       
	    }


		function deleteField($fieldId){
			$this->db->delete('assignedfields',['FieldID'=>$fieldId]);
			$this->db->delete('dynamicfields',['ID'=>$fieldId]);

			return true;
		}
	}
?>