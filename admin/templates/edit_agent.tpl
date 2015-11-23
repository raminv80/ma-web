{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12 edit-page-header">
							<span class="edit-page-title">{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone} {if $cnt eq ""}{assign var=cnt value=0}{/if}</span>
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
							<div class="published" {if $fields.listing_published eq 0}style="display: none;"{/if}>
								<!-- PUBLISHED -->
								<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]',false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a> 
								<a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
							</div>
							<div class="drafts" {if $fields.listing_published eq 1}style="display: none;"{/if}>
								<!-- DRAFT -->
								<a href="javascript:void(0);" onClick="publish('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a> 
							</div>
							<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>

					<input type="hidden" value="listing_id" name="primary_id" id="primary_id" /> <input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" /> <input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_id"
						class="key"> <input type="hidden" value="{if $fields.listing_object_id}{$fields.listing_object_id}{else}{$objID}{/if}" name="field[1][tbl_listing][{$cnt}][listing_object_id]" id="listing_object_id"> <input type="hidden" value="{$typeID}"
						name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id"> <input type="hidden" value="{$rootParentID}" name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="listing_parent_id"> <input type="hidden" value="{$fields.listing_published}"
						name="field[1][tbl_listing][{$cnt}][listing_published]" id="listing_published"> <input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_flag1]" id="listing_flag1"> <input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_flag2]"
						id="listing_flag2"> <input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>
			<div class="row published" {if $fields.listing_published eq 0}style="display: none;"{/if}>
				<div class="alert alert-success text-center">
					<strong>PUBLISHED</strong>
				</div>
			</div>
			<div class="row drafts" {if $fields.listing_published eq 1}style="display: none;"{/if}>
				<div class="alert alert-info text-center">
					<strong>DRAFT</strong>
				</div>
			</div>
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
				<li><a href="#location" data-toggle="tab">Location</a></li>
				<li><a href="#files" data-toggle="tab">Files</a></li>
				<li><a href="#testimonials" data-toggle="tab">Testimonials</a></li>
				<li><a href="#tags" data-toggle="tab">Tags</a></li>
			</ul>

			<div class="tab-content">
				<!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane active" id="details">
					<div class="row form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_parent_flag">Is Parent?</label>
							<div class="col-sm-5">
								<input type="hidden" value="{if $fields.listing_parent_flag eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" class="value"> <input class="chckbx" type="checkbox" {if $fields.listing_parent_flag eq 1} checked="checked"
									{/if} 
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_parent_flag">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_display_menu">Display in Menu?</label>
							<div class="col-sm-5">
								<input type="hidden" value="{if $fields.listing_display_menu eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" class="value"> <input class="chckbx" type="checkbox" {if $fields.listing_display_menu eq 1} checked="checked"
									{/if}
									 onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_display_menu">
							</div>
						</div>

						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_name">Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" onchange="seturl(this.value);" required> <span class="help-block"></span>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_url">URL *</label>
							<div class="col-sm-5">
								<input class="form-control" type="hidden" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" onchange="seturl(this.value, true);" >
                <span id="id_listing_url_text" class="form-control url-text edit-url">{$fields.listing_url}&nbsp;</span>
                <a href="javascript:void(0);" class="btn btn-info btn-sm marg-5r edit-url" onclick="$('.edit-url').removeClass('url-text').hide();$('#id_listing_url').get(0).type='text';">Edit URL</a> 
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_seo_title">SEO Title *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" required> <span class="help-block"></span>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_meta_description">Meta Description</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.listing_meta_description}" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_meta_words">Meta Words</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_order">Order</label>
							<div class="col-sm-5">
								<input class="form-control number" type="text" value="{$fields.listing_order}" name="field[1][tbl_listing][{$cnt}][listing_order]" id="id_listing_order">
							</div>
						</div>
						<div class="row form-group">
              <hr/>
            </div>
						<div class="row form-group">
              <label class="col-sm-3 control-label" for="id_location_enquiry_recipient">Enquiry Recipient</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.location_enquiry_recipient}" name="field[2][tbl_location][{$cnt}][location_enquiry_recipient]" id="id_location_enquiry_recipient">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_location_order_recipient">Order Recipient</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.location_order_recipient}" name="field[2][tbl_location][{$cnt}][location_order_recipient]" id="id_location_order_recipient">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_location_bcc_recipient">BCC Recipient</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.location_bcc_recipient}" name="field[2][tbl_location][{$cnt}][location_bcc_recipient]" id="id_location_bcc_recipient">
              </div>
            </div>
            <div class="row form-group">
              <hr/>
            </div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_content3">Contact Person</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.listing_content3}" name="field[1][tbl_listing][{$cnt}][listing_content3]" id="id_listing_content3">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_location_phone">Phone</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.location_phone}" name="field[2][tbl_location][{$cnt}][location_phone]" id="id_location_phone">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_location_fax">Fax</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.location_fax}" name="field[2][tbl_location][{$cnt}][location_fax]" id="id_location_fax">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_location_email">Email</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.location_email}" name="field[2][tbl_location][{$cnt}][location_email]" id="id_location_email">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_content1">Trading Hours</label><br />
							<div class="col-sm-5">
								<textarea name="field[1][tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_content2">Description</label><br />
							<div class="col-sm-5">
								<textarea name="field[1][tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" class="tinymce">{$fields.listing_content2}</textarea>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_content4">Our community</label><br />
							<div class="col-sm-5">
								<textarea name="field[1][tbl_listing][{$cnt}][listing_content4]" id="id_listing_content4" class="tinymce">{$fields.listing_content4}</textarea>
							</div>
						</div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ LOCATION TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="location">
					<div class="row form" data-error="Error found on <b>Location tab</b>. View <b>Location tab</b> to see specific error notices.">
					<div class="row form-group">
						<label class="col-sm-3 control-label" for="id_location_street">Street Address</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" value="{$fields.location_street}" onchange="fillLocation();" name="field[2][tbl_location][{$cnt}][location_street]" id="id_location_street">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-3 control-label" for="id_location_suburb">Suburb</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" value="{$fields.location_suburb}" onchange="fillLocation();" name="field[2][tbl_location][{$cnt}][location_suburb]" id="id_location_suburb">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-3 control-label" for="id_location_state">State</label>
						<div class="col-sm-5">
							<select class="form-control" name="field[2][tbl_location][{$cnt}][location_state]" onblur="fillLocation();" id="id_location_state">
								<option value="">Select one</option>
								<option value="ACT" {if $fields.location_state eq 'ACT'}selected="selected"{/if} >ACT</option>
								<option value="NSW" {if $fields.location_state eq 'NSW'}selected="selected"{/if}>NSW</option>
								<option value="NT" {if $fields.location_state eq 'NT'}selected="selected"{/if}>NT</option>
								<option value="QLD" {if $fields.location_state eq 'QLD'}selected="selected"{/if}>QLD</option>
								<option value="SA" {if $fields.location_state eq 'SA'}selected="selected"{/if}>SA</option>
								<option value="TAS" {if $fields.location_state eq 'TAS'}selected="selected"{/if}>TAS</option>
								<option value="VIC" {if $fields.location_state eq 'VIC'}selected="selected"{/if}>VIC</option>
								<option value="WA" {if $fields.location_state eq 'WA'}selected="selected"{/if}>WA</option>
							</select>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-3 control-label" for="id_location_postcode">Postcode</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" value="{$fields.location_postcode}" onchange="fillLocation();" name="field[2][tbl_location][{$cnt}][location_postcode]" id="id_location_postcode">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-3 control-label" for="search">Location</label>
						<div class="col-sm-7">
							<input class="form-control location-input" type="text" value="" id="search">&nbsp;<a href="javascript:void(0);" class="btn btn-info" onclick="searchAddress($('#search').val());$('#search').val('')">Search</a>
							<div id="search-warning"></div>
							<input type="hidden" value="location_id" name="field[2][tbl_location][{$cnt}][id]" id="id" /> 
							<input type="hidden" value="listing_id" name="default[location_listing_id]" /> 
							<input type="hidden" value="{$fields.location_id}" name="field[2][tbl_location][{$cnt}][location_id]" class="key"> 
							<input type="hidden" value="{$fields.listing_id}" name="field[2][tbl_location][{$cnt}][location_listing_id]" id="location_listing_id" class="key"/>
							<input type="hidden" value="{$fields.location_latitude}" name="field[2][tbl_location][{$cnt}][location_latitude]" id="location_latitude">
							<input type="hidden" value="{$fields.location_longitude}" name="field[2][tbl_location][{$cnt}][location_longitude]" id="location_longitude">
							<div id="GmlMap" class="GmlMap">Loading Map....</div>
							<script type="text/javascript">
						          jQuery(document).ready(function() {
						            centerOn({$fields.location_latitude},{$fields.location_longitude});
						          });
						        </script>
							<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
							<script type="text/javascript" src="/admin/includes/google-api/gml-v3.js"></script>
							<link href='/admin/includes/google-api/gml-v3.css' rel='stylesheet' type='text/css'>
						</div>
					</div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ FILES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="files">
					<div class="row form" id="files-wrapper">{assign var='filesno' value=0} {assign var='gTableName' value='listing'} {foreach $fields.files as $files} {assign var='filesno' value=$filesno+1} {include file='files.tpl'} {/foreach}</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.files').slideUp();newFile();"> Add New File</a>
					</div>
					<input type="hidden" value="{$filesno}" id="filesno">
				</div>
				<!--===+++===+++===+++===+++===+++ TESTIMONIALS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="testimonials">
					<div class="row form" id="testimonials-wrapper">{assign var='testimonialsno' value=0} {assign var='gTableName' value='listing'} {foreach $fields.testimonials as $testimonials} {assign var='testimonialsno' value=$testimonialsno+1} {include file='testimonial.tpl'} {/foreach}</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.testimonials').slideUp();newTestimonial();"> Add New Testimonial</a>
					</div>
					<input type="hidden" value="{$testimonialsno}" id="testimonialsno">
				</div>
				<!--===+++===+++===+++===+++===+++ TAGS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="tags">
					<div class="form">
						<div class="row form-group">
							<label class="col-sm-2 control-label" for="new_tag">Tag</label>
							<div class="col-sm-5">
								<div class="ui-widget">
									<input class="form-control" id="new_tag"> <a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="newTag();"> Add Tag</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row form" id="tags-wrapper">{assign var='tagno' value=0} {assign var='table_name' value='tbl_listing'} {assign var='default' value='listing_id'} {foreach $fields.tags as $tag} {assign var='tagno' value=$tagno+1} {include file='tag.tpl'} {/foreach}</div>
					<input type="hidden" value="{$tagno}" id="tagno">
				</div>
			</div>
			<div class="row form-group form-bottom-btns">
				<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
				<div class="published" {if $fields.listing_published eq 0}style="display: none;"{/if}>
					<!-- PUBLISHED -->
					<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a> 
					<a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
				</div>
				<div class="drafts" {if $fields.listing_published eq 1}style="display: none;"{/if}>
					<!-- DRAFT -->
					<a href="javascript:void(0);" onClick="publish('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a> 
				</div>
				<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">

