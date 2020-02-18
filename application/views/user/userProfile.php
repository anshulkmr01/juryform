<!DOCTYPE html>
<html>
<head>
	<title>Law Calendar</title>
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
	<!-- Search Bar -->
		<div class="container-fluid margin-top-25">
			<div class="container">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="home">Home</a></li>
				  <li class="breadcrumb-item active">Settings</li>
				 </ol>
		    </div>
		</div>
	<!--/ Search Bar -->

	<div class="container-fluid table categories-table">
		<div class="message container">
			<div class="row">
				<div class="col-5">
					<?php if($success = $this->session->flashdata('success')):?>
				    	<div class="alert alert-success">
				    		<?= $success; ?>
				    	</div>
				    <?php endif;?>

				    <?php if($error = $this->session->flashdata('error')):?>
				    	<div class="alert alert-danger">
				    		<?= $error; ?>
				    	</div>
				    <?php endif;?>
				    <?php if($warning = $this->session->flashdata('warning')):?>
				    	<div class="alert alert-warning">
				    		<?= $warning; ?>
				    	</div>
				    <?php endif;?>
				</div>
				<div class="col-7"></div>
			</div>
		</div>
		<div class="container">
			  <div class="tab-pane fade show" id="profile">
			  <div class="col-sm-6">
				<?= form_open('changePassword'); ?>
				  <fieldset>
				    <legend>Change Password</legend>
				    <div class="form-group">
				      <label for="password">Current Password*</label>
				      <?= form_input(['placeholder'=>'Current Password','name'=>'currentPassword','class'=>'form-control','id'=>'currentPassword','aria-describedby'=>'currentPassword']); ?>
					  <?= form_error('currentPassword');?>
				  	</div>

				    <div class="form-group">
				      <label for="password">New Password*</label>
				      <?= form_input(['placeholder'=>'New Password','name'=>'newPassword','class'=>'form-control','id'=>'newPassword','aria-describedby'=>'newPassword']); ?>
				      <small id="newPassword" class="form-text text-muted"></small>
					  <?php echo form_error('newPassword');?>
				  	</div>

				    <div class="form-group">
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
</script>
</html>
