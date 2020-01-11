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

			<div class="row">
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
			<hr>
			<div class="row margin-top-25">
				<div class="col-sm-12 margin-top-25">
				  <fieldset>
				    <legend>Field List</legend>
					<!-- Search Bar -->
						<div class="row">
							<div class="col-sm-4">
							<form class="my-2 my-lg-0">
						      <input class="form-control mr-sm-2" type="text" placeholder="Type Label For Search" id="myInput" onkeyup="myFunction()">
						    </form>
						    </div>
							<div class="col-sm-3"></div>
						</div>
					<!--/ Search Bar -->
				    <?php if($filedList){?>
				    <table class="table sortable-table" id="myTable"style="text-align: center;">
				    	<tr  class="sorter-header">
				    		<th class="no-sort">S.no</th>
				    		<th>Label</th>
				    		<th>Keyword</th>
				    		<th colspan="2" class="no-sort">Action</th>
				    	</tr>

				    	<?php
				    	$i=0;
				    	 foreach($filedList as $field): $i++?>
				    		<tr>
				    			<td><?= $i; ?></td>
				    			<td><?= $field->FieldLabel; ?></td>
				    			<td><?= $field->FieldName; ?></td>
				    			<td>
									<a data-toggle="modal" data-item="<?= $field->FieldLabel ?>" data-id="<?= $field->ID ?>" data-user="<?= $field->FieldName ?>" class="open-updateFields btn btn-primary btn-sm" href="#renameDynamicFields">Edit</a>
								</td>
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

			</div>
					<div class="modal fade" id="renameDynamicFields" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">Rename</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
						  <fieldset>
						    <legend>Upldate Fields</legend>
						    <span>Update Field Name & Keyword for users to replace text in the Document Files</span>
							<?= form_open('admin/AdminLogin/fieldUpdate'); ?>
						    <div class="form-group margin-top-25">
						    	<input type="hidden" id="fieldId" name="fieldId">
						      <label for="exampleInputEmail1">Field Label Name*</label>

						      <?php echo form_input(['placeholder'=>'eg. Name of Defendant','name'=>'labelName','value'=>set_value('labelName'),'class'=>'form-control','id'=>'labelName','aria-describedby'=>'newCategory']); ?>
						      <small id="newCategory" class="form-text text-muted">User will Identify the input field with this Label</small>
							  <?php echo form_error('labelName');?>
						  	</div>
						    <div class="form-group margin-top-25">
						      <label for="exampleInputEmail1">Keyword*</label>

						      <?php echo form_input(['placeholder'=>'eg. Name','name'=>'labelText','value'=>set_value('labelText'),'class'=>'form-control','id'=>'labelText','aria-describedby'=>'newCategory']); ?>
						      <small id="newCategory" class="form-text text-muted">A Keyword Must not contain Blank Space</small>
							  <?php echo form_error('labelText');?>
						  	</div>
						    <?php echo form_submit(['value'=>'Update','class'=>'btn btn-primary']); ?>
						    <?= form_close(); ?>
						  </fieldset>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					      </div>
					    </div>
					  </div>
					</div>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
	<script type="text/javascript">
	
// Setting Value of Docnament in Modal for rename//
	$(document).on("click", ".open-updateFields", function () {
     var labelId = $(this).data('id');
     var labelText = $(this).data('user');
     var labelName = $(this).data('item');
     $("#labelText").val( labelText );
     $("#labelName").val( labelName );
     $("#fieldId").val( labelId );
});

</script>
</html>
