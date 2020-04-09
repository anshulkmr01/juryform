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

	<div class="container-fluid categories-home">
		<div class="container">
			<?= form_open('user/DocMerge') ?>
			<legend>Fill the Given Details</legend>
			<small id="fileHelp" class="form-text text-muted">Fill the given Details and select the Documents from Categories for merging.</small>
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
		 <div class="modal-body">
					<div class="row margin-top-25 displayFields">
							
						<?php
						 if($fieldList){
							foreach ($fieldList as $fieldData) {
								if(strpos($fieldData['FieldName'],"|")){
									?>
										<div class="col-sm-3 dynamicFieldColumn">
										  <fieldset>
										    <div class="form-group">
										      <label for="exampleInputEmail1"><?= $fieldData['FieldLabel'];?></label>
										      <select class="custom-select" name="<?= explode('|', $fieldData['FieldName'])[0] ?>">
											      <option selected="" value="*none*">Select:</option>

											      <?php
											      	foreach (array_slice(explode('|', $fieldData['FieldName']),1) as $fieldName){
											      		?>
													      <option value="<?= $fieldName ?>"><?= $fieldName ?></option>
											      		<?php
											      	}
	   												 ?>										  </select>
										      <small id="emailHelp" class="form-text text-muted"></small>
											  <?php echo form_error('adminemail');?>
										  	</div>
										  </fieldset>
										</div>
									<?php
								}
								else{
								?>
								<div class="col-sm-3 dynamicFieldColumn">
								  <fieldset>
								    <div class="form-group">
								      <label for="exampleInputEmail1"><?= $fieldData['FieldLabel']; ?></label>
								      <?php echo form_input(['placeholder'=>$fieldData['FieldLabel'],'name'=>$fieldData['FieldName'],'value'=>set_value('adminemail'),'class'=>'form-control','aria-describedby'=>'adminemail']); ?>
								      <small id="emailHelp" class="form-text text-muted"></small>
									  <?php echo form_error('adminemail');?>
								  	</div>
								  </fieldset>
								</div>

						<?php }}}

						else{
							echo "No Any Field is Assigned to Selected Document You can still merge & Download the File ";
						}
						?>
					</div>
			      </div>
		<div>
			<?php
			if($documents){
				foreach ($documents as $documentPath) {
				?>
				<input type="hidden" value="<?= $documentPath ?>" name="docPath[]">
				<?php
				}
		}
		?>
		</div>
		<?= form_submit(['value'=>'Submit & Merge','class'=>'btn btn-primary merge-btn',])?>
			<?= form_close(); ?>
		</div>
	</div>
</body>
	<?php 
			globalJs(); 
	?>

<script>
function change() {
  var modelCbs = document.querySelectorAll(".documents input[type='checkbox']");
  var processorCbs = document.querySelectorAll(".processors input[type='checkbox']");
  var filters = {
    documents: getClassOfCheckedCheckboxes(modelCbs),
    processors: getClassOfCheckedCheckboxes(processorCbs)
  };

  filterResults(filters);
}

function getClassOfCheckedCheckboxes(checkboxes) {
  var classes = [];

  if (checkboxes && checkboxes.length > 0) {
    for (var i = 0; i < checkboxes.length; i++) {
      var cb = checkboxes[i];

      if (cb.checked) {
        classes.push(cb.getAttribute("rel"));
      }
    }
  }

  return classes;
}

function filterResults(filters) {
  var rElems = document.querySelectorAll(".displayFields .dynamicFieldColumn");
  var hiddenElems = [];

  if (!rElems || rElems.length <= 0) {
    return;
  }

  for (var i = 0; i < rElems.length; i++) {
    var el = rElems[i];

    if (filters.documents.length > 0) {
      var isHidden = true;

      for (var j = 0; j < filters.documents.length; j++) {
        var filter = filters.documents[j];

        if (el.classList.contains(filter)) {
          isHidden = false;
          break;
        }
      }

      if (isHidden) {
        hiddenElems.push(el);
      }
    }

    if (filters.processors.length > 0) {
      var isHidden = true;

      for (var j = 0; j < filters.processors.length; j++) {
        var filter = filters.processors[j];

        if (el.classList.contains(filter)) {
          isHidden = false;
          break;
        }
      }

      if (isHidden) {
        hiddenElems.push(el);
      }
    }
  }

  for (var i = 0; i < rElems.length; i++) {
    rElems[i].style.display = "block";
  }

  if (hiddenElems.length <= 0) {
    return;
  }

  for (var i = 0; i < hiddenElems.length; i++) {
    hiddenElems[i].style.display = "none";
  }
}
</script>
</html>
