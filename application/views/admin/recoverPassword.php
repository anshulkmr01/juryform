<!DOCTYPE html>
<html>
<head>
	<title>Merge Document to JURY INSTRUCTIONS Login</title>
	<?php 
			globalCss(); 
	?>
</head>
<body>
	<div class="container-fluid main-container">
	<div class="user-signup-form container">
		<div class="row">
			<div class="col-sm-6">
				<?= form_open('admin/AdminLogin/sendPasswordEmail'); ?>
				  <fieldset>
				    <legend>Recover Password</legend>

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
				      <label for="exampleInputEmail1">Email*</label>

				      <?= form_input(['placeholder'=>'Email','name'=>'adminEmail','type'=>'text','value'=>set_value('adminemail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'adminemail']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('adminEmail');?>
				  	</div>
				    <?php echo form_submit(['value'=>'Send','class'=>'btn btn-primary']); ?>
				  </fieldset>
			</div>
			<div>
			<div class="col-sm-6">
			</div>
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