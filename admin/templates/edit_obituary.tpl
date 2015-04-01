{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12 edit-page-header">
							<span class="edit-page-title">{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone}</span>
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
							<div class="published" {if $fields.listing_published eq 0}style="display:none;"{/if}>
								<!-- PUBLISHED -->
								<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]',false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
								<a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
							</div>
							<div class="drafts" {if $fields.listing_published eq 1}style="display:none;"{/if}>
								<!-- DRAFT -->
								<a href="javascript:void(0);" onClick="publish('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
							</div>
							<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
					<input type="hidden" value="listing_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" /> 
					<input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_id" class="key"> 
					<input type="hidden" value="{if $fields.listing_object_id}{$fields.listing_object_id}{else}{$objID}{/if}" name="field[1][tbl_listing][{$cnt}][listing_object_id]" id="listing_object_id"> 
					<input type="hidden" value="{if $fields.listing_created}{$fields.listing_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_listing][{$cnt}][listing_created]" id="listing_created">
					<input type="hidden" value="{if $typeID}{$typeID}{else}1{/if}" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
					<input type="hidden" value="{$rootParentID}" name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="id_listing_parent">
					<input type="hidden" value="{$fields.listing_published}" name="field[1][tbl_listing][{$cnt}][listing_published]" id="listing_published">
					<input type="hidden" name="formToken" id="formToken" value="{$token}"/>
					<input type="hidden" value="listing_id" name="default[obituary_listing_id]" />
					<input type="hidden" value="obituary_id" name="field[1][tbl_obituary][{$cnt}][id]"  /> 
					<input type="hidden" value="{$fields.obituary_id}" name="field[1][tbl_obituary][{$cnt}][obituary_id]" id="obituary_id" class="key">
					<input type="hidden" value="{$fields.obituary_listing_id}" name="field[1][tbl_obituary][{$cnt}][obituary_listing_id]" id="obituary_listing_id" class="key"> 
				</div>
			</div>
			<div class="row published" {if $fields.listing_published eq 0}style="display:none;"{/if}>
				<div class="alert alert-success text-center">
					<strong>PUBLISHED</strong> 
				</div>
			</div>
			<div class="row drafts" {if $fields.listing_published eq 1}style="display:none;"{/if}>
				<div class="alert alert-info text-center">
					<strong>DRAFT</strong>
				</div>
			</div>
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
				<li><a href="#images" data-toggle="tab">Images</a></li>
        		<li><a href="#share" data-toggle="tab">Social Sharing</a></li>
			<!-- 	<li><a href="#files" data-toggle="tab">Files</a></li> -->
        		<li><a href="#guestbook" data-toggle="tab">Guestbook Messages</a></li>

				<li><a href="#log" data-toggle="tab">Log</a></li>
			</ul>
		
			<div class="tab-content">
				<!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane active" id="details">
					<div class="row form" data-error="Error found on Details tab. View Details tab to see specific error notices.">
