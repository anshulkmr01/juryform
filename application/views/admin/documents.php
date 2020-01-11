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


	<div class="container-fluid table categories-table">
		<div class="container">

		<div class="row document-add">
			<div class="col-sm-6">
				<h3><div class="category-label"><small class="text-muted">You are in</small> <?= $multipleData['categoryData']['categoryName'] ?> <small class="text-muted">Category</small></div></h3>
				<?= form_open_multipart('admin/AdminLogin/uploadFiles'); ?>
		    <div class="form-group">


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
		      <label for="exampleInputFile">Select Files</label>

		      <?= form_upload(['class'=>'form-control-file','name'=>'docFiles[]','id'=>'docFiles','aria-describedby'=>'docFiles','multiple'=>'true']);
		      ?>
		      <?= form_hidden('categoryId',$multipleData['categoryData']['categoryId'])
		      ?>
		      <?= form_hidden('categoryName',$multipleData['categoryData']['categoryName'])
		      ?>
		      <?= form_error('docFiles')?>
				    <?php if($upload_error = $this->session->flashdata('upload_error')):?>
					      <div class="text-danger">
									<?= $upload_error; ?>
					      </div>
					<?php endif ?>

		      <small id="fileHelp" class="form-text text-muted">Use Only Microsoft Office 2007 or later Document Files</small>
		    </div>
		    <?= form_submit(['value'=>'Upload','class'=>'btn btn-primary big-button']); ?>
		    <?= form_close(); 	?>
			</div>
		</div>
	<!-- Search Bar -->
		<div class="margin-top-25 row">
			<div class="col-sm-8"></div>
			<div class="col-sm-4">
			<form class="my-2 my-lg-0">
		      <input class="form-control mr-sm-2" type="text" placeholder="Input Category Name for Search" id="myInput" onkeyup="myFunction()">
		    </form>
		    </div>
		</div>
	<!--/ Search Bar -->

			<table id="myTable" class="sortable-table">
				<?= form_open('admin/AdminLogin/deleteSelected'); ?>
				<tr  class="sorter-header">
					<th class="no-sort">S.no</th>
					<th>Document Name</th>
					<th class="is-date">Date Revised</th>
					<th class="is-date">Date Uploaded</th>
					<th colspan="2" class="no-sort"><center>Action<center></th>
					<th class="no-sort"><center><label><input type="checkbox" name="sample" class="selectall" style="display:none;"/> <span style="cursor: pointer;">Select all</span></label></center></th>
				</tr>
				<?php
				$i=0;
				foreach ($multipleData['documentList'] as $document): $i++ ?>
				<tr>
					<td><?= $i; ?></td>
					<td>
						<?php $url = 'https://docs.google.com/viewerng/viewer?url='.$document->DocumentPath; ?>
						<?= anchor($url,str_replace('_',' ',$document->DocumentName) ,['target'=>'new']) ?>
					
					</td>
					<td>
						<?php if($document->customDate) $date = date_format(date_create($document->customDate),"m/d/Y") ; else $date = date('m/d/Y', strtotime($document->DateofUpdation));
								$dateData = explode('/', $date);
								$month = $dateData[0];
								$date = $dateData[1];
								$year = $dateData[2];

								echo $month."/<span style='display:none'>".$date."/</span>".$year;

						?>
					</td>
					<td><?= date('m/d/Y', strtotime($document->Date_of_Creation)); ?></td>
					<td>
						<a data-toggle="modal" data-item="<?= $document->ID?>" data-id="<?= $document->DocumentName ?>" class="open-AddBookDialog btn btn-primary" href="#renameDocModal">Edit</a>
					</td>
					<td>
						<a href="<?= base_url('admin/AdminLogin/deleteDocument')?>?documentId=<?=$document->ID?>&documentName=<?=$document->DocumentName?>" class="delete btn btn-danger">Delete</a>

					</td>
					<td>
						<center><input type="checkbox" value="<?= $document->ID.'&'.$document->DocumentName ?>" name="docName[]"></center></td>
				</tr>
			<?php endforeach ?>
				<?php if(isset($document->ID)) {?>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?= form_submit(['value'=>'Delete Selected','name'=>'submit','class'=>'delete btn btn-danger']) ?></td>
						</tr>
						</tfoot>
						<?php }
							else{
								echo "No data to Show";
							}
						?>
				<?= form_close();?>
			</table>
					<div class="modal fade" id="renameDocModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">Rename</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      <?= form_open('admin/AdminLogin/documentRename'); ?>
					      	<div class="form-group">
						      <label for="exampleInputEmail1">Category Name*</label>
 							  <input type="hidden" name="docId" id="docId">
 							  <input type="hidden" name="docName" id="docName">
						      <?php echo form_input(['placeholder'=>'New Name','name'=>'docUpdatedName','class'=>'form-control','id'=>'docUpdatedName','aria-describedby'=>'editCategory']); ?>
						      <small id="editCategory" class="form-text text-muted">Enter the New Name For Updation</small>
						      <br>
						      <label for="exampleInputEmail1">Date Revised*</label>
						      <?php echo form_input(['placeholder'=>'MM/YYYY','name'=>'docRevisedDate','type'=>'date','class'=>'form-control','id'=>'docRevisedDate','aria-describedby'=>'docRevisedDate']); ?>
						      <small id="editCategory" class="form-text text-muted">Enter Revised Date, If Date is not selected than Today's Date will be updated as Revised date</small>
						  	</div>
				    		<?= form_submit(['value'=>'Update','name'=>'submit','class'=>'btn btn-primary big-button']); ?>
				    		<?= form_submit(['value'=>'Revise Date Only','name'=>'reviseDate','class'=>'btn btn-primary big-button']); ?>
				    		<?= form_close(); ?>
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
	$(document).on("click", ".open-AddBookDialog", function () {
     var docName = $(this).data('id');
     var docId = $(this).data('item');
     $("#docName").val( docName );
     $("#docUpdatedName").val( docName );
     $("#docId").val( docId );
     // As pointed out in comments, 
     // it is unnecessary to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});

</script>
</html>