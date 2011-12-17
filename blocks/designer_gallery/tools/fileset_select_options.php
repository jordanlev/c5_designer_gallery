<?php defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('file_set');
$fileSets = FileSet::getMySets();
?>

<option value="0"><?php echo t('--Choose Fileset--'); ?></option>
<?php foreach ($fileSets as $fs): ?>
	<option value="<?php echo $fs->fsID; ?>"><?php echo htmlentities($fs->fsName, ENT_QUOTES, APP_CHARSET); ?></option>
<?php endforeach ?>
