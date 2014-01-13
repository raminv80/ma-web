{block name=body}
{* Define the function *} {function name=options_list level=0} 
	{foreach $opts as $opt}
		{if $fields.listing_id neq $opt.id}
			<option value="{$opt.id}" {if $fields.listing_parent_id eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1} {/if} 
		{/if}
	{/foreach} 
{/function}

<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row-fluid">
				<div class="span12">
					<fieldset>
						<legend>
							{if $fields.product_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><i class="icon-ok icon-white"></i> Save</a>
							{if $fields.product_id neq ""} 
								<a class="btn btn-success pull-right" href="./"> <i class="icon-plus icon-white"></i> Add New {$zone}</a> 
							{/if}
						</legend>
					</fieldset>
					<input type="hidden" value="product_id" name="field[1][tbl_product][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;" /> 
					<input type="hidden" value="{$fields.product_id}" name="field[1][tbl_product][{$cnt}][product_id]" id="product_id"> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>		
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
				<li><a href="#images" data-toggle="tab">Images</a></li>
				<li><a href="#attributes" data-toggle="tab">Attributes</a></li>
				<!-- <button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button> -->
			</ul>
		
			<div class="tab-content">
				<!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane active" id="details">
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_name">Name</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_name}" name="field[1][tbl_product][{$cnt}][product_name]" id="id_product_name" class="req" onchange="seturl(this.value);">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_listing">Category</label>
						</div>
						<div class="span9 controls">
							<select name="field[1][tbl_product][{$cnt}][product_listing_id]" id="id_product_listing">
								<option value="0">Select one</option> 
								{call name=options_list opts=$fields.options.product_listing_id}
							</select>
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_meta_description">Meta Description</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_meta_description}" name="field[1][tbl_product][{$cnt}][product_meta_description]" id="id_product_meta_description" style="width: 70%;">
						</div>
					</div>
		<!-- 			<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_meta_words">Microdata</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_microdata}" name="field[1][tbl_product][{$cnt}][product_microdata]" id="id_product_microdata" style="width: 70%;">
						</div>
					</div> -->
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_description">Description</label><br />
						</div>
						<div class="span9 controls">
							<textarea name="field[1][tbl_product][{$cnt}][product_description]" id="id_product_description" class="tinymce">{$fields.product_description}</textarea>
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_instock">In stock</label>
						</div>
						<div class="span9 controls">
							<input type="hidden" value="{if $fields.product_instock eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_instock]" class="value"> <input type="checkbox" {if $fields.product_instock eq 1 || $fields.product_id eq ""}checked="checked"
								{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_instock">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_gst">Incl. GST</label>
						</div>
						<div class="span9 controls">
							<input type="hidden" value="{if $fields.product_gst eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_gst]" class="value"> <input type="checkbox" {if $fields.product_gst eq 1 || $fields.product_id eq ""}checked="checked"
								{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_gst">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_price">Price</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_price}" name="field[1][tbl_product][{$cnt}][product_price]" id="id_product_price" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_specialprice">Special Price</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_specialprice}" name="field[1][tbl_product][{$cnt}][product_specialprice]" id="id_product_specialprice" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_weight">Weight (Kg)</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_weight}" name="field[1][tbl_product][{$cnt}][product_weight]" id="id_product_weight" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_width">Width (cm)</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_width}" name="field[1][tbl_product][{$cnt}][product_width]" id="id_product_width" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_height">Height (cm)</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_height}" name="field[1][tbl_product][{$cnt}][product_height]" id="id_product_height" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_length">Length (cm)</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_length}" name="field[1][tbl_product][{$cnt}][product_length]" id="id_product_length" class="double validDouble">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_order">Order</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.product_order}" name="field[1][tbl_product][{$cnt}][product_order]" id="id_product_order" class="number">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="id_product_published">Published</label>
						</div>
						<div class="span9 controls">
							<input type="hidden" value="{if $fields.product_published eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_published]" class="value"> <input type="checkbox" {if $fields.product_published eq 1}checked="checked"
								{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_published">
						</div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ IMAGES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="images">
					<!--  gallery -->
					 <div class="row-fluid control-group">
						<div class="span3"><label class="control-label" for="gallery_image_{$count}">Gallery Images</label><br/><label class="control-label small-txt" >Size: 250px Wide x 250px Tall</label></div>
						<div class="span9 controls" id="gallery">
							{counter start=1 skip=1 assign="count"}
							{foreach $fields.gallery as $item}
							<div class="row-fluid gallery_item" rel="{$count}">
								<div class="span4" id="gallery_{$count}">
									<input type="hidden" value="gallery_id" name="field[10][tbl_gallery][{$count}][id]" id="id" />
									<input type="hidden" value="{$item.gallery_id}" name="field[10][tbl_gallery][{$count}][gallery_id]" >
									<input type="hidden" value="{$item.gallery_file}" name="field[10][tbl_gallery][{$count}][gallery_file]" id="gallery_image_{$count}" >
									<input type="hidden" value="{$item.gallery_product_id}" name="field[10][tbl_gallery][{$count}][gallery_product_id]" class="fileinput">
									<input type="text" value="{$item.gallery_link}" name="field[10][tbl_gallery][{$count}][gallery_link]" class="fileinput" id="gallery_image_{$count}_link" >
									<span id="gallery_image_{$count}_file">{$item.gallery_file}</span>
								</div>
								<div class="span8">
									<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('gallery_image_{$count}','','')">Update</a><a href="{$item.gallery_link}" target="_blank"  class="btn btn-info marg-5r" id="gallery_image_{$count}_path">View</a><a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="deleteFileType('gallery_{$count}')">Delete</a>
								</div>
							</div>
							{counter}
							{/foreach}
						</div>
					</div>
					 <div class="row-fluid control-group">
						<div class="span3"></div>
						<div class="span9 controls">
							<div class="row-fluid">
								<div class="span12">
									<a href="javascript:void(0);" class="btn btn-info" onclick="getFileType('','gallery','{$fields.listing_id}')">Add New Image</a>
								</div>
							</div>
						</div>
					</div>
					<!--  gallery -->
				</div>
				<!--===+++===+++===+++===+++===+++ ATTRIBUTES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="attributes">
					
					<div class="offset9" style="margin-bottom: 22px;">
						<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="$('.attributes').hide();newAttribute();"> Add New Attribute</a>
					</div>
					<div id="attributes-wrapper">
					{assign var='attributeno' value=0}
					{foreach $fields.attribute as $attribute}
						{assign var='attributeno' value=$attributeno+1}
						{include file='form_attribute.tpl'}
					{/foreach}
					</div>
					<div class="row-fluid">
						<input type="hidden" value="{$attributeno}" id="attributeno">
						<div class="span11">
							<input type="hidden" id="error" name="error" value="0" />
							<script type="text/javascript">
		
								$(document).ready(function(){
									$('.attributes').hide();
									$('.attr_values').hide();
								});
							
								function newAttribute(){
									$('body').css('cursor','wait');
									var no = $('#attributeno').val();
									no++;
									$('#attributeno').val(no);
									$.ajax({
										type: "POST",
									    url: "/admin/includes/processes/load-template.php",
										cache: false,
										data: "template=form_attribute.tpl&attributeno="+no+"&attrvalueno=0",
										dataType: "html",
									    success: function(data, textStatus) {
									    	try{
									    		$('#attributes-wrapper').append(data);
									    		$('body').css('cursor','default');
									    		scrolltodiv('#attribute_wrapper'+no);
											}catch(err){ $('body').css('cursor','default'); }
									    }
									});
								}
		
								function toggleAttribute(ID){
									if ($('#attribute'+ID).is(':visible')){
										$('.attributes').hide();
									}else{
										$('.attributes').hide();
										$('#attribute'+ID).show();
									}
								}
								
								function toggleAttr_value(ID){
									if ($('#attr_value'+ID).is(':visible')){
										$('.attr_values').hide();
									}else{
										$('.attr_values').hide();
										$('#attr_value'+ID).show();
									}
								}
			
								function newAttr_value(attribute_no){
									$('body').css('cursor','wait');
									var attribute = new String(attribute_no);
									var no = $('#attr_valueno' + attribute).val();
									no++;
									$('#attr_valueno' + attribute).val(no);
									
									$.ajax({
										type: "POST",
									    url: "/admin/includes/processes/load-template.php",
										cache: false,
										data: "template=form_value.tpl&attributeno="+attribute_no+"&attrvalueno="+no,
										dataType: "html",
									    success: function(data, textStatus) {
									    	try{
									    		$('#attr_value-wrapper'+attribute_no).append(data);
									    		$('body').css('cursor','default');
									    		scrolltodiv('#attr_value_wrapper'+attribute_no+'-'+ no);
											}catch(err){ $('body').css('cursor','default'); }
									    }
									});
								}
								function deleteAttribute(rid){
									if (ConfirmDelete()) {
										var ID = 'attribute_wrapper'+rid;
										var count = $('#'+ID).attr('rel');
										var fieldno = $('#'+ID).attr('fieldno');
										var today = new Date();
										var dd = today.getDate();
										var mm = today.getMonth()+1;//January is 0!
										var yyyy = today.getFullYear(); 
										var hh = today.getHours();
										var MM = today.getMinutes();
										var ss = today.getSeconds();
										
										html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+fieldno+'][tbl_attribute]['+count+'][attribute_deleted]" />';
										$('#'+ID).append(html);
										var entno = $('.attribute_attr_values'+rid).length;
										
										for ( var i=1; i<=entno;i++){
											var elem = new String(rid*20+i);
											var del = 'attr_value_wrapper' + elem;
											//deleteAttr_value(del);
											var acount = $('#'+del).attr('rel');
											var afieldno = $('#'+del).attr('fieldno');
											html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+afieldno+'][tbl_attr_value]['+acount+'][attr_value_deleted]" />';
											$('#'+del).append(html);
											$('#'+del).css('display','none');
											$('#'+del).removeClass('attr_values');
										}
										
										$('#'+ID).css('display','none');
										$('#'+ID).removeClass('attributes');
									}else{ 
										return false;
									}
									
								}
								function deleteAttr_value(ID){
									if (ConfirmDelete()) {
										var count = $('#'+ID).attr('rel');
										var fieldno = $('#'+ID).attr('fieldno');
										var today = new Date();
										var dd = today.getDate();
										var mm = today.getMonth()+1;//January is 0!
										var yyyy = today.getFullYear(); 
										var hh = today.getHours();
										var MM = today.getMinutes();
										var ss = today.getSeconds();
										
										html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+fieldno+'][tbl_attr_value]['+count+'][attr_value_deleted]" />';
										$('#'+ID).append(html);
										$('#'+ID).css('display','none');
										$('#'+ID).removeClass('attr_values');
		
									}else{ 
										return false;
									}
								}
							
								function validate(){
									$('body').css('cursor','wait');
									var pass = validateForm();
									if(!pass){
										$('body').css('cursor','pointer');
										return false;
									}else{
										$('#Edit_Record').submit();
									}
								}

								function scrolltodiv(id){
									$('html,body').animate({
										   scrollTop: $(id).offset().top
										});
								}
							</script>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid control-group">
				<div class="form-actions">
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><i class="icon-ok icon-white"></i> Save</a>
					{if $fields.product_id neq ""} 
						<a class="btn btn-success pull-right" href="./"> <i class="icon-plus icon-white"></i> Add New {$zone}</a> 
					{/if}
				</div>
			</div>
		</form>
	</div>
</div>
<script>
function seturl(str){
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/urlencode.php",
		cache: false,
		data: "value="+encodeURIComponent(str),
		dataType: "json",
	    success: function(res, textStatus) {
	    	try{
	    		$('#id_product_url').val(res.url);
	    	}catch(err){ }
	    }
	});
}