function seturl(str){
	seturl(str,false);
}
function seturl(str,editexisting){
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/urlencode.php",
		cache: false,
		data: "value="+encodeURIComponent(str),
		dataType: "json",
	    success: function(res, textStatus) {
	    	try{
	    		if($('#listing_id').val() == "" || editexisting == true){
	    		$('#id_listing_url').val(res.url);
	    		$('#id_listing_url_text').html(res.url);
	    		}
	    	}catch(err){ }
	    }
	});
}

$(document).ready(function(){

	$('#Edit_Record').validate({
		onkeyup: false
	});
	$('.services').hide();
	$('.files').hide();
	
	$('#id_listing_url').rules("add", {
    	  uniqueURL: { 
        	  	id: $('#listing_object_id').val(),
        	  	idfield: "listing_object_id",
	        	table : "tbl_listing",
	        	field : "listing_url",
	        	field2 : "listing_parent_id",
	        	value2 : "id_listing_parent"
		  }
	 });
});

function saveDraft(id_name,objId_name,publish_name, field_name, preview){
	if ($('#Edit_Record').valid()) { 
		$('body').css('cursor', 'wait');
		$('#'+publish_name).val('0');
		var id_key0 = encodeURIComponent(id_name+'[0]');
		var id_key1 = encodeURIComponent(id_name+'[1]');
		var objId_key = encodeURIComponent($('#'+objId_name).attr('name'));
		var publish_key = encodeURIComponent($('#'+publish_name).attr('name'));
		var field_key = encodeURIComponent(field_name);
		var field_value = encodeURIComponent(mysql_now());
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/processes-record.php",
			cache: false,
			async: false,
			data : id_key0+'='+objId_name+'&'+id_key1+'='+publish_name+'&'+objId_key+"="+$('#'+objId_name).val()+"&"+publish_key+"=0&"+field_key+"="+field_value+'&formToken='+$('#formToken').val(),
			dataType: "html",
			success : function(data, textStatus) {
				try {
					var obj = $.parseJSON(data);
					if(obj.notice){ 
						$('.key').val('');
						$('#Edit_Record').submit();
						$('.published').hide();
						$('.drafts').show();
						buildUrl('tbl_listing','listing_parent_id',objId_name, preview);
					}
				} catch (err) {}
				$('body').css('cursor', 'default');
			}
		});
		$('body').css('cursor', 'default');
	} 
}

