<!DOCTYPE html>
<html>
<head>
	<title>Merge Document to JURY INSTRUCTIONS</title>
	<!-- Global Css using Helper -->
	<?php 
			globalCss(); 
	?>
	<!--/ Global Css using Helper -->
</head>
<body>
	<!-- Navbar -->
		<?php include 'navbar.php'?>
	<!--/ Navbar -->

	<div class="container-fluid categories-home">
		<div class="container">
			<?= form_open('user/DocMerge') ?>

				<div class="modal fade" id="textReplaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">Fill the Given Details</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">

							<small id="fileHelp" class="form-text text-muted">Fill the given Details and select the Documents from Categories for merging.</small>
							<div class="row margin-top-25">
								
								<?php if($fieldList){
									foreach ($fieldList as $field) { 
										if(strpos($field->FieldName,"|")){
											?>
												<div class="col-sm-3">
												  <fieldset>
												    <div class="form-group">
												      <label for="exampleInputEmail1"><?= $field->FieldLabel;?></label>
												      <select class="custom-select" name="<?= explode('|', $field->FieldName)[0] ?>">
													      <option selected="" value="*none*">Select:</option>

													      <?php
													      	foreach (array_slice(explode('|', $field->FieldName),1) as $fieldName){
													      		?>
															      <option value="<?= $fieldName ?>"><?= $fieldName ?></option>
													      		<?php
													      	}
			   												 ?>										  </select>
												      <small id="emailHelp" class="form-text text-muted"></small>
													  <?php echo form_error('adminemail');?>
												  	</div>
												  </fieldset>
												</div>
											<?php
										}
										else{
										?>
										<div class="col-sm-3">
										  <fieldset>
										    <div class="form-group">
										      <label for="exampleInputEmail1"><?= $field->FieldLabel; ?></label>
										      <?php echo form_input(['placeholder'=>$field->FieldLabel,'name'=>$field->FieldName,'value'=>set_value('adminemail'),'class'=>'form-control','aria-describedby'=>'adminemail']); ?>
										      <small id="emailHelp" class="form-text text-muted"></small>
											  <?php echo form_error('adminemail');?>
										  	</div>
										  </fieldset>
										</div>

								<?php }}}?>
							</div>
								<!-- <div>
									<ul class="list-none">
										<li>
											<div>
									    	  <input type="checkbox" value="" name="ReplaceAgreeCheck">
										      <label>
										      	Whether include in the jury instruction or not
										  	  </label>
										  	</div>
				                        </li>
				                    </ul>
								</div> -->
					      </div>
					      <div class="modal-footer"><?= form_submit(['value'=>'Submit & Merge','class'=>'btn btn-primary merge-btn',])?>
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					      </div>
					    </div>
					  </div>
					</div>

			<legend>Categories</legend>
			<small id="fileHelp" class="form-text text-muted">Select the Jury Instructions.</small>
				<?php if($mergedFileSuccess = $this->session->flashdata('mergedFileSuccess')):?>
					    	<div class="merge-message text-success">
					    		<b>Selected Documents has Merged Succefully</b>
					    		 <?= anchor($mergedFileSuccess,'Download Now', ['class'=>'badge badge-primary'])?>
					    	</div>
					    <?php endif;?>

					    <?php if($mergedFileFailed = $this->session->flashdata('mergedFileFailed')):?>
					    	<div class="text-danger merge-message">
					    		<?= $mergedFileFailed; ?>
					    	</div>
				<?php endif;?>

			<div class="category-container">
				<?php $counter = 0;?>
				<?php foreach($categoriesData as $categories): ?>
				<div class="category-list row">
					<div class="category col-sm-12"><label class="dateRevised" style="float: right;">Date Revised</label>
						<?php
		                	if ($counter==0) {
		                		?>
								<span class="collapsable-list active-list"><?= $categories->Categoryname ?></span>
		                		<ul class="list-panel" style="max-height: fit-content">
		                		<?php
		                	}
		                	else
		                	{
						?>
						<span class="collapsable-list"><?= $categories->Categoryname ?></span>
		                <ul class="list-panel">
		                	<?php
		                	}
		                	 $counter++; ?>
		                    <div>
		                    	<?php
		                    		if(!empty($categories->sub)){

		                    			foreach($categories->sub as $DocmentData){
		                    	?>
		                        <li>
							      <input type="checkbox" value="<?php echo $DocmentData->DocumentPath ?>" name="docPath[]">
								      <label>
	    							  	<?php $url = 'https://docs.google.com/viewerng/viewer?url='.$DocmentData->DocumentPath;	?>
								      		<?= anchor($url,str_replace('_',' ',$DocmentData->DocumentName),['target'=>'new']) ?>
								  	  </label>
								  		<label style="float: right; cursor: default;">
								  	  	<?php if($DocmentData->customDate)
								  	  		echo date_format(date_create($DocmentData->customDate),"m/Y") ;
								  	  		else echo date('m/Y', strtotime($DocmentData->DateofUpdation));?>
										</label>
		                        </li>
		                        <?php
		                        	}
		                    		}

		                    		else{
		                    			?>
		                        <li>
							      No Document is Listed for this Category
		                        </li>
		                        <?php
		                    		}
		                        ?>
		                    </div>
		                </ul>
					</div>
				</div>
				<?php endforeach ?>
			</div>
			<a href="#textReplaceModal" data-toggle="modal" class="btn btn-primary merge-btn">Create</a>
			<?= form_close(); ?>
		</div>
	</div>
</body>
	<?php 
			globalJs(); 
	?>
</html>
