<!DOCTYPE html>
<html>
<head>
	<title>Update Category</title>
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
				<?= form_open('admin/AdminLogin/updateCategory'); ?>
				  <fieldset>
				    <legend>Update Category Name</legend>

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
				    <?php
				    
				    if(empty($categoryName)){
				    	$categoryName = "";
				    }
				    ?>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Category Name*</label>

				      <?php echo form_hidden('categoryId',$categoryId); ?>

				      <?php echo form_input(['placeholder'=>'New Name','name'=>'editCategory','class'=>'form-control','id'=>'editCategory','aria-describedby'=>'editCategory','value'=>$categoryName]); ?>

				      <small id="editCategory" class="form-text text-muted">Enter the New Name For Updation</small>
					  <?php echo form_error('editCategory');?>
				  	</div>
				    <?php echo form_submit(['value'=>'Update','class'=>'btn btn-primary']); ?>
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