function publish(id_name,objId_name,publish_name,field_name){
	if ($('#Edit_Record').valid()) { 
		$('body').css('cursor', 'wait');
		$('#'+publish_name).val('1');
		var id_key0 = encodeURIComponent(id_name+'[0]');
		var id_key1 = encodeURIComponent(id_name+'[1]');
		var objId_key = encodeURIComponent($('#'+objId_name).attr('name'));
		var publish_key = encodeURIComponent($('#'+publish_name).attr('name'));
		var field_key = encodeURIComponent(field_name);
		var field_value = encodeURIComponent(mysql_now());
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/processes-record.php",
			cache: false,
			data : id_key0+'='+objId_name+'&'+id_key1+'='+publish_name+'&'+objId_key+"="+$('#'+objId_name).val()+"&"+publish_key+"=1&"+field_key+"="+field_value+'&formToken='+$('#formToken').val(),
			dataType: "html",
			success : function(data, textStatus) {
				try {
					var obj = $.parseJSON(data);
					if(obj.notice){ 
						$('#Edit_Record').submit();
						$('.drafts').hide();
						$('.published').show();
					}
				} catch (err) {}
				$('body').css('cursor', 'default');
			}
		});
		$('body').css('cursor', 'default');
	} 
}

function unpublish(publish_name){
	$('#'+publish_name).val('0');
	$('#Edit_Record').submit();
	$('.published').hide();
	$('.drafts').show();
}


