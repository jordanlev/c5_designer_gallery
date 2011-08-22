<style type="text/css">
/* This CSS is here inline in the template for demo purposes only -- ideally this would be in your theme css or a separate stylesheet in the block's /css/ directory. */
.nivoSlider {
    margin:100px auto 0 auto;
    width:300px; /* Make sure your images are the same size */
    height:200px; /* Make sure your images are the same size */
}
</style>

<div class="slider-wrapper theme-default">
    <div class="ribbon"></div>
    <div id="slider<?php echo $bID ?>" class="nivoSlider">
		<?php foreach ($images as $img): ?>
		<img src="<?php echo $img->large->src ?>" alt="" title="<?php echo $img->title ?>" />
		<?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
	$(window).load(function() {
		$('#slider<?php echo $bID ?>').nivoSlider();
	});
</script>
