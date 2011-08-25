<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<table border="0"><tr><td align="right">
	<strong>File Set:</strong>
</td><td>
	<select id="fsID" name="fsID">
		<option value="0">Loading...</option>
	</select>
</td></tr><tr><td>&nbsp;</td><td style="padding: 5px 0 0 5px; font-weight: bold;">
	[<a href="#" id="fileManagerLink">Open File Manager&hellip;</a>]
</td></tr></table>

<hr />

<script type="text/javascript">
var FILESETS_URL = '<?php echo $filesetsToolURL ?>';
refreshFilesetList(<?php echo $fsID ?>);
</script>
