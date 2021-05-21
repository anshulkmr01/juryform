<?php
		use PhpOffice\PhpWord\TemplateProcessor;
		use DocxMerge\DocxMerge;
		class DocMerge extends CI_controller{
	
		function __construct(){
			parent::__construct();
			if(!$this->session->userdata('userId'))
				return redirect('loginUser');
		}

		function index(){
			$dm = new DocxMerge();
			$resultFile = 'Merged_File.docx';
			$resultFolder = 'mergedDocs/';
			$emptyVar = '*none*';
			$docPaths = $this->input->post('docPath');
			$variables = $this->input->post();
			unset($variables['docPath']);
			foreach ($variables as $key => $value) {
				if(empty($value)){
					$variables[$key] = $emptyVar;
					}
			}
			if (file_exists('dynamic_doc_first_page/doc_first_page.docx')) {		
				$docname[] = base_url('dynamic_doc_first_page/doc_first_page.docx');
			}
			foreach ($docPaths as $document) {
				$docname[] = self::textReplace($variables, $document);
			}

			$result = $dm->merge($docname, $resultFolder.$resultFile,true);
			if($result == 0)
			{
			chmod($resultFolder.$resultFile,0755);
			unlink("dynamic_doc_first_page/doc_first_page.docx");
				$this->session->set_flashdata('mergedFileSuccess',$resultFolder.$resultFile);
			}

			else{
				$this->session->set_flashdata('mergedFileFailed','File Merging Failed');
			}
			
			return redirect('user/jury_forms/HomeController');
		}


		function textReplace($variables,$document){
			$resultFolder = 'mergedDocs/textReplaced/';
			$documentName  = basename($document); 
			$templateProcessor = new TemplateProcessor($document);

			foreach ($variables as $key => $value) {
				$templateProcessor->setValue($key, $value);
			}
			$templateProcessor->saveAs($resultFolder.$documentName);

			$newPath = base_url($resultFolder).$documentName;
			return $newPath;
		}
	}
?>