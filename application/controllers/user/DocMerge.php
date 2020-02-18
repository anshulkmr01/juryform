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
			$resultFile = 'result.docx';
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
			// $docname[] = base_url('uploads/fix.docx');
			foreach ($docPaths as $document) {

				$docname[] = self::textReplace($variables, $document);
			}

			$result = $dm->merge($docname, $resultFolder.$resultFile,true);
			if($result == 0)
			{
			chmod($resultFolder.$resultFile,0755);
				$this->session->set_flashdata('mergedFileSuccess',$resultFolder.$resultFile);
			}

			else{
				$this->session->set_flashdata('mergedFileFailed','File Merging Failed');
			}
			
			return redirect('user/HomeController');
		}


		function textReplace($variables,$document){
			$resultFolder = 'mergedDocs/textReplaced/';
			$documentName  = ltrim($document, base_url('uploads/')); 
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