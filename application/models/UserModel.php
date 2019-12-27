<?php

	class UserModel extends CI_Model
	{
		public function get_categories(){

        $parent = $this->db->get('documentcategories');
        
        $categories = $parent->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_categories($p_cat->CategoryId);
            $i++;
        }
        return $categories;
    }

    public function sub_categories($id){

        $this->db->select('*');
        $this->db->from('documentnames');
        $this->db->where('CategoryId', $id);

        $child = $this->db->get();
        $categories = $child->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_categories($p_cat->ID);
            $i++;
        }
        return $categories;       
    }
	}
?>