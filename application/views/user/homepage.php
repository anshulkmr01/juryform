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
		<div class="message container">
			<div class="row">
				<div class="col-sm-5">
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
				</div>
				<div class="col-sm-7"></div>
			</div>
		</div>
		<div class="container">
			<?= form_open('user/HomeController/getDynamicFields') ?>
			<legend>Categories</legend>
			<small id="fileHelp" class="form-text text-muted">Select the Jury Instructions.</small>
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
				<?php $counter = 0;?>
				<?php foreach($categoriesData as $categories): ?>
				<div class="category-list row">
					<div class="category col-sm-12"><label class="dateRevised" style="float: right;">Date Revised</label>
						<?php
		                	if ($counter==0) {
		                		?>
								<span class="collapsable-list active-list"><?= $categories->Categoryname ?></span>
		                		<ul class="list-panel" style="max-height: fit-content">
		                		<?php
		                	}
		                	else
		                	{
						?>
						<span class="collapsable-list"><?= $categories->Categoryname ?></span>
		                <ul class="list-panel">
		                	<?php
		                	}
		                	 $counter++; ?>
		                    <div class="documents">
		                    	<?php
		                    		if(!empty($categories->sub)){

		                    			foreach($categories->sub as $DocumentData){
		                    	?>
		                        <li>
							      <input type="checkbox" rel="<?= $DocumentData->ID?>" onchange="change();" value="<?php echo $DocumentData->DocumentPath ?>/amg/<?= $DocumentData->ID?>" name="docPath[]">
								      <label>
	    							  	<?php $url = 'https://docs.google.com/viewerng/viewer?url='.$DocumentData->DocumentPath;	?>
								      		<?= anchor($url,str_replace('_',' ',$DocumentData->DocumentName),['target'=>'new']) ?>
								  	  </label>
								  		<label style="float: right; cursor: default;">
								  	  	<?php if($DocumentData->customDate)
								  	  		echo date_format(date_create($DocumentData->customDate),"m/Y") ;
								  	  		else echo date('m/Y', strtotime($DocumentData->DateofUpdation));?>
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
			<input type="submit" value="Create" class="btn btn-primary merge-btn">
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
