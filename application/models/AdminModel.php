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

		function deleteDocuments($documentId){
			return $this->db->delete('documentnames',['ID'=>$documentId]);
		}


		function updateDocumentName($documentId,$updateDocumentName, $newPath, $docRevisedDate){
			return $this->db->where('ID',$documentId)
						->update('documentnames',['DocumentName'=>$updateDocumentName, 'DocumentPath'=>$newPath, 'customDate'=>$docRevisedDate]);
		}

		function addField($labelName,$labelText,$selectedDocuments){

			foreach ($selectedDocuments as $document) {
			
			$document = explode('/amg/',$document);

				$docID = $document[0];
				$docName = $document[1];
				$this->db->insert('dynamicfields',['FieldLabel'=>$labelName, 'FieldName'=>$labelText,'DocumentID'=>$docID,'docname'=>$docName]);
			}
			return true;
		}


		function updateField($labelName,$labelText,$fieldId){
			return $this->db->where('ID',$fieldId)
							 ->update('dynamicfields',['FieldLabel'=>$labelName, 'FieldName'=>$labelText]);
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
			return $this->db->where('DocumentID',$docID)->get('dynamicfields')->result();
		}

		function allFieldList(){
			return $this->db->get('dynamicfields')->result();
		}


		function deleteField($fieldId){
			return $this->db->delete('dynamicfields',['ID'=>$fieldId]);
		}
	}
?>