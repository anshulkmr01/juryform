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

	<div class="container-fluid categories-home">
		<div class="container">
			<legend>Categories</legend>
			<small id="fileHelp" class="form-text text-muted">Click on the Categries and select the Documents for merging.</small>
			<?= form_open('user/DocMerge') ?>
			<br>
			<?= form_submit(['value'=>'Merge','class'=>'btn btn-primary merge-btn',])?>
			<br>
				<?php if($mergedFileSuccess = $this->session->flashdata('mergedFileSuccess')):?>
					    	<div class="merge-message text-success">
					    		<b>Selected Documents has Merged Succefully</b>
					    		 <?= anchor($mergedFileSuccess,'Download Now', ['class'=>'badge badge-primary'])?>
					    	</div>
					    <?php endif;?>

					    <?php if($mergedFileFailed = $this->session->flashdata('mergedFileFailed')):?>
					    	<div class="text-danger merge-message">
					    		<?= $mergedFileFailed; ?>
					    	</div>
				<?php endif;?>

			<div class="category-container">
				<?php foreach($categoriesData as $categories): ?>
				<div class="category-list row">
					<div class="category col-sm-12">
						<span class="collapsable-list"><?= $categories->Categoryname ?></span>
		                <ul class="list-panel">
		                    <div>
		                    	<?php
		                    		if(!empty($categories->sub)){

		                    			foreach($categories->sub as $DocmentData){

		                    	?>
		                        <li>
							      <input type="checkbox" value="<?php echo $DocmentData->DocumentPath ?>" name="docName[]">
								      <label data-toggle="modal" data-target="#exampleModalLong">
								      		<?= $DocmentData->DocumentName ?>
								  	  </label>
		                        </li>
		                        <?php
		                        	}
		                    		}

		                    		else{
		                    			?>
		                        <li>
							      No Document is Listed for this Category
		                        </li>
		                        <?php
		                    		}
		                        ?>
		                    </div>
		                </ul>
					</div>
				</div>
				<?php endforeach ?>
			</div>
			<?= form_submit(['value'=>'Merge','class'=>'btn btn-primary merge-btn',])?>
					<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">Document Name</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      	...
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
</html>
