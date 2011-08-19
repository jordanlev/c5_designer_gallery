function ccmValidateBlockForm() {
	if ($('select#fsID').val() == '0') {
		ccm_addError('You must choose a file set.');
	}
	 
	return false;
}
