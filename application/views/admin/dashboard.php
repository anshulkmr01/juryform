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
			<table>
				<tr>
					<th>S.no</th>
					<th>Categories Name</th>
					<th>Date Of Creation</th>
					<th colspan="3"><center>Action<center></th>
				</tr>
					<?php
						$i=0;
						foreach($categoriesData->result() as $categories): $i++?>
						<tr>
						<td><?= $i?></td>
						<td><?= $categories->Categoryname; ?></td>
						<td><?= date('d / M / Y H:i',strtotime($categories->Dateofcreation)) ?></td>
						<td>
						<?= #anchor("admin/AdminLogin/documents/{$categories->CategoryId}",'Explore',['class'=>'btn btn-primary']);
							form_open('admin/AdminLogin/documents'),
							form_hidden('categoryId',$categories->CategoryId),
							form_hidden('categoryName',$categories->Categoryname),
							form_submit(['value'=>'Explore','class'=>'btn btn-primary']),
							form_close();
						?>

						</td>
						<td>
						<?= form_open('admin/AdminLogin/editCategory'),
							form_hidden('categoryId',$categories->CategoryId),
							form_hidden('categoryName',$categories->Categoryname),
							form_submit(['value'=>'Edit','name'=>'submit','class'=>'btn btn-primary']),
							form_close();
						?>
						</td>
						<td>
						<?= form_open('admin/AdminLogin/deleteCategory'),
							form_hidden('categoryId',$categories->CategoryId),
							form_submit(['value'=>'Delete','name'=>'submit','class'=>'delete btn btn-danger']),
							form_close();
						?>
						</td>
						</tr>
					<?php endforeach ?>
			</table>
		</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>
