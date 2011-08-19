<div>
	<a id="gallery<?php echo $bID ?>" href="javascript:;">
		<?php $img = $images[0]; //use first image for thumbnail ?>
		<img src="<?php echo $img->thumb->src ?>" width="<?php echo $img->thumb->width ?>" height="<?php echo $img->thumb->height ?>" alt="" />
	</a>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#gallery<?php echo $bID ?>").click(function() {
		$.fancybox([
			<?php
			$jsimages = array();
			foreach ($images as $img) {
				$caption = str_replace("'", "\\'", $img->description);
				$jsimages[] = "{'href':'{$img->large->src}','title':'{$caption}'}\n";
			}
			echo implode(",", $jsimages);
			?>
		], {
			'transitionIn' : 'fade', //'elastic', 'fade', 'none'
			'transitionOut' : 'fade', //'elastic', 'fade', 'none'
			'titleShow' : true,
			'titlePosition' : 'float', //'over', 'inside', 'float', or 'outside' ('float' is what outside used to be in older versions, now outside is for unstyled title [so you can style it yourself])
			'type': 'image'
		});
	});
});
</script>
