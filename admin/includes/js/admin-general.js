/**file types ***/
function getFileType(ID){
  CKFinder.popup( {
    chooseFiles: true,
    width: 800,
    height: 600,
    onInit: function( finder ) {
      finder.on( 'files:choose', function( evt ) {
        var file = evt.data.files.first();
        var fileUrl = file.getUrl();

        $('#'+ID).val(file.attributes.name);
        $('#'+ID+'_link').val(''+fileUrl);
        $('#'+ID+'_file').html(file.name);
        $('#'+ID+'_path').html('<a href="'+fileUrl+'" target="_blank" >View</a>');
        $('#'+ID+'_preview').html('<img src="'+fileUrl+'" alt="preview-image" height="50px" width="50px">');
			});
      finder.on( 'file:choose:resizedImage', function( evt ) {
        var file = evt.data.files.first();
        var fileUrl = evt.data.resizedUrl;

        $('#'+ID).val(file.attributes.name);
        $('#'+ID+'_link').val(''+fileUrl);
        $('#'+ID+'_file').html(file.name);
        $('#'+ID+'_path').html('<a href="'+fileUrl+'" target="_blank" >View</a>');
        $('#'+ID+'_preview').html('<img src="'+fileUrl+'" alt="preview-image" height="50px" width="50px">');
			});
		}
	});
}




function buildUrl(table_name,parent_fieldname,object_id,preview){
	var str = '';
	if(object_id != ''){
		str = '&objid='+$('#'+object_id).val();
	}
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/processes-build-url.php",
		cache: false,
		async: false,
		data : 'table='+table_name+'&parentfield='+parent_fieldname+str,
		dataType: "html",
		success : function(data, textStatus) {
			try {
				var obj = $.parseJSON(data);
				if(preview && obj.url){
					window.open('https://'+(document.domain)+obj.url,'_blank');
				}
			} catch (err) {}
		}
	});
}



function mysql_now(){
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1;//January is 0!
	var yyyy = today.getFullYear(); 
	var hh = today.getHours();
	var MM = today.getMinutes();
	var ss = today.getSeconds();
	
	return yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss;
}

/**tool tips ***/
$('input[type=text][class=tool-tip]').tooltip({ 
    placement: "right",
    trigger: "focus"
});

function ConfirmDelete(){
	 return confirm("Do you want to remove this item?");
}

function convert_to_mysql_date_format(str){
	var dateArr = str.split("/");
	return dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]; 
}
	
function setDateValue(id,date){
	$("#"+id).val( convert_to_mysql_date_format(date) );
}

function scrolltodiv(ID){
	$('html,body').animate({
		scrollTop : $(ID).offset().top
	});
}

function ToggleAccordionElem(ID, CLASS) {
  if($('#'+ID).is(':visible')){
    $('.'+CLASS).slideUp();
  }else{
    $('.'+CLASS).slideUp();
    $('#'+ID).slideDown();
  }
}

function SelectToJSONsync(ELEMENT){ 
  $(ELEMENT).hide();
  $(ELEMENT).closest('.form-group').find('.json-multiselect option').remove().end();
  var jsonStr = $(ELEMENT).closest('.form-group').find('.json-value').val();
  var values = jsonStr ? JSON.parse( jsonStr ) : [];
  
  //Build array .json-key-value
  $(".json-key-value").each(function(index, val){  
    var p = $(val).closest('.attr_values').find('.json-addkey').val();
    var v = $(val).val();
    if(v > 0 && p > 0){ 
     var selected = '';
     if($.inArray(v, values) != -1){
         selected = 'selected="selected"';
     };
     var n = $(val).closest('.attr_values').find('.json-name-value').val();
     $(ELEMENT).closest('.form-group').find('.json-multiselect').append('<option value="' + v + '" ' + selected + '>' + n + '</option>');
    }
 });
  $(ELEMENT).closest('.form-group').find('.json-multiselect').fadeIn();
};


function addEditor(CLASSID){
  $( 'textarea.'+CLASSID ).ckeditor({
        filebrowserBrowseUrl: "/admin/includes/ckfinder/ckfinder.html",
        filebrowserUploadUrl: "/admin/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files"
  });

}

function tinyMCEinit(CLASSID){
  //for compatibility only: migrated to ckEditor
  addEditor(CLASSID);
}

$(document).ready(function(){
  addEditor('tinymce');
});
