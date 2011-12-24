<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<style type="text/css">
	div.ccm-pane-controls label {
		font-weight: normal !important;
		margin-bottom: 0;
	}
	.gallery-display-table {
		width:100%;
		padding-bottom: 10px;
	}
	.gallery-display-table td {
		padding: 10px 0;
		border-bottom: 1px dotted gray;
	}
	.gallery-display-table td.label {
		text-align: right;
		padding-right: 3px;
	}
	.gallery-display-table input {
		text-align: center;
		width: 30px;
	}
</style>

<table border="0" cellpadding="0" cellspacing="0" class="gallery-display-table">
	<tr><td class="label">
		<?php echo $form->label('fsID', t('File Set:')); ?>
	</td><td colspan="5">
		<select id="fsID" name="fsID">
			<option value="0"><?php echo t('Loading&hellip;'); ?></option>
		</select>
		&nbsp;&nbsp;&nbsp;
		[<a href="#" id="fileManagerLink"><?php echo t('Open File Manager&hellip;'); ?></a>]
	</td></tr>
	
	<tr><td class="label">
		<?php echo $form->label('randomize', t('Display Order:')); ?>
	</td><td colspan="5">
		<?php echo $form->select('randomize', array('0' => t('Fileset Order'), '1' => t('Random Order')), $randomize); ?>
	</td></tr>

	<tr <?php echo $showLargeControls ? '' : 'style="display:none;"'; ?>>
		<td class="label">
			<?php echo $form->label('cropLarge', t('Size Options:')); ?>
		</td><td>
			<?php echo $form->select('cropLarge', array('-1' => t('Keep Original Size'), '0' => t('Shrink Proportionally'), '1' => t('Crop To Fit')), $cropLarge); ?>
		</td><td class="label">
			<?php echo $form->label('largeWidth', t('Width:')); ?>
		</td><td>
			<?php echo $form->text('largeWidth', $largeWidth); ?> px
		</td><td class="label">
			<?php echo $form->label('largeHeight', t('Height:')); ?>
		</td><td>
			<?php echo $form->text('largeHeight', $largeHeight); ?> px
		</td>
	</tr>
	
	<tr <?php echo $showThumbControls ? '' : 'style="display:none;"'; ?>>
		<td class="label">
			<?php echo $form->label('cropThumb', t('Thumbnail Options:')); ?>
		</td><td>
			<?php echo $form->select('cropThumb', array('0' => t('Shrink Proportionally'), '1' => t('Crop To Fit')), $cropThumb); ?>
		</td><td class="label">
			<?php echo $form->label('thumbWidth', t('Width:')); ?>
		</td><td>
			<?php echo $form->text('thumbWidth', $thumbWidth); ?> px
		</td><td class="label">
			<?php echo $form->label('thumbHeight', t('Height:')); ?>
		</td><td>
			<?php echo $form->text('thumbHeight', $thumbHeight); ?> px
		</td>
	</tr>
</table>

<script type="text/javascript">
var FILESETS_URL = '<?php echo $filesetsToolURL ?>';
refreshFilesetList(<?php echo $fsID ?>);
</script>
