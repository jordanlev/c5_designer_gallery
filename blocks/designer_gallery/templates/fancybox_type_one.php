<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="fancybox_thumbs_container">
<?php foreach ($images as $img): ?>

	<div class="fancybox_thumbs_image" style="width: 33%;">

		<div style="height: <?php echo $controller->thumbHeight ?>px;">
			<a href="<?php echo $img->large->src ?>" rel="gallery<?php echo $bID ?>">
				<img src="<?php echo $img->thumb->src ?>" width="<?php echo $img->thumb->width ?>" height="<?php echo $img->thumb->height ?>" alt="<?php echo $img->title ?>" />
			</a>
			<?php if (!empty($img->description)): ?>
			<div class="fancybox_thumbs_caption" style="display: none;">
				<?php echo nl2br($img->description) ?>
			</div>
			<?php endif; ?>
		</div>
		
		<p><?php echo empty($img->title) ? '&nbsp;' : $img->title ?></p>
	
	</div>

<?php endforeach; ?>
</div>

<div style="clear: both;"></div>

<script type="text/javascript">
$(document).ready(function() {
	$('a[rel="gallery<?php echo $bID ?>"]').fancybox({
		'transitionIn' : 'elastic', //'elastic', 'fade', 'none'
		'transitionOut' : 'elastic', //'elastic', 'fade', 'none'
		'titleShow' : true,
		'titlePosition' : 'inside', //'over', 'inside', 'float', or 'outside' ('float' is what outside used to be in older versions, now outside is for unstyled title [so you can style it yourself])
		'onStart': function(images, current) {
			var $caption = $(images[current]).next();
			if ($caption.length) {
				this.title = $caption.html();
			}
		}
	});
});
</script>
