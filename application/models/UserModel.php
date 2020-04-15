<?php

	class UserModel extends CI_Model
	{

        //User Registration
        function addUser($userData){
            $key = (md5(time()));
                $query = $this->db->insert('users',['fullname'=>$userData['fullName'], 'email'=>$userData['userEmail'],
                    'telephone'=>$userData['telephone'],'password'=>md5($userData['password']),'verificationkey'=>$key]);

                if($query){
                $this->verifyEmail($key,$userData['userEmail']);

                return $query;
                }
            }


        //verify Email
        function verifyEmail($key,$user){
            $url = base_url('user/UserController/verifyUser/'.$user.'/'.$key);
            //Load email library
                $this->load->library('email');

                $config['protocol']    = 'smtpout.secureserver.net';
                $config['smtp_host']    = 'localhost';
                $config['smtp_port']    = '25';
                $config['smtp_timeout'] = '600';

                $config['smtp_user']    = 'info@kennerlawgroup.com';    //Important
                $config['smtp_pass']    = 'Maria!$%';  //Important


                $config['charset']    = 'utf-8';
                $config['newline']    = "\r\n";
                $config['mailtype'] = 'html'; // or html
                $config['validation'] = TRUE; // bool whether to validate email or not 

                $this->email->initialize($config);
                $this->email->set_mailtype("html"); 
                $this->email->set_newline("\r\n");


                $message .= 'Dear User, <br><br>';
                $message .= 'Thank you so much to register with Jury Forms, please click on the button below to verify your email address and you can login to your account. <br><br>';
                $message .= '<a href='.$url.' style="background: #000;padding: 11px;color: #fff; text-decoration: none;">Verify</a> <br><br>' ;

                $this->email->from('info@kennerlawgroup.com', 'Jury Forms');
                $this->email->to($user);

                $this->email->subject('Thank you so much to register with Jury Forms, Please Verify your Email');
                $this->email->message($message);
                $this->email->send();
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

                    $config['protocol']    = 'smtpout.secureserver.net';
                    $config['smtp_host']    = 'localhost';
                    $config['smtp_port']    = '25';
                    $config['smtp_timeout'] = '600';

                    $config['smtp_user']    = 'info@kennerlawgroup.com';    //Important
                    $config['smtp_pass']    = 'Maria!$%';  //Important
                    
                    $config['charset']    = 'utf-8';
                    $config['newline']    = "\r\n";
                    $config['mailtype'] = 'html'; // or html
                    $config['validation'] = TRUE; // bool whether to validate email or not 

                    $this->email->initialize($config);
                    $this->email->set_mailtype("html"); 
                    $this->email->set_newline("\r\n");


                    $message .= 'Dear User, <br><br>';
                    $message .= 'Please Click on Below button and Create a new password. <br><br>';
                    $message .= '<a href='.$url.' style="background: #000;padding: 11px;color: #fff; text-decoration: none;">Click Here</a> <br><br>' ;

                    $this->email->from('info@kennerlawgroup.com', 'Jury Forms');
                    $this->email->to($userEmail);

                    $this->email->subject('Recover your Jury Form Password, Please Verify your Email');
                    $this->email->message($message);
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


        function checkIfUserExist($userdata){
            $query =  $this->db->where('email',$userdata['userEmail'])->get('users')->row('is_verified');
            
            if (isset($query)) {
                if ($query == 0) {
                $key = (md5(time()));
                $query2 = $this->db->where('email',$userdata['userEmail'])->update('users',['verificationkey'=>$key]);
                $this->verifyEmail($key,$userdata['userEmail']);
                //A veryfied user exist
                $this->session->set_flashdata('warning','Congo! You are already a registered User, Please verify your email address');
                return redirect('loginUser');
                }
                else{
                    //A veryfied user exist
                    $this->session->set_flashdata('success','Congo! You are already a registered User');
                    return redirect('loginUser');
                }

            }
            else{
                //No user found
                return true;
            }
            exit();
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