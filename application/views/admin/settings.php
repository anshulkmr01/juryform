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
				<div class="col-sm-6">
				<?= form_open('admin/AdminLogin/changePassword'); ?>
				  <fieldset>
				    <legend>Change Admin Credentials</legend>

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
					    $adminName =  $this->session->userdata('adminemail');
					    $adminId = $this->session->userdata('adminId')
				    ?>
				    <div class="form-group">
				      <label for="exampleInputEmail1">Admin Name*</label>
				      <?= form_input(['placeholder'=>'Admin User Name','name'=>'newAdminName','value'=>$adminName,'class'=>'form-control','id'=>'newAdminName','aria-describedby'=>'newAdminName']); ?>
				      <small id="newAdminName" class="form-text text-muted"></small>
					  <?= form_error('newAdminName');?>
				  	</div>

				    <div class="form-group">
				      <label for="exampleInputEmail1">Password*</label>
				      <?= form_input(['placeholder'=>'Admin Password','name'=>'newAdminPassword','class'=>'form-control','id'=>'newAdminPassword','aria-describedby'=>'newAdminPassword']); ?>
				      <small id="newAdminPassword" class="form-text text-muted"></small>
					  <?php echo form_error('newAdminPassword');?>
				  	</div>

				    <div class="form-group">
				      <?= form_hidden('adminId',$adminId)?>
				  	</div>
				    <?= form_submit(['value'=>'Change','class'=>'btn btn-primary']); ?>
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
