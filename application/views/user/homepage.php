<!DOCTYPE html>
<html>
<head>
	<title>My Future Timeline</title>
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
			<legend>Categories</legend>
			<small id="fileHelp" class="form-text text-muted">Fill the given Details and select the Documents from Categories for merging.</small>
			<?= form_open('user/DocMerge') ?>

			<div class="row margin-top-25">
				
				<div class="col-sm-3">
					 <fieldset>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Name of Plaintiff*</label>
				      <?php echo form_input(['placeholder'=>'Name of Plaintiff','name'=>'Name_of_Plaintiff','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminemail');?>
				  	</div>
				  </fieldset>
				</div>
				<div class="col-sm-3">
					 <fieldset>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Name of Defendant*</label>
				      <?php echo form_input(['placeholder'=>'Name of Defendant','name'=>'Name_of_Defendant','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminemail');?>
				  	</div>
				  </fieldset>
				</div>
				<div class="col-sm-3">
					 <fieldset>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Name of Cross-Complainant*</label>
				      <?php echo form_input(['placeholder'=>'Name of Cross-Complainant','name'=>'Name_of_Cross-Complainant','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminemail');?>
				  	</div>
				  </fieldset>
				</div>
				<div class="col-sm-3">
					 <fieldset>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Name of Cross-Defendant*</label>
				      <?php echo form_input(['placeholder'=>'Name of Cross-Defendant','name'=>'Name_of_Cross-Defendant','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminemail');?>
				  	</div>
				  </fieldset>
				</div>

			</div>
			<div class="row margin-top-25">
				<div class="col-sm-3">
					 <fieldset>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Whether you are Male/Female/Corporation*</label>
				      <?php echo form_input(['placeholder'=>'Name of Cross-Defendant','name'=>'Subject','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminemail');?>
				  	</div>
				  </fieldset>
				</div>
				<div class="col-sm-3"></div>
				<div class="col-sm-3"></div>
				<div class="col-sm-3"></div>
			</div>
			<br>
			<?= form_submit(['value'=>'Merge','class'=>'btn btn-primary merge-btn',])?>
			<br>
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
				<?php foreach($categoriesData as $categories): ?>
				<div class="category-list row">
					<div class="category col-sm-12">
						<span class="collapsable-list"><?= $categories->Categoryname ?></span>
		                <ul class="list-panel">
		                    <div>
		                    	<?php
		                    		if(!empty($categories->sub)){

		                    			foreach($categories->sub as $DocmentData){
		                    	?>
		                        <li>
							      <input type="checkbox" value="<?php echo $DocmentData->DocumentPath ?>" name="docName[]">
								      <label>
	    							  	<?php $url = 'https://docs.google.com/viewerng/viewer?url='.$DocmentData->DocumentPath;	?>
								      		<?= anchor($url,$DocmentData->DocumentName,['target'=>'new']) ?>
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
			<?= form_submit(['value'=>'Merge','class'=>'btn btn-primary merge-btn',])?>
			<?= form_close(); ?>
		</div>
	</div>
</body>
	<?php 
			globalJs(); 
	?>
</html>