<!-- Obituary inputs -->
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_prefix">Title/Prefix</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_prefix}" name="field[1][tbl_obituary][{$cnt}][obituary_prefix]" id="id_obituary_prefix" >
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_name">Deceased's Given Names *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_name}" name="field[1][tbl_obituary][{$cnt}][obituary_name]" id="id_obituary_name" onchange="setname();" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_surname">Deceased's Family Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_surname}" name="field[1][tbl_obituary][{$cnt}][obituary_surname]" id="id_obituary_surname" onchange="setname();" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_suffix">Suffix</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_suffix}" name="field[1][tbl_obituary][{$cnt}][obituary_suffix]" id="id_obituary_suffix" >
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_born">Born</label>
							<div class="col-sm-5">
								<input class="form-control dates" type="text" value="{if $fields.obituary_born neq '0000-00-00'}{$fields.obituary_born|date_format:"%d/%m/%Y"}{/if}"  name="born" id="born" onchange="setDateValue('id_obituary_born',this.value);">
								<input type="hidden" value="{if $fields.obituary_born}{$fields.obituary_born}{/if}" name="field[1][tbl_obituary][{$cnt}][obituary_born]" id="id_obituary_born">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_died">Died</label>
							<div class="col-sm-5">
								<input class="form-control dates" type="text" value="{if $fields.obituary_died neq '0000-00-00'}{$fields.obituary_died|date_format:"%d/%m/%Y"}{/if}"  name="died" id="died" onchange="setDateValue('id_obituary_died',this.value);">
								<input type="hidden" value="{if $fields.obituary_died}{$fields.obituary_died}{/if}" name="field[1][tbl_obituary][{$cnt}][obituary_died]" id="id_obituary_died">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_service_date">Service Date *</label>
							<div class="col-sm-5">
								<input class="form-control dates" type="text" value="{if $fields.obituary_service_date neq '0000-00-00'}{$fields.obituary_service_date|date_format:"%d/%m/%Y"}{/if}"  name="service_date" id="service_date" onchange="setDateValue('id_obituary_service_date',this.value);" required>
								<input type="hidden" value="{if $fields.obituary_service_date}{$fields.obituary_service_date}{/if}" name="field[1][tbl_obituary][{$cnt}][obituary_service_date]" id="id_obituary_service_date">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_service_time">Service Time *</label>
							<input type="hidden" value="{if $fields.obituary_service_time}{$fields.obituary_service_time}{/if}" name="field[1][tbl_obituary][{$cnt}][obituary_service_time]" id="id_obituary_service_time">
							<div class="col-sm-4">
								<!-- <input type="number" placeholder="HH" value="{$fields.obituary_service_time|date_format:"%I"}" name="hour" id="hour" min="1" max="12" class="form-control times" required>
								<input type="number" placeholder="MM" value="{$fields.obituary_service_time|date_format:"%M"}" name="minute" id="minute" min="1" max="59" class="form-control times" required> -->
								<select id="hour" name="hour" class="form-control required times">
									{assign var='selTime' value=$fields.obituary_service_time|date_format:"%k"}
									{assign var='selNotation' value='am'}
									{if $selTime gt 12}{assign var='selTime' value=$selTime - 12}{assign var='selNotation' value='pm'}{/if}
		  						<option value="">HH</option>
		  						{for $time=1 to 12}
		  							<option value="{$time}" {if $selTime eq $time}selected="selected"{/if}>{$time}</option> 
		  						{/for}
		  					</select>
								<select id="minute" name="minute" class="form-control required times">
									
		  						{for $time=0 to 59}
		  							<option value="{$time}" {if $fields.obituary_service_time|date_format:"%M" eq $time}selected="selected"{/if}>{if $time lt 10}0{/if}{$time}</option> 
		  						{/for}
		  					</select>
								
								<select id="notation" name="notation" class="form-control times">
		  						<option value="am" {if $selNotation eq 'am'}selected="selected"{/if}>am</option> 
		  						<option value="pm" {if $selNotation eq 'pm'}selected="selected"{/if}>pm</option> 
		  					</select>
							</div>
						</div>
						
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_kin_name">Next of kin's name</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_kin_name}" name="field[1][tbl_obituary][{$cnt}][obituary_kin_name]" id="id_obituary_kin_name">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_kin_phone">Next of kin's phone</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.obituary_kin_phone}" name="field[1][tbl_obituary][{$cnt}][obituary_kin_phone]" id="id_obituary_kin_phone">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_kin_email">Next of kin's email</label>
							<div class="col-sm-5">
								<input class="form-control" type="email" value="{$fields.obituary_kin_email}" name="field[1][tbl_obituary][{$cnt}][obituary_kin_email]" id="id_obituary_kin_email">
							</div>
						</div>
						
						<div class="row form-group">
              				<label class="col-sm-3 control-label" for="id_listing_content5">Obituary/Biography</label>
              				<div class="col-sm-5">
                				<textarea name="field[1][tbl_listing][{$cnt}][listing_content5]" id="id_listing_content5" class="tinymce">{$fields.listing_content5}</textarea>
              				</div>
            			</div>
						<div class="row" style="border-top: 1px solid #000">
							<div class="col-sm-offset-3 col-sm-9">
								<h4>Website Values</h4>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_listing_name">Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="hidden" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" onchange="seturl(this.value);" required>
                				<span id="id_listing_name_text" class="form-control name-text edit-name">{$fields.listing_url}&nbsp;</span>
                				<a href="javascript:void(0);" class="btn btn-info btn-sm marg-5r edit-name" onclick="$('.edit-name').removeClass('name-text').hide();$('#id_listing_name').get(0).type='text';">Edit Name</a> 
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
								<input class="form-control" type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" required>
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

					</div>
				</div>
		<!--===+++===+++===+++===+++===+++ SHARE TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="share">
          <div class="row form" data-error="Error found on <b>Social Sharing tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_share_title">Share Title</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.listing_share_title}" name="field[1][tbl_listing][{$cnt}][listing_share_title]" id="id_listing_share_title" >
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
                <label class="col-sm-3 control-label" for="listing_share_image">Share Image<br>
                <small>Size: 1200px Wide x 630px Tall (less than 1Mb) <br>("None" for default image)</small></label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.listing_share_image}" name="field[1][tbl_listing][{$cnt}][listing_share_image]" id="listing_share_image_link" class="fileinput"> 
                <span class="file-view" id="listing_share_image_path"> {if $fields.listing_share_image}<a href="{$fields.listing_share_image}" target="_blank" >View</a>{else}None{/if} </span> 
                <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('listing_share_image','','');">Select File</a> 
                <a href="javascript:void(0);" class="btn btn-info" onclick="$('#listing_share_image_link').val('');$('#listing_share_image_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_share_text">Share Text <br><span class="small">(120 Characters)</span></label>
              <div class="col-sm-5">
                <textarea class="form-control"name="field[1][tbl_listing][{$cnt}][listing_share_text]" id="id_listing_share_text" maxlength="120">{$fields.listing_share_text}</textarea>
              </div>
            </div>
          </div>
        </div>
				<!--===+++===+++===+++===+++===+++ IMAGES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="images">
					<div class="form" id="images-wrapper">
						{assign var='imageno' value=0}
						{assign var='gTableName' value='listing'}
						{foreach $fields.gallery as $images}
							{assign var='imageno' value=$imageno+1}
							{include file='gallery.tpl'}
						{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.images').slideUp();newImage();"> Add New Image</a>
					</div>
					<input type="hidden" value="{$imageno}" id="imageno">
				</div>
				<!--===+++===+++===+++===+++===+++ FILES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="files">
					<div class="row form" id="files-wrapper">{assign var='filesno' value=0} {assign var='gTableName' value='listing'} {foreach $fields.files as $files} {assign var='filesno' value=$filesno+1} {include file='files.tpl'} {/foreach}</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.files').slideUp();newFile();"> Add New File</a>
					</div>
					<input type="hidden" value="{$filesno}" id="filesno">
				</div>
				<!--===+++===+++===+++===+++===+++ LOG TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="log">
					<div class="row form" id="tags-wrapper">
						<div class="col-sm-12">
							{if $fields.logs}
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Date-Time</th>
											<th>Action</th>
											<th>User</th>
										</tr>
									</thead>
									<tbody>
									{foreach $fields.logs as $log}
										<tr {if $log.listing_id eq $fields.listing_id}class="info"{/if}>
											<td>{$log.log_created|date_format:"%d/%b/%Y %r"}</td>
											<td>{$log.log_action}{if $log.log_action eq 'Add' || $log.log_action eq 'Delete'} draft{/if}</td>
											<td>{$log.admin_name}</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							{else}
								Log empty.
							{/if}
						</div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ GUESTBOOK TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="guestbook">
					<div class="form" id="guestbooks-wrapper">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_obituary_guestbook_managed">Is it managed?</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.listing_id}{if $fields.obituary_guestbook_managed eq 1}1{else}0{/if}{else}1{/if}" name="field[1][tbl_obituary][{$cnt}][obituary_guestbook_managed]" class="value"> 
								<input class="chckbx" type="checkbox" {if $fields.obituary_guestbook_managed eq 1 || $fields.listing_id eq ""}checked="checked" {/if} 
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_obituary_guestbook_managed">
							</div>
						</div>
						{assign var='gbno' value=0}
						{foreach $fields.guestbook as $message}
							{assign var='gbno' value=$gbno+1}
							{include file='guestbook.tpl'}
						{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.guestbooks').slideUp();newGuestbook();"> Add New Message</a>
					</div>
					<input type="hidden" value="{$gbno}" id="gbno">
				</div>
			</div>
			<div class="row form-group form-bottom-btns">
				<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
				<div class="published" {if $fields.listing_published eq 0}style="display:none;"{/if}>
					<!-- PUBLISHED -->
					<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
					<a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
				</div>
				<div class="drafts" {if $fields.listing_published eq 1}style="display:none;"{/if}>
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

function setname(){
	var name = $('#id_obituary_name').val() + " " + $('#id_obituary_surname').val();
	var lifespan = "";
	if($("#id_obituary_born").val() != ""){
		var dateArr = $("#id_obituary_born").val().split("-");
		lifespan = ""+dateArr[0]; 
	}
	if($("#id_obituary_died").val() != ""){
		var dateArr = $("#id_obituary_died").val().split("-");
		if(lifespan!=""){ lifespan = lifespan + " to " + dateArr[0]; }else{ lifespan = ""+dateArr[0]; }
	}
	name = name + " " + lifespan;
	$('#id_listing_name').val(name);
	$('#id_listing_name_text').html(name);
	seturl(name);
}

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
	$('.images').hide();
	$('.files').hide();
	$('.guestbooks').hide();


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

	$("#born").datepicker({
		changeMonth : true,
		changeYear : true,
		dateFormat : "dd/mm/yy",
		yearRange: "-125:+0", 
		onSelect : function(selectedDate) {
			$("#id_obituary_born").val( convert_to_mysql_date_format(selectedDate) );
			setname();
		}
	});

	$("#died").datepicker({
		changeMonth : true,
		changeYear : true,
		dateFormat : "dd/mm/yy",
		yearRange: "-125:+0", 
		onSelect : function(selectedDate) {
			$("#id_obituary_died").val( convert_to_mysql_date_format(selectedDate) );
			setname();
		}
	});

	$("#service_date").datepicker({
		changeMonth : true,
		changeYear : true,
		dateFormat : "dd/mm/yy",
		yearRange: "-125:+0", 
		onSelect : function(selectedDate) {
			$("#id_obituary_service_date").val( convert_to_mysql_date_format(selectedDate) );
		}
	});

	$('.times').change(function(){
		var notation = 0;
		var hour = parseInt($('#hour').val());
		if (hour == 12) hour = 0;
		if($('#notation').val() == 'pm') notation = 12;
		$('#id_obituary_service_time').val( ( hour + notation) + ':' + $('#minute').val());
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

function newImage() {
	$('body').css('cursor', 'wait');
	var no = $('#imageno').val();
	no++;
	$('#imageno').val(no);
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/load-template.php",
		cache : false,
		data : "template=gallery.tpl&imageno=" + no + "&gTableName=listing",
		dataType : "html",
		success : function(data, textStatus) {
			try {
				$('#images-wrapper').append(data);
				$('body').css('cursor', 'default');
				scrolltodiv('#image_wrapper' + no);
				if (no == 1) {
					$('#gallery_ishero_1').val('1');
				}
			} catch (err) {
				$('body').css('cursor', 'default');
			}
		}
	});
}

function toggleImage(ID) {
	if ($('#image' + ID).is(':visible')) {
		$('.images').slideUp();
	} else {
		$('.images').slideUp();
		$('#image' + ID).slideDown();
	}
}

function deleteImage(ID) {
	if (ConfirmDelete()) {
		var count = $('#' + ID).attr('rel');
		var today = mysql_now();

		html = '<input type="hidden" value="'+today+'" name="field[10][tbl_gallery]['+count+'][gallery_deleted]" />';
		$('#' + ID).append(html);
		$('#' + ID).css('display', 'none');
		$('#' + ID).removeClass('images');
	} else {
		return false;
	}
}

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

function newGuestbook() {
	$('body').css('cursor', 'wait');
	var no = $('#gbno').val();
	no++;
	$('#gbno').val(no);
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/load-template.php",
		cache : false,
		data : "template=guestbook.tpl&gbno=" + no ,
		dataType : "html",
		success : function(data, textStatus) {
			try {
				$('#guestbooks-wrapper').append(data);
				$('body').css('cursor', 'default');
				scrolltodiv('#guestbook_wrapper' + no);
			} catch (err) {
				$('body').css('cursor', 'default');
			}
			eval(data);
		}
	});
}

function toggleGuestbook(ID) {
	if ($('#guestbook' + ID).is(':visible')) {
		$('.guestbooks').slideUp();
	} else {
		$('.guestbooks').slideUp();
		$('#guestbook' + ID).slideDown();
	}
}

function deleteGuestbook(ID) {
	if (ConfirmDelete()) {
		var count = $('#' + ID).attr('rel');
		var today = mysql_now();

		html = '<input type="hidden" value="'+today+'" name="field[10][tbl_guestbook]['+count+'][guestbook_deleted]" />';
		$('#' + ID).append(html);
		$('#' + ID).css('display', 'none');
		$('#' + ID).removeClass('guestbooks');
	} else {
		return false;
	}
}
</script>
{/block}
