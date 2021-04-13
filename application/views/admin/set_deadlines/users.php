<!DOCTYPE html>
<html>
<head>
	<title>Law Calendar</title>
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
		<div class="container-fluid margin-top-25">
			<div class="container">
				<div class="row">
			<div class="col-sm-8">
					<!-- <a data-toggle="modal" class="open-updateFields btn btn-primary btn-sm" href="#addUser">Add User</a> -->
			</div>
			<div class="col-sm-3">
			<form class=" my-2 my-lg-0">
		      <input class="form-control mr-sm-2" type="text" placeholder="Search User" id="myInput" onkeyup="myFunction()">
		    </form>
			</div>
			</div>
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
			<?php if($users){ $i=0;?>	    
			<div class="table-label"><legend>Registered Users</legend></div>
			<table id="myTable" class="sortable-table">
				<?= form_open('admin/set_deadlines/AdminController/deleteSelectedUser'); ?>
				<tr class="sorter-header">
					<th class="no-sort">S.no</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Verified</th>
					<th class="no-sort" colspan="2"><center>Action</center></th>
					<th class="no-sort"><center><label><input type="checkbox" name="sample" class="selectall" style="display:none;"/> <span style="cursor: pointer;">Select all</span></label></center></th>
				</tr>
				<?php foreach($users as $user){ $i++;?>
				<tr>
					<td><?= $i; ?></td>
					<td><?= $user->fullname?></td>
					<td><?= $user->email?></td>
					<td><?= $user->telephone?></td>
					<td><center><?php
							if($user->is_verified == 0){
								 echo "<span class='badge badge-info'>No</span>";
								}
								else {
									echo "<span class='badge badge-success'>Yes</span>";
								}
								?></center></td>
						<td>
							<?= anchor("admin/set_deadlines/AdminController/userProfile/{$user->id}",'Login',['class'=>'btn btn-primary']); ?>
						</td>
						<td>
							<?= anchor("admin/set_deadlines/AdminController/deleteUser/{$user->id}",'Delete',['class'=>'delete btn btn-danger']); ?>
						</td>
					<td><center><input type="checkbox" value="<?=$user->id ?>" name="userIds[]"></center></td>
				</tr>
				<?php }?>
				<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th><center><?= form_submit(['value'=>'Delete','name'=>'deleteRules','class'=>'delete btn btn-danger']) ?></center></td>
						</th>
						</tfoot>
						<?= form_close();?>
			</table>
		<?php
			}
			else{
			echo "No data to Show";} ?>
		</div>
	</div>
	<!-- Modal Popup -->
		<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle">Law Calendar</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
			  <fieldset>
			    <legend>Add new user</legend>
			    <span class="text-muted">Register a new User by filling the following details</span>
				<?= form_open('adduser'); ?>
			    <div class="form-group margin-top-25">
			      <label for="userName">Name*</label>

			      <?php echo form_input(['placeholder'=>'eg. Jason','name'=>'userName','value'=>set_value('userName'),'class'=>'form-control','id'=>'userName','aria-describedby'=>'userName']); ?>
				  <?php echo form_error('userName');?>
			      <small id="newCategory" class="form-text text-muted">You can use Full name here</small>
			  	</div>
			    <div class="form-group margin-top-25">
			      <label for="userEmail">Email*</label>

			      <?php echo form_input(['placeholder'=>'eg. xyz@gmail.com','name'=>'userEmail','value'=>set_value('userEmail'),'class'=>'form-control','id'=>'userEmail','aria-describedby'=>'newCategory']); ?>
				  <?php echo form_error('userEmail');?>
			      <small id="newCategory" class="form-text text-muted">Email should not be used before</small>
			  	</div>
			    <?php echo form_submit(['value'=>'Add','class'=>'btn btn-primary']); ?>
			    <?= form_close(); ?>
			  </fieldset>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		<!--/ Moal Popup -->

</body>
	<?php 
			globalJs(); 
	?>
		<?php if($modal = $this->session->flashdata('modal')):?>
	    	<script type="text/javascript">
	    		var isModal = '<?php echo $modal; ?>';
			    	if(isModal){
			        $("#addUser").modal('show');
			        };
	    	</script>
	    <?php endif;?>
</html>
