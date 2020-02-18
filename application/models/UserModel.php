<?php

	class UserModel extends CI_Model
	{

        //User Registration
        function addUser($userData){
            $key = (md5(time()));
                $query = $this->db->insert('users',['fullname'=>$userData['fullName'], 'email'=>$userData['userEmail'],
                    'telephone'=>$userData['telephone'],'password'=>md5($userData['password']),'verificationkey'=>$key]);

                if($query){
                $url = base_url('user/UserController/verifyUser/'.$userData['userEmail'].'/'.$key);

                $this->load->library('email');
                $this->email->from('kbrostechno@gmail.com', 'Anshul');
                $this->email->to($userData['userEmail']);

                $this->email->subject('Verify your Email for Registration');
                $message = '<!DOCTYPE html>
                                <html>
                                <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8/>
                                </head>';
                $message .= '<p>Hello User</p>';
                $message .= '<p>Please verify your email address for Law Calendar by clicking
                            <a href="'.$url.'">here</a></p>';
                $message .= '<p>Thanks</>';
                $this->email->message($message);
                $this->email->send();

                return $query;
                }
            }
            //User Verify using email link
        function verifyUser($userEmail,$recivedKey){
            $query = $this->db->where(['email'=>$userEmail])->get('users');
                if($query->num_rows()){

                    //Retriving Key of email
                    $DBkey = $query->row()->verificationkey;
                    $is_verified = $query->row()->is_verified;
                    if($is_verified){
                        $this->session->set_flashdata('warning','You are already a Verified User, Login to contionue');
                        return redirect('loginUser');
                    }

                    if($recivedKey == $DBkey){
                        return $this->db->where(['email'=>$userEmail, 'verificationkey'=>$recivedKey])->update('users',['is_verified'=>1]);
                        }
                        else{
                            //key does not matched
                            $this->session->set_flashdata('error','Link Expired');
                            return redirect('loginUser');
                        }
                    }
                    else{
                            //email does not matched
                            $this->session->set_flashdata('error','Link Expired');
                            return redirect('loginUser');
                    }
                }

        //User Login
        function validateUser($userData){
                $query = $this->db->where(['email'=>$userData['userEmail']])->get('users');
                if($query->num_rows()){

                    //Retriving password of email
                    $userPassword = $query->row()->password;

                    if(md5($userData['userPassword'])==$userPassword){
                        if($query->row()->is_verified){

                            //User is Verified 
                            return $query->row()->id;
                        }
                        else{
                            //User is not verified yet
                            $this->session->set_flashdata('warning','Check Email, Please Verify Your account.');
                            return redirect('loginUser');
                        }
                    }
                    else{
                        //wrong Password
                        return false;
                    }
                }
                else{
                        $this->session->set_flashdata('error','Email is not registered');
                        return redirect('loginUser');
                }
            }
            //Password Recovery email
            function sendRecoverEmail($userEmail){
                 $query = $this->db->where(['email'=>$userEmail])->get('users');
                 if($query->num_rows())
                 {
                    $recoveryKey = (md5(time()));
                    $result = $this->db->where(['email'=>$userEmail])->update('users',['verificationkey'=>$recoveryKey]);
                    if($result){

                    $url = base_url('user/UserController/resetPassword/'.$userEmail.'/'.$recoveryKey);

                    $this->load->library('email');
                    $this->email->from('kbrostechno@gmail.com', 'Anshul');
                    $this->email->to($userEmail);

                    $this->email->subject('Verify your Email for Registration');
                    $this->email->message("click here to verify your email address ".$url);
                    $this->email->send();

                    return true;
                    }
                 }
                 else{
                    $this->session->set_flashdata('error',"You are not a registered user");
                    return redirect('recoverPassword');
                 }
            }

            function recoveryKey($userEmail,$recoveryKey){
                $query = $this->db->where(['email'=>$userEmail])->get('users');
                if($query->num_rows()){

                    //Retriving Key of email
                    $DBkey = $query->row()->verificationkey;
                    if($recoveryKey == $DBkey){
                            $this->session->set_userdata('userEmail',$userEmail);
                            $this->session->set_userdata('recoveryKey',$recoveryKey);
                            return true;
                        }
                        else{
                            //key does not matched
                            $this->session->set_flashdata('error','Link expired');
                            return redirect('loginUser');
                        }
                    }
                    else{
                            //key and email does not matched
                            $this->session->set_flashdata('error','Link expired');
                            return redirect('loginUser');
                    }
            }

            function setNewPassword($newPassword){
                $userEmail = $this->session->userdata('userEmail');
                $recoveryKey = $this->session->userdata('recoveryKey');

                $result = $this->db->where(['email'=>$userEmail, 'verificationkey'=>$recoveryKey])->update('users',['password'=>md5($newPassword),'verificationkey'=>0]);
                if($result){
                    $this->session->unset_userdata('userEmail');
                    $this->session->unset_userdata('recoveryKey');
                    return true;
                }
            }

        //User setting new Password
        function changePassword($changePasswordData){
            $userId = $this->session->userdata('userId');
            $existPassword = $this->db->where('id',$userId)->get('users')->row('password');
            if($existPassword == md5($changePasswordData['currentPassword'])){
                return $this->db->where('id',$userId)->update('users',['password'=>md5($changePasswordData['newPassword'])]);
            }
        }
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