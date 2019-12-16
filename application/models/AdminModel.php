<?php

	class AdminModel extends CI_Model
	{
		function isValidate($adminemail,$adminpassword)
		{
				$query = $this->db->where(['adminemail'=>$adminemail, 'adminpassword'=>$adminpassword])
									->get('Admins');
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
			$query = $this->db->get('Documentcategories');
			return $query;
		}

		function addCategory($categoryName){
			return $this->db->insert('Documentcategories',['Categoryname'=>$categoryName]);
		}

		function deleteCategory($categoryId){
			return $this->db->delete('Documentcategories',['ID'=>$categoryId]);
		}


		function updateCategoryName($CategoryId,$editCategory){
			return $this->db->where('id',$CategoryId)
						->update('Documentcategories',['Categoryname'=>$editCategory]);
		}

		function addDocuments($categoryId,$image_path){
			return $this->db->insert('DocumentNames',['	CategoryId'=>$categoryId,'DocumentName'=>$image_path]);

		}

		function getDocumentsList($CategoryId){
			return $this->db->where(['CategoryId'=>$CategoryId])->get('DocumentNames')->result();
		}
	}
?>