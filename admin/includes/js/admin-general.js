/**file types ***/
var elf='';
function getFileType(ID,parent,listing_id){
	elf = $('#elfinder').elfinder({
			url : '/admin/includes/fileManager/php/connector.php',
			getFileCallback : function(file) {
		   	    if(ID == ""){
					var last = $('#'+parent).find('.gallery_item:last-child');
					var count = $('#'+parent).find('.gallery_item:last-child').attr('rel');
					if(count === undefined){
						count=0;
					}
					count++;
					var html = '';
					html += '<div class="row-fluid gallery_item" rel="'+count+'">';
					html += '<div class="span4" id="gallery_'+count+'" >';
						html += '<input type="hidden" value="gallery_id" name="field[tbl_gallery]['+count+'][id]" id="id" />';
						html += '<input type="hidden" value="" name="field[tbl_gallery]['+count+'][gallery_id]" >';
						html += '<input type="hidden" value="'+file.name+'" name="field[tbl_gallery]['+count+'][gallery_file]" id="gallery_image_{$count}" >';
						html += '<input type="hidden" value="'+listing_id+'" name="field[tbl_gallery]['+count+'][gallery_listing_id]" class="fileinput">';
						html += '<input type="text" value="/'+file.path+'" name="field[tbl_gallery]['+count+'][gallery_link]" class="fileinput">';
						html += '<span id="gallery_image_'+count+'_file">'+file.name+'</span>';
					html += '</div>';
					html += '<div class="span8">';
					html += '<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType(\'gallery_image_'+count+'\',\'\',\'\')">Update</a>';
					html += '<a href="/'+file.path+'" target="_blank" class="btn btn-info marg-5r" id="gallery_image_'+count+'_path">View</a>';
					html += '<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="deleteFileType(\'gallery_'+count+'\')">Delete</a>';
					html += '</div>';
					html += '</div>';
					$('#'+parent).append(html);
				}else{
					$('#'+ID).val(file.name);
					$('#'+ID+'_file').html(file.name);
			        $('#'+ID+'_path').attr('href','/'+file.path);
			        $('#'+ID+'_path').html('View');
				}
				elf = '';
				$('#elfinder').elfinder('close').elfinder('destroy');
	        },
	        dialog : { title : 'files', modal : true, height: 900 },
	        resizable: true
	}).elfinder('instance');
	$('#elfinder').elfinder('open');
	$('html, body').animate({
        scrollTop: $('#elfinder').offset().top
    }, 2000);
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
	
	html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[tbl_gallery]['+count+'][gallery_deleted]" />';
	$('#'+ID).append(html);
	$('#'+ID).parent().css('display','none');
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