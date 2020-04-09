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
				    <legend>Add New Field</legend>
				    <span>Add New Field for users to replace text in the Document Files</span>
					<?= form_open('admin/AdminLogin/fieldValidate'); ?>
				    <div class="form-group margin-top-25">
				      <label for="exampleInputEmail1">Field Label Name*</label>

				      <?php echo form_input(['placeholder'=>'eg. Name of Defendant','name'=>'labelName','value'=>set_value('labelName'),'class'=>'form-control','id'=>'newCategory','aria-describedby'=>'newCategory','required'=>'required']); ?>
				      <small id="newCategory" class="form-text text-muted">User will Identify the input field with this Label</small>
					  <?php echo form_error('labelName');?>
				  	</div>
				    <div class="form-group margin-top-25">
				      <label for="exampleInputEmail1">Keyword*</label>

				      <?php echo form_input(['placeholder'=>'eg. Name','name'=>'labelText','value'=>set_value('labelText'),'class'=>'form-control','id'=>'newCategory','aria-describedby'=>'newCategory','required'=>'required']); ?>
				      <small id="newCategory" class="form-text text-muted">A Keyword Must not contain Blank Space</small>
					  <?php echo form_error('labelText');?>
				  	</div>

					 <?php if($documents){?>
				  	 <div class="form-group">
					    <div class="custom-control custom-radio">
					      <input type="radio" id="customRadio1" name="radio_flavour" onclick="handleClick(this);" value="allDocuments" class="custom-control-input" checked>
					      <label class="custom-control-label" for="customRadio1">All Documents</label>
					    </div>
					    <div class="custom-control custom-radio">
					      <input type="radio" id="customRadio2" onclick="handleClick(this);" class="custom-control-input select_list" name="radio_flavour" value="multipleDocuments">
					      <label class="custom-control-label" for="customRadio2">Select Single or Multiple*</label>
						    <select class="custom-select height-250" id="documentSelect" name="documents[]" multiple required disabled="disabled">
						      <?php foreach($documents as $document){?>
						      	<option value="<?= $document->ID?>/amg/<?= $document->DocumentName?>"><?= $document->DocumentName?></option>
						      <?php } ?>
						    </select>
					      <small id="newCategory" class="form-text text-muted">Select single or multiple document by holding down Ctrl button for adding fields with</small>
						  <?php echo form_error('labelText');?>
					    </div>
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
				    <?php if($filedList){
				    	?>
				    <table class="table sortable-table" id="myTable"style="text-align: left;">
				    	<?= form_open('admin/AdminLogin/deleteSelectedFields')?>
				    	<tr  class="sorter-header">
				    		<th class="no-sort">S.no</th>
				    		<th>Label</th>
				    		<th>Keyword</th>
				    		<th colspan="3" class="no-sort">Action</th>
				    		<th class="no-sort"><center><label><input type="checkbox" name="sample" class="selectall" style="display:none;"/> <span style="cursor: pointer;">Select all</span></label></center></th>
				    	</tr>

				    	<?php
				    	$i=0;
				    	 foreach($filedList as $field): $i++?>
				    		<tr>
				    			<td><?= $i; ?></td>
				    			<td><?= $field->FieldLabel; ?></td>
				    			<td><?= $field->FieldName; ?></td>
				    			<td>
				    				<?php if($field->sub[0]->DocumentID != "allDocuments"){?>
									<?= anchor("admin/AdminLogin/assignNewDocument/{$field->ID}",'Add doc',['class'=>'btn btn-primary btn-sm']) ?>
									<?php }?>
								</td>
				    			<td>
									<a data-toggle="modal" data-item="<?= $field->FieldLabel ?>" data-id="<?= $field->ID ?>" data-user="<?= $field->FieldName ?>" class="open-updateFields btn btn-primary btn-sm" href="#renameDynamicFields">Edit</a>
								</td>
				    			<td><?= anchor("admin/AdminLogin/deleteField/{$field->ID}",'Delete',['class'=>'delete btn btn-danger btn-sm']) ?></td>
				    			<td><center><input type="checkbox" value="<?= $field->ID ?>" name="fieldId[]"></center></td>
				    			<?php if($field->sub){

				    				foreach ($field->sub as $document) {
				    				?>
				    				<tr><td></td><td>
				    					<li><?php if($document->DocumentName) echo $document->DocumentName; else echo "For All Documents";?></li>
				    				</td>
				    				<td><?= anchor("admin/AdminLogin/removeDocumentFromKeyword/{$document->DocumentID}/{$field->ID}",'Delete',['class'=>'delete btn btn-danger btn-sm small-btn']) ?>
				    				</td></tr>
				    			<?php }}?>
				    		</tr>
				    	<?php endforeach;?>
				    	<?php if(isset($field->ID)) {?>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?= form_submit(['value'=>'Delete Selected','name'=>'submit','class'=>'delete btn btn-danger btn-sm']) ?></td>
						</tr>
						</tfoot>
						<?php }
							else{
								echo "No data to Show";
							}
						?>
				    	<?= form_close(); ?>
				    </table>
				    <?php
					} 
				    else{
				    	echo "<br> No Data to show";
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


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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

	//Enable HTML select when radio button is checked or vice-versa
function handleClick(myRadio) {
	if(myRadio.value== "multipleDocuments")
	{
	document.getElementById("documentSelect").disabled = false;
	}
	else
	{
	document.getElementById("documentSelect").disabled = true;
	}
}
</script>
</html>
