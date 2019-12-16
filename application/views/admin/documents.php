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
				<h3><div class="category-label"><small class="text-muted">You are in</small> <?= $categoryData['categoryName'] ?> <small class="text-muted">Category</small></div></h3>
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

		      <?= form_upload(['class'=>'form-control-file','name'=>'docFiles','id'=>'docFiles','aria-describedby'=>'docFiles']);
		      ?>
		      <?= form_hidden('categoryId',$categoryData['categoryId'])
		      ?>
		      <?= form_hidden('categoryName',$categoryData['categoryName'])
		      ?>
		      <?= form_error('docFiles')?>
		      <div class="text-danger">
		      <?php if(isset($categoryData['upload_error'])){echo $categoryData['upload_error'];}?>
		      </div>

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
					<th colspan="1"><center>Action<center></th>
				</tr>
			</table>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>
