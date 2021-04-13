<?php

	use PhpOffice\PhpWord\PhpWord;
	class HomeController extends CI_Controller
	{
		function __construct(){
			parent::__construct();
			if(!$this->session->userdata('userId'))
				return redirect('loginUser');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

	    $this->load->model('UserModel');
		$this->load->model('AdminModel');
		}

		public function index(){
		$order_by = ""; $sort_type = "";
		$sort_data = $this->input->post("sorting_drop");
		if ($sort_data) {
			$order_by = explode("_", $sort_data)[0];
			$sort_type = explode("_", $sort_data)[1];
		}
		$queryResult = $this->UserModel->get_categories($order_by, $sort_type);
		$this->load->view('user/jury_forms/homepage',['categoriesData'=>$queryResult]);
	    }

	    function getDynamicFields(){
	    	require(__DIR__."/../../admin/jury_forms/doc2txt.class.php");
	    	$sq_brakets_arr = [];
	    	$categoryNames = $this->input->post();
	    	error_reporting(E_ERROR | E_PARSE);
			$fieldList[] = $this->AdminModel->fieldList('allDocuments');
	    	foreach ($categoryNames['docPath'] as $categoryData) {
	    		$docID = explode ("/amg/", $categoryData)[1];  
	    		$docPath[] = $document_fullpath = explode ("/amg/", $categoryData)[0];	    	
				$fieldList[] = $this->AdminModel->fieldList($docID);

				$docObj = new Doc2Txt(str_replace(base_url(),"",$document_fullpath));
				$lines = $docObj->convertToText();
				preg_match_all("/\[[^\]]*\]/", $lines, $matches);
				$sqr_brakets = $matches[0];
				$sq_brakets_arr[$document_fullpath] = $sqr_brakets;
				
	    	}

	    	if(!$fieldList){
	    		$this->session->set_flashdata('error', 'Admin has not assigned any Keyword to any Document');
	    		return redirect('home');
	    	}

			$this->create_doc($sq_brakets_arr);

	    	$fieldList = call_user_func_array('array_merge', $fieldList);
	    	$fieldList = call_user_func_array('array_merge', $fieldList);

			$newArray = json_decode(json_encode($fieldList), true);
			$newArray = $this->unique_key($newArray,'FieldName');
			$this->load->view('user/jury_forms/dynamicFields',['fieldList'=>$newArray,'documents'=>$docPath]);
	    }

	    function unique_key($array,$keyname){

		 $new_array = array();
		 foreach($array as $key=>$value){
		   if(!isset($new_array[$value[$keyname]])){
		     $new_array[$value[$keyname]] = $value;
		   }

		 }
		 $new_array = array_values($new_array);
		 return $new_array;
		}

		function create_doc($sq_brakets_arr)
		{
			$flag = false;
			$phpWord = new PhpWord();
			// Adding an empty Section to the document...
			$section = $phpWord->addSection();

			foreach ($sq_brakets_arr as $key => $value) {
				if($value == "" || $value == null || !$value){
					continue;
				}
				$title = str_replace("_"," ",pathinfo($key, PATHINFO_FILENAME));
				// Adding Text element with font customized inline...
				$section->addText($title,
				array('name' => 'Tahoma', 'size' => 11, 'bold'=> true)
				);
					foreach ($value as $key => $text) {
						$section->addText($text,
						array('name' => 'Tahoma', 'size' => 10)
						);
						$flag = true;
					}
			}

			if ($flag) {
			// Saving the document as OOXML file...
			$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$objWriter->save('dynamic_doc_first_page/doc_first_page.docx');
			}

		}
	}
?>