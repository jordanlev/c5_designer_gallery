<div id="slider<?php echo $bID ?>" class="plusSliderContainer">
	<?php foreach ($images as $img): ?>
	<img src="<?php echo $img->large->src ?>" width="<?php echo $img->large->width ?>" height="<?php echo $img->large->height ?>" alt="" />
	<?php endforeach; ?>
</div>

<script type='text/javascript'>
$(document).ready(function(){
	$('#slider<?php echo $bID ?>').plusSlider({
		sliderEasing: 'swing', // Anything other than 'linear' and 'swing' requires the jquery easing plugin
		width: <?php echo $controller->largeWidth ?>, //we must set this here because we didn't in the CSS
		height: <?php echo $controller->largeHeight ?>, //we must set this here because we didn't in the CSS
		paginationBefore: true, //puts the pagination numbers outside the slider div (this is usually what you want)
		displayTime: 4000, //millisenconds before next slide is shown
        speed: 500, //milliseconds for the transition time
		sliderType: 'slider' // Choose whether the carousel is a 'slider' or a 'fader'
	});
});
</script>
