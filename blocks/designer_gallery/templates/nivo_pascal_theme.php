<div class="slider-wrapper theme-pascal" style="margin-top: 150px;">
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
