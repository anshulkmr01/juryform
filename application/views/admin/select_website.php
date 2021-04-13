<!DOCTYPE html>
<html>
<head>
	<title>Merge Document to JURY INSTRUCTIONS Login</title>
	<?php 
			globalCss(); 
	?>
</head>
<body>
	<div class="container-fluid main-container">
	<div class="user-signup-form container">
		<div class="row">
			<div class="col-sm-3">
				
			</div>
			<div class="col-sm-6">
				<label class="font-weight-bold"><h4><center>Select Website to enter as admin</center></h4></label>
				<div class="row mt-5">
					<div class="col-6">
						<a href="<?= base_url('admin/select_website/jury_forms')?>" class="btn btn-primary w-100">Jury Forms</a>
					</div>
					<div class="col-6">
						<a href="<?= base_url('admin/select_website/set_deadlines')?>" class="btn btn-primary w-100">Set Deadlines</a>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				
			</div>
	</div>
	<div class="row">
		<div class="col-sm-12 company">
			<h6><small class="text-muted">Developed & Designed by </small><?= anchor('https://Kbrostechno.com','KBros Technologies')?></h6>
		</div>
	</div>
	</div>

</body>
	<?php 
			globalJs(); 
	?>
</html>