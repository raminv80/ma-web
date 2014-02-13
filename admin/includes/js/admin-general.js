/**file types ***/
var elf='';
function getFileType(ID,parent,listing_id){
	if (elf != '') {
		$('#elfinder').elfinder('close').elfinder('destroy');
	}
	elf = $('#elfinder').elfinder({
			url : '/admin/includes/fileManager/php/connector.php',
			getFileCallback : function(file) {
		   	    
				$('#'+ID).val(file.name);
				$('#'+ID+'_link').val('/'+file.path);
				$('#'+ID+'_file').html(file.name);
		        $('#'+ID+'_path').html('<a href="/'+file.path+'" target="_blank" >View</a>');
		        $('#'+ID+'_preview').html('<img src="/'+file.path+'" alt="preview-image" height="50px" width="50px">');
				
				elf = '';
				$('#elfinder').elfinder('close').elfinder('destroy');
				$('#modal-elfinder').modal('hide');
	        },
	        dialog : { title : 'files', modal : true, height: 900 },
	        resizable: true
	}).elfinder('instance');
	$('#elfinder').elfinder('open');
	$('#modal-elfinder').modal('show');

	/*$('html, body').animate({
        scrollTop: $('#elfinder').offset().top
    }, 2000);*/
}


function deleteFileType(ID){
	var count = $('#'+ID).parent().attr('rel');
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1;//January is 0!
	var yyyy = today.getFullYear(); 
	var hh = today.getHours();
	var MM = today.getMinutes();
	var ss = today.getSeconds();
	
	html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[1][tbl_gallery]['+count+'][gallery_deleted]" />';
	$('#'+ID).append(html);
	$('#'+ID).parent().css('display','none');
}


function deleteInspection(ID){
	if (ConfirmDelete()) {
		var count = $('#'+ID).attr('rel');
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear(); 
		var hh = today.getHours();
		var MM = today.getMinutes();
		var ss = today.getSeconds();
		
		html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[1][tbl_inspection]['+count+'][inspection_deleted]" />';
		$('#'+ID).append(html);
		$('#'+ID).css('display','none');
	}else{ 
		return false;
	}

}

/**tool tips ***/
$('input[type=text][class=tool-tip]').tooltip({ 
    placement: "right",
    trigger: "focus"
});
/** confirm **/
function ConfirmDelete(){
	 return confirm("Do you want to remove this item ?");
}