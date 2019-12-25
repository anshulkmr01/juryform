<?php
		include 'vendor/autoload.php';
		use PhpOffice\PhpWord\TemplateProcessor;
		use DocxMerge\DocxMerge;

		class DocMerge extends CI_controller{

		function index(){

			$dm = new DocxMerge();
			$resultFile = 'result.docx';
			$resultFolder = 'mergedDocs/';

			$docNames = $this->input->post('docName');

			$variables = $this->input->post();
			unset($variables['docName']);

			$result = $dm->merge($docNames, $resultFolder.$resultFile,true);
			if($result == 0)
			{
				$this->session->set_flashdata('mergedFileSuccess',$resultFolder.$resultFile);

				self::textReplace();
				echo "<pre>";

				foreach ($variables as $key => $value) {
					
					echo $key."----".$value."<br>";
				}

				exit();
			}

			else{
				$this->session->set_flashdata('mergedFileFailed','File Merging Failed');
			}
			
			return redirect('user/HomeController');
		}

		function textReplace(){
			$resultFolder = 'mergedDocs/';
			$templateProcessor = new TemplateProcessor($resultFolder.'result.docx');
			$templateProcessor->setValue('Name_of_Plaintiff', 'Anshul');
			$templateProcessor->saveAs('result.docx');
		}
	}
?>