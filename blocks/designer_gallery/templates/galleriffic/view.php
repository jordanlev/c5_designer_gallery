<div class="example-page">
	<div class="example-container">
		<h1>Galleriffic</h1>
		<h2>Thumbnail rollover effects and slideshow crossfades</h2>

		<!-- Start Advanced Gallery Html Containers -->
		<div id="gallery<?php echo $bID ?>" class="content">
			<div id="controls<?php echo $bID ?>" class="controls"></div>
			<div class="slideshow-container">
				<div id="loading<?php echo $bID ?>" class="loader"></div>
				<div id="slideshow<?php echo $bID ?>" class="slideshow"></div>
			</div>
			<div id="caption<?php echo $bID ?>" class="caption-container"></div>
		</div>
		<div id="thumbs<?php echo $bID ?>" class="navigation">
			<ul class="thumbs">
				<?php foreach ($images as $img): ?>
				<li>
					<a class="thumb" name="image<?php echo $bID ?>" href="<?php echo $img->large->src ?>" title="<?php echo $img->title ?>">
						<img src="<?php echo $img->thumb->src ?>" alt="<?php echo $img->title ?>" />
					</a>
					<div class="caption">
						<div class="download">
							<a href="<?php echo $img->orig->src ?>">Download Original</a>
						</div>
						<div class="image-title"><?php echo $img->title ?></div>
						<div class="image-desc"><?php echo $img->description ?></div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
	// We only want these styles applied when javascript is enabled
	$('div.navigation').css({'width' : '<?php echo $controller->largeWidth ?>px', 'float' : 'left'});
	$('div.content').css('display', 'block');

	// Initially set opacity on thumbs and add
	// additional styling for hover effect on thumbs
	var onMouseOutOpacity = 0.67;
	$('#thumbs<?php echo $bID ?> ul.thumbs li').opacityrollover({
		mouseOutOpacity:   onMouseOutOpacity,
		mouseOverOpacity:  1.0,
		fadeSpeed:         'fast',
		exemptionSelector: '.selected'
	});
	
	// Initialize Advanced Galleriffic Gallery
	var gallery = $('#thumbs<?php echo $bID ?>').galleriffic({
		delay:                     2500,
		numThumbs:                 15,
		preloadAhead:              10,
		enableTopPager:            true,
		enableBottomPager:         true,
		maxPagesToShow:            7,
		imageContainerSel:         '#slideshow<?php echo $bID ?>',
		controlsContainerSel:      '#controls<?php echo $bID ?>',
		captionContainerSel:       '#caption<?php echo $bID ?>',
		loadingContainerSel:       '#loading<?php echo $bID ?>',
		renderSSControls:          true,
		renderNavControls:         true,
		playLinkText:              'Play Slideshow',
		pauseLinkText:             'Pause Slideshow',
		prevLinkText:              '&lsaquo; Previous Photo',
		nextLinkText:              'Next Photo &rsaquo;',
		nextPageLinkText:          'Next &rsaquo;',
		prevPageLinkText:          '&lsaquo; Prev',
		enableHistory:             false,
		autoStart:                 false,
		syncTransitions:           true,
		defaultTransitionDuration: 900,
		onSlideChange:             function(prevIndex, nextIndex) {
			// 'this' refers to the gallery, which is an extension of $('#thumbs')
			this.find('ul.thumbs').children()
				.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
				.eq(nextIndex).fadeTo('fast', 1.0);
		},
		onPageTransitionOut:       function(callback) {
			this.fadeTo('fast', 0.0, callback);
		},
		onPageTransitionIn:        function() {
			this.fadeTo('fast', 1.0);
		}
	});
});
</script>