//START TESTIMONIALS
function newTestimonial() {
	$('body').css('cursor', 'wait');
	var no = $('#testimonialsno').val();
	no++;
	$('#testimonialsno').val(no);
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/load-template.php",
		cache : false,
		data : "template=testimonial.tpl&testimonialsno=" + no + "&gTableName=listing",
		dataType : "html",
		success : function(data, textStatus) {
			try {
				$('#testimonials-wrapper').append(data);
				$('body').css('cursor', 'default');
				scrolltodiv('#testimonial_wrapper' + no);
			} catch (err) {
				$('body').css('cursor', 'default');
			}
		}
	});
}

function toggleTestimonial(ID) {
	if ($('#testimonial' + ID).is(':visible')) {
		$('.testimonials').slideUp();
	} else {
		$('.testimonials').slideUp();
		$('#testimonial' + ID).slideDown();
	}
}

function deleteTestimonial(ID) {
	if (ConfirmDelete()) {
		var count = $('#' + ID).attr('rel');
		var today = mysql_now();

		html = '<input type="hidden" value="'+today+'" name="field[10][tbl_testimonial]['+count+'][testimonial_deleted]" />';
		$('#' + ID).append(html);
		$('#' + ID).css('display', 'none');
		$('#' + ID).removeClass('testimonials');
	} else {
		return false;
	}
}
//END TESTIMONIALS


