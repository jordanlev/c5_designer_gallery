function ccmValidateBlockForm() {
	if ($('select#fsID').val() == '0') {
		ccm_addError(ccm_t('fileset-required'));
	}
}

//Fileset Dropdown List...
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
    		href: CCM_TOOLS_PATH + "/files/search_dialog?disable_choose=1",
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

//Event handlers for image size fields...
    $(document).ready(function() {
    	//If 'original size' is chosen from dropdown, clear out width/height values.
    	$('#cropLarge').change(function() {
    		var val = parseInt($(this).val());
    		if (val == -1) {
    			$('#largeWidth, #largeHeight').val('');
    		}
    	});
	
    	//If width/height values are both empty, set dropdown to 'original size'.
    	//If either width or height (or both) has a non-empty value, ensure dropdown isnn't on 'original size'.
    	$('#largeWidth, #largeHeight').blur(function() {
    		var isEmptySizes = (($('#largeWidth').val().length == 0) && ($('#largeHeight').val().length == 0));
    		if (isEmptySizes) {
    			$('#cropLarge').val('-1');
    		} else if ($('#cropLarge').val() == '-1') {
    			$('#cropLarge').val('0');
    		}
    	});
    });