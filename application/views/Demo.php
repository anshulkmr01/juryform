<div class="row document-add">
			<div class="col-sm-6">
				<?= form_open_multipart('Demo/demo'); ?>
		    <div class="form-group">


		      <label for="exampleInputFile">Select Files</label>

		      <?= form_upload(['class'=>'form-control-file','name'=>'docFiles','id'=>'docFiles','aria-describedby'=>'docFiles','multiple'=>'true']);
		      ?>
		    </div>
		    <?= form_submit(['value'=>'Upload','class'=>'btn btn-primary big-button']); ?>
			</div>
		</div>