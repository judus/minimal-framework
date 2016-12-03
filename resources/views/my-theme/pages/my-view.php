<?php
	$title = isset($title) ? $title : 'Minimal Framework';
	$content = isset($content) ? $content : '';

?>
	<!-- Main jumbotron for a primary marketing message or call to action -->
	<div class="jumbotron">
		<div class="container">
			<!-- Add your site or application content here -->
			<h1><?=$title?></h1>
		</div>
	</div>
	<div class="container">
		<!-- Example row of columns -->
		<div class="row">
			<div class="col-md-12">
				<?=$content?>
			</div>
		</div>
	</div>
