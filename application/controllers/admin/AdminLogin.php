<?php
	class AdminLogin extends CI_Controller{

public function __construct(){

		parent::__construct();
				$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		}
		public function index()
		{
			if($this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/welcome');
			$this->load->view('admin/login');
		}

		public function validate(){

			$this->form_validation->set_rules('adminemail','Email','trim|required',
											array('required' => '%s is Required'));
			$this->form_validation->set_rules('adminpassword','Password','trim|required',
											array('required' => '%s is Required'));

			if($this->form_validation->run())
			{
				$adminemail = $this->input->post('adminemail');
				$adminpassword = $this->input->post('adminpassword');

				$this->load->model('AdminModel');
				$adminId = $this->AdminModel->isValidate($adminemail,$adminpassword);

				if($adminId){
					//$this->load->library('session');
					// Setting the user id into user session
					$this->session->set_userdata('adminId',$adminId);

					return redirect('admin/AdminLogin/welcome');
				}
				else{
					$this->session->set_flashdata('login_failed','Invalid Email or Password');
					return redirect('admin/AdminLogin/index');
				}

			}

			else
			{
				$this->load->view('admin/login');
			}
		}

		public function welcome()
		{
			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->model('AdminModel');
			$queryResult = $this->AdminModel->getCategories();

			$this->load->view('admin/dashboard',['categoriesData'=>$queryResult]);
		}

		public function logout(){
			$this->session->unset_userdata('adminId');
			$this->session->set_flashdata('logout_success',"Logout Successfully");
			return redirect ('admin/AdminLogin/');
		}

		public function createCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->load->view('admin/createCategory');
		}

		public function categoryValidate(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');


			$this->form_validation->set_rules('newCategory','Category','trim|required',
									array(['required'=>'%s is Required']));
			if($this->form_validation->run()):
				$categoryName = $this->input->post('newCategory');

				$this->load->model('AdminModel','newCategory');

				if($this->newCategory->addCategory($categoryName)){
					
					$this->session->set_flashdata('success','Category Created Successfully');
					return redirect('admin/adminLogin/createCategory');

				}
			else{
					$this->session->set_flashdata('error','Category Adding Failed');
					return redirect('admin/adminLogin/createCategory');

			}

			endif;


			$this->load->view('admin/createCategory');
		}

		public function deleteCategory(){

			$categoryId = $this->input->post('categoryId');
			$this->load->model('AdminModel');
			if($this->AdminModel->deleteCategory($categoryId))
			{
					$this->session->set_flashdata('success','Category Created Successfully');
					return redirect('admin/AdminLogin/welcome');

			}
			else{
					$this->session->set_flashdata('error','Category Adding Failed');
					return redirect('admin/AdminLogin/welcome');

			}
		}


		public function editCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');


			$categoryName = $this->input->post('categoryName');
			$categoryId = $this->input->post('categoryId');
			$this->load->view('admin/updateCategory',['categoryName'=>$categoryName,'categoryId'=>$categoryId]);
		}

		public function updateCategory(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			$this->form_validation->set_rules('editCategory','Category Name','trim|required',
											array('required' => '%s is Required'));

			$editCategory = $this->input->post('editCategory');
			$CategoryId = $this->input->post('categoryId');

			if($this->form_validation->run()){
				$this->load->model('AdminModel');
				if($this->AdminModel->updateCategoryName($CategoryId,$editCategory)){

					$this->session->set_flashdata('success','Name Changed Successfully');
					return redirect('admin/AdminLogin/welcome');
				}
				else{
					$this->session->set_flashdata('error','Name Could not Changed');
					return redirect('admin/AdminLogin/welcome');
				}
			}
			else
			{
				$this->load->view('admin/updateCategory',['categoryName'=>$editCategory,'categoryId'=>$CategoryId]);
			}
		}

		public function documents(){

			if(!$this->session->userdata('adminId'))
				return redirect('admin/AdminLogin/');

			
			$this->session->set_userdata('adminId',$adminId);
			$categoryData = $this->input->post();
			
			$this->load->model('AdminModel');
			$this->AdminModel->getDocumentsList($categoryData['categoryId']);

			$this->load->view('admin/documents',['categoryData'=>$categoryData]);
		}
		public function uploadFiles(){

			$config=[
			'upload_path'=>'./uploads/',
			'allowed_types'=>'gif|jpg|png|jpeg',
			];

			$this->load->library('upload', $config);
			$categoryData = $this->input->post();
			if($this->upload->do_upload('docFiles'))
			{
				$data = $this->upload->data();
				$image_path = base_url("uplads/".$data['raw_name'].$data['file_ext']);

				$this->load->model('AdminModel');
				if($this->AdminModel->addDocuments($categoryData['categoryId'],$image_path))
				{

					$this->session->set_flashdata('success','Documents Added Successfully');
					$this->load->view('admin/documents',['categoryData'=>$categoryData]);
				}
				else{
					$this->session->set_flashdata('error','Documents Adding Failed');
					$this->load->view('admin/documents',['categoryData'=>$categoryData]);
				}

			}
			else{
				$categoryData['upload_error']=$this->upload->display_errors();
				$this->load->view('admin/documents',['categoryData'=>$categoryData]);
			}

		}
}
?>