function newFile() {
	$('body').css('cursor', 'wait');
	var no = $('#filesno').val();
	no++;
	$('#filesno').val(no);
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/load-template.php",
		cache : false,
		data : "template=files.tpl&filesno=" + no + "&gTableName=listing",
		dataType : "html",
		success : function(data, textStatus) {
			try {
				$('#files-wrapper').append(data);
				$('body').css('cursor', 'default');
				scrolltodiv('#file_wrapper' + no);
			} catch (err) {
				$('body').css('cursor', 'default');
			}
		}
	});
}

function toggleFile(ID) {
	if ($('#file' + ID).is(':visible')) {
		$('.files').slideUp();
	} else {
		$('.files').slideUp();
		$('#file' + ID).slideDown();
	}
}

function deleteFile(ID) {
	if (ConfirmDelete()) {
		var count = $('#' + ID).attr('rel');
		var today = mysql_now();

		html = '<input type="hidden" value="'+today+'" name="field[10][tbl_files]['+count+'][files_deleted]" />';
		$('#' + ID).append(html);
		$('#' + ID).css('display', 'none');
		$('#' + ID).removeClass('files');
	} else {
		return false;
	}
}

function newTag() {
	if ( $('#new_tag').val() != '' ) { 
		$('body').css('cursor', 'wait');
		var no = $('#tagno').val();
		no++;
		$('#tagno').val(no);
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/processes-tags.php",
			cache : false,
			data : "template=tag.tpl&tagno=" + no + "&tag%5Btag_value%5D="	+  $('#new_tag').val() + "&table_name=tbl_listing&default=listing_id&objId=" + $('#listing_id').val(),
			dataType : "html",
			success : function(data, textStatus) {
				try {
					$('#tags-wrapper').prepend(data);
					$('#new_tag').val('');
					$('body').css('cursor', 'default');
					$('#new_tag').closest('.form-group').removeClass('has-success').removeClass('has-error');
				} catch (err) {
					$('body').css('cursor', 'default');
				}
			}
		});
	} else {
		$('#new_tag').closest('.form-group').removeClass('has-success').addClass('has-error');
	}
}

function deleteTag(ID) {
	if (ConfirmDelete()) {
		var count = $('#' + ID).attr('rel');
		var today = mysql_now();

		html = '<input type="hidden" value="'+today+'" name="field[15][tbl_tag]['+count+'][tag_deleted]" />';
		$('#' + ID).append(html);
		$('#' + ID).css('display', 'none');
		$('#' + ID).removeClass('tags');
	} else {
		return false;
	}
}

function fillLocation(){
	
	var loc_str = $('#id_location_street').val() + ', ' + $('#id_location_suburb').val() + ', ' + $('#id_location_state option:selected').val() + ' ' + $('#id_location_postcode').val();
	$('#search').val(loc_str); 
}
          
$('#myTab a[href="#location"]').click(function() {
	setTimeout(function(){
		google.maps.event.trigger(map,'resize');
		fillLocation();
	},1000);
})

</script>
{/block}
