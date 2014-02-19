{block name=body}
{* Define the function *} {function name=options_list level=0} 
	{foreach $opts as $opt}
		{if $fields.listing_id neq $opt.id}
			<option value="{$opt.id}" {if $fields.product_listing_id eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1} {/if} 
		{/if}
	{/foreach} 
{/function}

<div class="row">
	<div class="col-sm-12" >
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend>
							{if $fields.product_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"> Save</a>
							{if $fields.product_id neq ""} 
								<!-- <a class="btn btn-success pull-right" href="./"> <i class="icon-plus icon-white"></i> Add New {$zone}</a>  -->
							{/if}
						</legend>
					</fieldset>
					<input type="hidden" value="product_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="product_id" name="field[1][tbl_product][{$cnt}][id]" id="id"/> 
					<input type="hidden" value="{$fields.product_id}" name="field[1][tbl_product][{$cnt}][product_id]" id="product_id"> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>		
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
				<li><a href="#pricing" data-toggle="tab">Pricing</a></li>
				<li><a href="#images" data-toggle="tab">Images</a></li>
				<li><a href="#attributes" data-toggle="tab">Attributes</a></li>
				<!-- <button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button> -->
			</ul>
		
			<div class="tab-content">
				<!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane active" id="details">
					<div class="row form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_name">Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.product_name}" name="field[1][tbl_product][{$cnt}][product_name]" id="id_product_name" onchange="seturl(this.value);" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
								<label class="col-sm-3 control-label" for="id_product_url">URL *</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_url}" name="field[1][tbl_product][{$cnt}][product_url]" id="id_product_url" required>
								<span class="help-block"></span>
							</div>
						</div>		
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_listing">Category</label>
							<div class="col-sm-5 ">
								<select  class="form-control" name="field[1][tbl_product][{$cnt}][product_listing_id]" id="id_product_listing">
									<option value="{$rootParentID}">Select one</option> 
									{call name=options_list opts=$fields.options.product_listing_id}
								</select>
							</div>
						</div>			
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_seo_title">SEO Title *</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_seo_title}" name="field[1][tbl_product][{$cnt}][product_seo_title]" id="id_product_seo_title" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_meta_description">Meta Description</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_meta_description}" name="field[1][tbl_product][{$cnt}][product_meta_description]" id="id_product_meta_description">
							</div>
						</div>					
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_meta_words">Meta Words</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_meta_words}" name="field[1][tbl_product][{$cnt}][product_meta_words]" id="id_product_meta_words">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_description">Description</label><br />
							<div class="col-sm-5 ">
								<textarea name="field[1][tbl_product][{$cnt}][product_description]" id="id_product_description" class="tinymce">{$fields.product_description}</textarea>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_order">Order</label>
							<div class="col-sm-5 ">
								<input class="form-control number" type="text" value="{$fields.product_order}" name="field[1][tbl_product][{$cnt}][product_order]" id="id_product_order">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_published">Published</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.product_published eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_published]" class="value"> 
								<input class="chckbx" type="checkbox" {if $fields.product_published eq 1}checked="checked"{/if} 
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_published">
							</div>
						</div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ PRICING TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="pricing">
					<div class="row form" data-error="Error found on <b>Pricing tab</b>. View <b>Pricing tab</b> to see specific error notices.">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_price">Price ($)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_price}" name="field[1][tbl_product][{$cnt}][product_price]" id="id_product_price">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_specialprice">Special Price ($)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_specialprice}" name="field[1][tbl_product][{$cnt}][product_specialprice]" id="id_product_specialprice">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_weight">Weight (Kg)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_weight}" name="field[1][tbl_product][{$cnt}][product_weight]" id="id_product_weight">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_width">Width (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_width}" name="field[1][tbl_product][{$cnt}][product_width]" id="id_product_width">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_height">Height (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_height}" name="field[1][tbl_product][{$cnt}][product_height]" id="id_product_height">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_length">Length (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_length}" name="field[1][tbl_product][{$cnt}][product_length]" id="id_product_length">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_gst">Incl. GST</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.product_gst eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_gst]" class="value">
								<input class="chckbx" type="checkbox" {if $fields.product_gst eq 1 || $fields.product_id eq ""}checked="checked" {/if} 
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_gst">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_instock">In stock</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.product_instock eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_instock]" class="value"> 
								<input class="chckbx" type="checkbox" {if $fields.product_instock eq 1 || $fields.product_id eq ""}checked="checked" {/if} 
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_instock">
							</div>
						</div>
					</div>
				</div>
				
				<!--===+++===+++===+++===+++===+++ IMAGES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="images">
					<!--  gallery -->
					
					<!-- <div class="row form-group" id="select-hero-img">
							<label class="col-sm-3 control-label" for="id_product_hero_image">Hero Image</label>
						<div class="col-sm-5 ">
							<select  class="form-control" name="product_hero_image" id="id_product_hero_image">
								{assign var='cntlst' value=0}
								{foreach $fields.gallery as $opt}
									{assign var='cntlst' value=$cntlst+1}
									<option value="{$cntlst}" {if $opt.gallery_ishero eq '1'}selected="selected"{/if}>{$opt.gallery_file}</option> 
								{/foreach}
							</select>
						</div>
					</div>	 -->
					
					
					<div class="row form" id="images-wrapper">
						{assign var='imageno' value=0}
						{foreach $fields.gallery as $images}
							{assign var='imageno' value=$imageno+1}
							{include file='gallery.tpl'}
						{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.images').slideUp();newImage();"> Add New Image</a>
					</div>
					<div class="row">
						<input type="hidden" value="{$imageno}" id="imageno">
						<div class="col-md-11">
							<input type="hidden" id="error" name="error" value="0" />
						</div>
					</div>
					<!--  gallery -->
				</div>
				<!--===+++===+++===+++===+++===+++ ATTRIBUTES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="attributes">
					<div class="row form" id="attributes-wrapper">
					{assign var='attributeno' value=0}
					{foreach $fields.attribute as $attribute}
						{assign var='attributeno' value=$attributeno+1}
						{include file='form_attribute.tpl'}
					{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.attributes').slideUp();newAttribute();"> Add New Attribute</a>
					</div>
					<div class="row">
						<input type="hidden" value="{$attributeno}" id="attributeno">
						<div class="col-md-11">
							<input type="hidden" id="error" name="error" value="0" />
						</div>
					</div>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-top: 50px;"> Save</a>
				</div>
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">


$(document).ready(function(){

	
	$('#Edit_Record').validate({
		onkeyup: false
	});
	
	
	$('#id_product_url').rules("add", {
		uniqueURLProduct: { id: "{if $fields.product_id}{$fields.product_id}{else}0{/if}" }
	 });

		
	$('.images').hide();

	if ($('#imageno').val() == 0 ) {
		$('#select-hero-img').hide();
	}

	$('.attributes').hide();
	$('.attr_values').hide();

	$('#id_product_hero_image').change(function() {
		$('.ishero').val('0');
		$('#gallery_ishero_'+ $(this).val()).val('1');
	});

});


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
		$('.attributes').slideUp();
	}else{
		$('.attributes').slideUp();
		$('#attribute'+ID).slideDown();
	}
}

