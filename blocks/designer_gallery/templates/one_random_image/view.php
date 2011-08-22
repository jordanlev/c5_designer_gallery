<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="random_image">
	<?php
	if (count($images) > 0) {
		shuffle($images);
		$img = $images[0];
		echo "<img src=\"{$img->large->src}\" width=\"{$img->large->width}\" height=\"{$img->large->height}\" alt=\"{$img->title}\" />";
	}
	?>
</div>
