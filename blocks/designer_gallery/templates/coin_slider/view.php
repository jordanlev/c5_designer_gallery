<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div style="float: left;">
	<div id="slider<?php echo $bID; ?>">
		<?php foreach ($images as $img): ?>
			<img src="<?php echo $img->large->src; ?>" alt="<?php echo $img->title; ?>" />
			<?php if (!empty($img->description)): ?>
			<span><?php echo $img->description; ?></span>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#slider<?php echo $bID; ?>').coinslider({
		width: <?php echo $controller->largeWidth; ?>
	});
});
</script>