function toggleAttr_value(ID){
	if ($('#attr_value'+ID).is(':visible')){
		$('.attr_values').slideUp();
	}else{
		$('.attr_values').slideUp();
		$('#attr_value'+ID).slideDown();;
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
	    		displayResults();
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

function newImage(){
	$('body').css('cursor','wait');
	var no = $('#imageno').val();
	no++;
	$('#imageno').val(no);
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/load-template.php",
		cache: false,
		data: "template=gallery.tpl&imageno="+no,
		dataType: "html",
	    success: function(data, textStatus) {
	    	try{
	    		$('#images-wrapper').append(data);
	    		$('body').css('cursor','default');
	    		scrolltodiv('#image_wrapper'+no);
	    		if (no == 1) { 
	    			$('#gallery_ishero_1').val('1');
	    		}
    			$('#select-hero-img').show();
	    		$('#id_product_hero_image').append($('<option>', {
	    		    value: no,
	    		    text: 'Image #'+no
	    		}));
			}catch(err){ $('body').css('cursor','default'); }
	    }
	});
}

function toggleImage(ID){
	if ($('#image'+ID).is(':visible')){
		$('.images').slideUp();
	}else{
		$('.images').slideUp();
		$('#image'+ID).slideDown();
	}
}

function deleteImage(ID){
	if (ConfirmDelete()) {
		var count = $('#'+ID).attr('rel');
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear(); 
		var hh = today.getHours();
		var MM = today.getMinutes();
		var ss = today.getSeconds();
		
		html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[10][tbl_gallery]['+count+'][gallery_deleted]" />';
		$('#'+ID).append(html);
		$('#'+ID).css('display','none');
		$('#'+ID).removeClass('images');
		$('#id_product_hero_image option[value='+ count +']').remove();
		$('#id_product_hero_image').trigger('change');
		if ($('#id_product_hero_image option').length == 0) {
			$('#select-hero-img').hide();
		}
	}else{ 
		return false;
	}
}


function displayResults(){ 
	$(".modifier").each(function(i){	
		refreshResult(this.id); 
	});
}

function refreshResult(ID){
	var obj = document.getElementById(ID);
	var modifierValue = obj.value; 
	var productValue = parseFloat($('#id_product_'+ obj.getAttribute('modify')).val());
     if ($.isNumeric(modifierValue)) {
    	var result = productValue + parseFloat(modifierValue);
    	$(obj).closest('.form-group').find('.form-help-value').text( result.toFixed(2));
	} else {
		$(obj).closest('.form-group').find('.form-help-value').text( productValue.toFixed(2));
	} 
}




$('#myTab a[href="#attributes"]').click(function () {
	displayResults();
})
    
</script>
{/block}
