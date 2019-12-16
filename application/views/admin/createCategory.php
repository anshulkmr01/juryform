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

	<div class="container-fluid createCategory-container">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
				<?= form_open('admin/AdminLogin/categoryValidate'); ?>
				  <fieldset>
				    <legend>Add New Category</legend>

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

				    <div class="form-group">
				      <label for="exampleInputEmail1">Category Name*</label>

				      <?php echo form_input(['placeholder'=>'Category','name'=>'newCategory','value'=>set_value('newCategory'),'class'=>'form-control','id'=>'newCategory','aria-describedby'=>'newCategory']); ?>
				      <small id="newCategory" class="form-text text-muted"></small>
					  <?php echo form_error('newCategory');?>
				  	</div>
				    <?php echo form_submit(['value'=>'Create','class'=>'btn btn-primary']); ?>
				  </fieldset>
				</div>
			</div>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>
