<!DOCTYPE html>
<html>
<head>
	<title>Merge Online</title>
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
		<div class="container-fluid margin-top-25 row">
			<div class="col-sm-1"></div>
			<div class="col-sm-4">
			<form class="my-2 my-lg-0">
		      <input class="form-control mr-sm-2" type="text" placeholder="Input Field Name for Search" id="myInput" onkeyup="myFunction()">
		    </form>
		    </div>
			<div class="col-sm-3"></div>
		</div>
	<!--/ Search Bar -->
	<div class="container-fluid createCategory-container">
		<div class="container">
			
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

			<div class="row">
				<div class="col-sm-6">
				  <fieldset>
				    <legend>Field List</legend>
				    <?php if($filedList){?>
				    <table class="table sortable-table" id="myTable">
				    	<tr  class="sorter-header">
				    		<th class="no-sort">S.no</th>
				    		<th>Label</th>
				    		<th>Keyword</th>
				    		<th class="no-sort">Action</th>
				    	</tr>

				    	<?php
				    	$i=0;
				    	 foreach($filedList as $field): $i++?>
				    		<tr>
				    			<td><?= $i; ?></td>
				    			<td><?= $field->FieldLabel; ?></td>
				    			<td><?= $field->FieldName; ?></td>
				    			<td><?= anchor("admin/AdminLogin/deleteField/{$field->ID}",'Delete',['class'=>'delete btn btn-danger btn-sm']) ?></td>
				    		</tr>
				    	<?php endforeach;?>

				    </table>
				    <?php
					} 
				    else{
				    	echo "No Dynamic Field Added";
				    }
				    ?>
				  </fieldset>
				</div>

				<div class="col-sm-1"></div>
				<div class="col-sm-5">
				  <fieldset>
				    <legend>Add New Field</legend>
				    <span>Add New Field for users to replace text in the Document Files</span>
					<?= form_open('admin/AdminLogin/fieldValidate'); ?>
				    <div class="form-group margin-top-25">
				      <label for="exampleInputEmail1">Field Label Name*</label>

				      <?php echo form_input(['placeholder'=>'eg. Name of Defendant','name'=>'labelName','value'=>set_value('labelName'),'class'=>'form-control','id'=>'newCategory','aria-describedby'=>'newCategory']); ?>
				      <small id="newCategory" class="form-text text-muted">User will Identify the input field with this Label</small>
					  <?php echo form_error('labelName');?>
				  	</div>
				    <div class="form-group margin-top-25">
				      <label for="exampleInputEmail1">Keyword*</label>

				      <?php echo form_input(['placeholder'=>'eg. Name','name'=>'labelText','value'=>set_value('labelText'),'class'=>'form-control','id'=>'newCategory','aria-describedby'=>'newCategory']); ?>
				      <small id="newCategory" class="form-text text-muted">A Keyword Must not contain Blank Space</small>
					  <?php echo form_error('labelText');?>
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
</html>
