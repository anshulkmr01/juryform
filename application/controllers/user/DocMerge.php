<?php

		use DocxMerge\DocxMerge;

		class DocMerge extends CI_controller{

		function index(){

			$dm = new DocxMerge();
			$resultFile = 'result.docx';
			$resultFolder = 'mergedDocs/';

			$docNames = $this->input->post('docName');
			$result = $dm->merge($docNames, $resultFolder.$resultFile,true);
			if($result == 0)
			{
				$this->session->set_flashdata('mergedFileSuccess',$resultFolder.$resultFile);
			}

			else{
				$this->session->set_flashdata('mergedFileFailed','File Merging Failed');
			}
			
			return redirect('user/HomeController');
		}
	}
?>