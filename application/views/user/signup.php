<!DOCTYPE html>
<html>
<head>
	<title>Future Timeline</title>
	<?php 
			globalCss(); 
	?>
</head>
<body>
	<div class="container-fluid">
	<div class="user-signup-form container">
		<div class="row">
			<div class="col-sm-6">
				<?php echo form_open('user/UserSignup/test'); ?>
				  <fieldset>
				    <legend>User Signup</legend>
				    <div class="form-group">
				      <label for="exampleInputEmail1">User Name</label>
				      <?php echo form_input(['placeholder'=>'User Name','name'=>'username','value'=>set_value('username'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'username']); ?>
				      <small id="emailHelp" class="form-text text-muted"></small>
					  <?php echo form_error('username');?>
				  	</div>

					<div class="form-group">
				      <label for="exampleInputEmail1">Email address</label>
				      <?php echo form_input(['placeholder'=>'Email Address','name'=>'useremail','value'=>set_value('useremail'),'class'=>'form-control','id'=>'exampleInputEmail1','aria-describedby'=>'emailHelp']); ?>
				      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					  <?php echo form_error('useremail');?>
				  	</div>

				    <div class="form-group">
				      <label for="exampleInputPassword1">Password</label>

				      <?php echo form_input(['placeholder'=>'Password','name'=>'userpassword','value'=>set_value('userpassword'),'class'=>'form-control','id'=>'exampleInputPassword1','aria-describedby'=>'emailHelp']); ?>
					  <?php echo form_error('userpassword');?>

				    </div>

				    <fieldset class="form-group">
				      <div class="form-check">
				        <label class="form-check-label">
				          <input class="form-check-input" type="checkbox" value="">
				          Yes, I Agree User Agreement
				        </label>
				      </div>
				    </fieldset>
				    <?php echo form_submit(['value'=>'Signup','class'=>'btn btn-primary']); ?>
				  </fieldset>
			</div>

			<div class="col-sm-6">
			</div>
		</div>
	</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>