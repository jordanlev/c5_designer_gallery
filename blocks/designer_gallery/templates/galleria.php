<div id="gallery<?php echo $bID ?>">
	<?php foreach ($images as $img): ?>
    <a rel="<?php echo $img->orig->src ?>" href="<?php echo $img->large->src ?>"><img src="<?php echo $img->thumb->src ?>" alt="<?php echo $img->description ?>" title="<?php echo $img->title ?>"></a>
	<?php endforeach; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#gallery<?php echo $bID ?>').galleria({
		height: <?php echo $controller->largeHeight ?>,
		thumbCrop: false,
        transition: 'fade'
    });	
});
</script>