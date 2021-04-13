<!DOCTYPE html>
<html>
<head>
	<title>Law Calendar</title>
	<?php 
			globalCss(); 
	?>
</head>
<body>
	<div class="container-fluid main-container">
	<div class="user-signup-form container">
		<div class="row">
			<div class="col-sm-3">
			</div>
			<div class="col-sm-5">
				<?= form_open('setNewPassword'); ?>
				  <fieldset>
				    <legend>Set New Password</legend>

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
				      <label for="exampleInputEmail1">Password*</label>

				      <?php echo form_input(['placeholder'=>'Password','name'=>'newPassword','value'=>set_value('newPassword'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'newPassword']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('newPassword');?>
				  	</div>

				    <?php echo form_submit(['value'=>'Change','class'=>'btn btn-primary']); ?>
				  </fieldset>
				  <br>
			</div>
			<div class="col-sm-4">
			</div>
	</div>
	<div class="row">
		<div class="col-sm-12 company">
			<h6><small class="text-muted">Developed & Designed by </small><?= anchor('https://Kbrostechno.com','KBros Technologies')?></h6>
		</div>
	</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>