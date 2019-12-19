<!DOCTYPE html>
<html>
<head>
	<title>My Future Timeline</title>
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
					<?php endif;?>

		      <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
		    </div>
		    <?= form_submit(['value'=>'Upload','class'=>'btn btn-primary big-button']); ?>
			</div>
		</div>
			<table>
				<tr>
					<th>S.no</th>
					<th>Document Name</th>
					<th>Date Of Creation</th>
					<th><center>Action<center></th>
				</tr>
				<?php
				$i=0;
				foreach ($multipleData['documentList'] as $document): $i++?>
				<tr>
					<td><?= $i; ?></td>
					<td><?= $document->DocumentName; ?></td>
					<td><?= date('d/M/Y H:i A ',strtotime($document->Date_of_Creation)); ?></td>
					<td>

						<?= //form_open(''),
							#form_hidden('categoryId',$categories->ID),
							//form_submit(['value'=>'Delete','name'=>'submit','class'=>'delete btn btn-danger']),
							//form_close();
							anchor("admin/AdminLogin/deleteDocument/{$document->ID}",'Delete',['class'=>'delete btn btn-danger']);
						?>
					</td>
				</tr>
			<? endforeach ?>
			</table>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>
