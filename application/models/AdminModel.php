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

		//			$query = $this->db->join('DocumentNames', 'DocumentNames.CategoryId =
		//			Documentcategories.CategoryId')->get('Documentcategories');
			return $query;
		}

		function addCategory($categoryName){
			return $this->db->insert('Documentcategories',['Categoryname'=>$categoryName]);
		}

		function deleteCategory($categoryId){
//			return $this->db->delete('Documentcategories',['CategoryId'=>$categoryId]);
				return $this->db->join("DocumentNames", "Documentcategories.CategoryId = DocumentNames.CategoryId")
				  ->where("DocumentNames.CategoryId", $categoryId)
				  ->delete("Documentcategories");
		}


		function updateCategoryName($CategoryId,$editCategory){
			return $this->db->where('CategoryId',$CategoryId)
						->update('Documentcategories',['Categoryname'=>$editCategory]);
		}

		function addDocuments($categoryId,$image_path,$image_name){
			return $this->db->insert('DocumentNames',['	CategoryId'=>$categoryId,'DocumentPath'=>$image_path,'DocumentName'=>$image_name]);

		}

		function getDocumentsList($CategoryId){
		return $this->db->where(['CategoryId'=>$CategoryId])->get('DocumentNames')->result();
		}

		function deleteDocuments($documentId){
			return $this->db->delete('DocumentNames',['ID'=>$documentId]);
		}
	}
?>