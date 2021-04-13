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
	<!-- Search Bar -->
		<div class="container-fluid margin-top-25 row">
			<div class="col-sm-8"></div>
			<div class="col-sm-3">
			<form class="my-2 my-lg-0">
		      <input class="form-control mr-sm-2" type="text" placeholder="Input Category Name for Search" id="myInput" onkeyup="myFunction()">
		    </form>
		    </div>
		</div>
	<!--/ Search Bar -->

	<div class="container-fluid table categories-table">
		<div class="container">
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
			<table id="myTable" class="sortable-table">
				<?= form_open('admin/jury_forms/deleteSelectedCategories'); ?>
				<tr class="sorter-header">
					<th class="no-sort">S.no</th>
					<th>Categories Name</th>
					<!--<th class="is-date">Date Revised</th>
					<th class="is-date">Date Of Creation</th> -->
					<th colspan="3" class="no-sort"><center>Action<center></th>
					<th class="no-sort"><center><label><input type="checkbox" name="sample" class="selectall" style="display:none;"/> <span style="cursor: pointer;">Select all</span></label></center></th>
				</tr>
					<?php
						$i=0;
						foreach($categoriesData->result() as $categories): $i++?>
						<tr>
						<td><?= $i?></td>
						<td><?= $categories->Categoryname; ?></td>
						<!--
						<td><?#= date('m/Y',strtotime($categories->DateofUpdation)) ?></td>
						<td><?#= date('m/d/y',strtotime($categories->Dateofcreation)) ?></td>
						-->	
						<td>
							<a href="<?= base_url('admin/jury_forms/HomeController/documents')?>?categoryId=<?=$categories->CategoryId ?>&categoryName=<?=$categories->Categoryname?>" class="btn btn-primary">View</a>
						</td>
						<td>
							<a href="<?= base_url('admin/jury_forms/HomeController/editCategory')?>?categoryId=<?=$categories->CategoryId ?>&categoryName=<?=$categories->Categoryname?>" class="btn btn-primary">Edit</a>
						</td>
						<td>
							<a href="<?= base_url('admin/jury_forms/HomeController/deleteCategory')?>?categoryId=<?=$categories->CategoryId ?>" class="delete btn btn-danger">Delete</a>
						</td>
						<td><center><input type="checkbox" value="<?=$categories->CategoryId ?>" name="categoryIds[]"></center></td>
						</tr>
					<?php endforeach ?>

						<?php if(isset($categories->CategoryId)) {?>
						<tfoot>
						<tr>
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
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>
