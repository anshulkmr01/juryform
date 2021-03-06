<?php
		use PhpOffice\PhpWord\TemplateProcessor;
		use DocxMerge\DocxMerge;

		class DocMerge extends CI_controller{

		function index(){

			$dm = new DocxMerge();
			$resultFile = 'result.docx';
			$resultFolder = 'mergedDocs/';

			$docPaths = $this->input->post('docPath');
			$variables = $this->input->post();
			unset($variables['docPath']);

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