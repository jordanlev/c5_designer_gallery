<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div style="padding: 5px;">
	<strong>File Set:</strong>

	<select id="fsID" name="fsID">
		<option value="0">--Choose Fileset--</option>

		<?php foreach ($fileSets as $fs): ?>
		<option value="<?php echo $fs->fsID; ?>"<?php echo ($fsID == $fs->fsID ? ' selected="selected"' : ''); ?>>
			<?php echo htmlspecialchars($fs->fsName, ENT_QUOTES, APP_CHARSET); ?>
		</option>
		<?php endforeach ?>

	</select>
</div>

<hr />
