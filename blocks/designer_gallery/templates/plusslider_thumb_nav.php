<div id="slider<?php echo $bID ?>" class="plusSliderContainer">
	<?php foreach ($images as $img): ?>
	<img src="<?php echo $img->large->src ?>" width="<?php echo $img->large->width ?>" height="<?php echo $img->large->height ?>" alt="" />
	<?php endforeach; ?>
</div>

<div class="plusSliderThumbNav">
	<?php $i = 0; foreach ($images as $img): ?>
	<a href="#" onclick="slider.toSlide(<?php echo $i ?>); return false;">
		<img src="<?php echo $img->thumb->src ?>" width="<?php echo $img->thumb->width ?>" height="<?php echo $img->thumb->height ?>" alt="<?php echo $img->title ?>" />
	</a>
	<?php $i++; endforeach; ?>
</div>

<script type='text/javascript'>
$(document).ready(function(){
	slider = new $.plusSlider($('#slider<?php echo $bID ?>'), {
		sliderEasing: 'swing', // Anything other than 'linear' and 'swing' requires the jquery easing plugin
		width: <?php echo $controller->largeWidth ?>, //we must set this here because we didn't in the CSS
		height: <?php echo $controller->largeHeight ?>, //we must set this here because we didn't in the CSS
		createPagination: false,
		createArrows: false,
		displayTime: 4000, //millisenconds before next slide is shown
        speed: 500, //milliseconds for the transition time
		sliderType: 'slider' // Choose whether the carousel is a 'slider' or a 'fader'
	});
});
</script>