$('.validDouble').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});

$('.modifier').keyup(function () {
	var modifierValue = this.value; 
	var productValue = parseFloat($('#id_product_'+ this.getAttribute('modify')).val());
     if ($.isNumeric(modifierValue)) {
    	var result = productValue + parseFloat(modifierValue);
    	$('#' + this.getAttribute('resultId')).html( '= '+result.toFixed(2));
    	
	} else {
		if (modifierValue != '-' && modifierValue != '+' && modifierValue != ''){
			_this.value= modifierValue.replace(/[^0-9\.]/g, '');
		} else if  (modifierValue == '') {
			$('#' + this.getAttribute('resultId')).html( '= '+productValue.toFixed(2));
		}
	} 
});

function displayResults(){ 
	$(".modifier").each(function(){	
		var modifierValue = this.value; 
		var productValue = parseFloat($('#id_product_'+ this.getAttribute('modify')).val());
	     if ($.isNumeric(modifierValue)) {
	    	var result = productValue + parseFloat(modifierValue);
	    	$('#' + this.getAttribute('resultId')).html( '= '+result.toFixed(2));
		} else {
			$('#' + this.getAttribute('resultId')).html( '= '+productValue.toFixed(2));
		} 
	});
}

$('#myTab a[href="#attributes"]').click(function () {
	displayResults();
})
    
</script>
{/block}
