<?php

	function globalCss()
	{
		?>
		<?= link_tag("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css")?>
		<?= link_tag("assets/css/lux-css/lux-bootstrap.css")?>
		<?php
	}

	function globalJs()
	{
		?>
		<script
		  src="https://code.jquery.com/jquery-3.4.1.min.js"
		  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
		  crossorigin="anonymous"></script>
		  
		<script
			type="text/javascript"
			src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
		<script 
			type="text/javascript"
			src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
		
		<?php
	}
?>