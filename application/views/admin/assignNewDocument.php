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
	<div class="container-fluid createCategory-container">
		<div class="container">
			<div class="row">
				<div class="col-sm-5">
				    <?php if($error = $this->session->flashdata('error')):?>
				    	<div class="alert alert-danger">
				    		<?= $error; ?>
				    	</div>
				    <?php endif;?>

				    <?php if($message = $this->session->flashdata('success')):?>
				    	<div class="alert alert-success">
				    		<?= $message; ?>
				    	</div>
				    <?php endif;?>

				    <?php if($message = $this->session->flashdata('emptyFields')):?>
				    	<div class="alert alert-danger">
				    		<?= $message; ?>
				    	</div>
				    <?php endif;?>

				  <fieldset>
				    <legend>Assign Documents</legend>
				    <span>Assign more documents to selected Field</span>
					<?= form_open('admin/AdminLogin/assignMoreDocuments'); ?>
					<input type="hidden" value="<?= $fieldID ?>" name="fieldID">
					 <?php if($documents){?>
				  	 <div class="form-group">
						    <select class="custom-select" id="documentSelect" name="documents[]" multiple>
						      <?php foreach($documents as $document){?>
						      	<option value="<?= $document->ID?>/amg/<?= $document->DocumentName?>"><?= $document->DocumentName?></option>
						      <?php } ?>
						    </select>
					      <small id="newCategory" class="form-text text-muted">Select single or multiple document by holding down Ctrl button for adding fields with</small>
						  <?php echo form_error('labelText');?>
					  </div>
					<?php } else{?>
						<div style="color: red">No documents to display</div>
					<?php } ?>
				    <div class="form-group margin-top-25">

				  	</div>
				    <?php echo form_submit(['value'=>'Add','class'=>'btn btn-primary']); ?>
				    <?= form_close(); ?>
				  </fieldset>
				</div>
			</div>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript">
</script>
</html>
