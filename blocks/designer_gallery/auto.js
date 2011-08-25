function ccmValidateBlockForm() {
	if ($('select#fsID').val() == '0') {
		ccm_addError('You must choose a file set.');
	}
	 
	return false;
}

var last_selected_fsid = 0;

$(document).ready(function() {
	$('select#fsID').change(function() {
		last_selected_fsid = this.value;
	});
	
	$('a#fileManagerLink').click(function() {
		openFileManager();
		return false;
	});
});

function openFileManager() {
	$.fn.dialog.open({
		width: '90%',
		height: '70%',
		modal: false,
		href: CCM_TOOLS_PATH + "/files/search_dialog",
		title: ccmi18n_filemanager.title,
		onClose: function () {
			refreshFilesetList(last_selected_fsid);
		}
	});
}

function refreshFilesetList(select_value) {
	var select = $('select#fsID');
	var value = (select_value == undefined) ? select.val() : select_value;
	last_selected_fsid = value;
	
	$.ajax({
		url: FILESETS_URL,
		dataType: 'html',
		success: function(response) {
			select.html(response);
			select.val(value);
		}
	});
}
