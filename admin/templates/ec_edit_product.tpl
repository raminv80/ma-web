{block name=body}
{* Define the function *} {function name=options_list level=0}
	{foreach $opts as $opt}
		{if $ignore neq $opt.id}
			<option value="{$opt.id}" {if  $selected eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1 selected=$selected} {/if}
		{/if}
	{/foreach}
{/function}

<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.product_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <div class="published" {if $fields.product_published eq 0}style="display: none;"{/if}>
            <!-- PUBLISHED -->
            <a href="javascript:void(0);" onClick="unpublish('product_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a> <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
          </div>
          <div class="drafts" {if $fields.product_published eq 1}style="display: none;"{/if}>
            <!-- DRAFT -->
            <a href="javascript:void(0);" onClick="publish('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
          </div>
          <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
          <input type="hidden" value="product_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="product_id" name="field[1][tbl_product][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.product_id}" name="field[1][tbl_product][{$cnt}][product_id]" id="product_id" class="key">
          <input type="hidden" value="{if $fields.product_object_id}{$fields.product_object_id}{else}{$objID}{/if}" name="field[1][tbl_product][{$cnt}][product_object_id]" id="product_object_id">
          <input type="hidden" value="{if $fields.product_created}{$fields.product_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_product][{$cnt}][product_created]" id="product_created">
          <input type="hidden" value="{if $fields.product_published}{$fields.product_published}{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_published]" id="product_published">

          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        </div>
      </div>
      <div class="row published" {if $fields.product_published eq 0}style="display: none;"{/if}>
        <div class="alert alert-success text-center">
          <strong>PUBLISHED</strong>
        </div>
      </div>
      <div class="row drafts" {if $fields.product_published eq 1}style="display: none;"{/if}>
        <div class="alert alert-info text-center">
          <strong>DRAFT</strong>
        </div>
      </div>
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
        <li><a href="#variants" data-toggle="tab">Variants</a></li>
        <li><a href="#images" data-toggle="tab">Images</a></li>
        <li><a href="#categories" data-toggle="tab">Collections</a></li>
        <li><a href="#associated" data-toggle="tab">Associated Products</a></li>
        <li><a href="#share" data-toggle="tab">Social Sharing</a></li>
        <li><a href="#log" data-toggle="tab">Log</a></li>
      </ul>

      <div class="tab-content">
        <!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane active" id="details">
          <div class="form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_name">Name *</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.product_name}" name="field[1][tbl_product][{$cnt}][product_name]" id="id_product_name" onchange="seturl(this.value);" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="product_type_id">Type</label>
              <div class="col-sm-5 ">
                <select class="form-control" name="field[1][tbl_product][{$cnt}][product_type_id]" id="product_type_id" data-value="{$fields.product_type_id}">
                  {call name=options_list opts=$fields.options.product_types selected=$fields.product_type_id}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_uid">Code (ISDN,Product code,etc)</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.product_uid}" name="field[1][tbl_product][{$cnt}][product_uid]" id="id_product_uid">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_url">URL *</label>
              <div class="col-sm-5 ">
                <input class="form-control" type="hidden" value="{$fields.product_url}" name="field[1][tbl_product][{$cnt}][product_url]" id="id_product_url" onchange="seturl(this.value, true);" required>
                <span style="display: inline-block; width: 74%;" id="id_product_url_text" class="form-control url-text edit-url">{$fields.product_url}&nbsp;</span> <a href="javascript:void(0);" class="btn btn-info btn-sm marg-5r edit-url" onclick="$('.edit-url').removeClass('url-text').hide();$('#id_product_url').get(0).type='text';">Edit URL</a> <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_brand">Brand</label>
              <div class="col-sm-5 ">
                <input class="form-control" type="text" value="{$fields.product_brand}" name="field[1][tbl_product][{$cnt}][product_brand]" id="id_product_brand">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_seo_title">SEO Title *</label>
              <div class="col-sm-5 ">
                <input class="form-control" type="text" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" value="{$fields.product_seo_title}" name="field[1][tbl_product][{$cnt}][product_seo_title]" id="id_product_seo_title" required>
                <span class="small pull-right charcount">{$fields.product_seo_title|count_characters:true}</span><span class="small pull-right">characters: </span><span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_meta_description">Meta Description</label>
              <div class="col-sm-5 ">
                <textarea class="form-control" rows="3" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" name="field[1][tbl_product][{$cnt}][product_meta_description]" id="id_product_meta_description">{$fields.product_meta_description}</textarea>
                <span class="small pull-right charcount">{$fields.listing_meta_description|count_characters:true}</span><span class="small pull-right">characters: </span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_meta_words">Meta Words</label>
              <div class="col-sm-5 ">
                <input class="form-control" type="text" value="{$fields.product_meta_words}" name="field[1][tbl_product][{$cnt}][product_meta_words]" id="id_product_meta_words">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_order">Order(#position)</label>
              <div class="col-sm-5 ">
                <input class="form-control number" type="text" value="{if $fields.product_order neq ''}{$fields.product_order}{else}999{/if}" name="field[1][tbl_product][{$cnt}][product_order]" id="id_product_order">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_flag1">Featured Product</label>
              <div class="col-sm-5 ">
                <input type="hidden" value="{if $fields.product_flag1 eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_flag1]" class="value">
                <input class="chckbx" type="checkbox" {if $fields.product_flag1 eq 1}checked="checked" {/if}
                  onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_flag1">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content1">Description</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content1]" id="id_product_content1" class="tinymce">{$fields.product_content1}</textarea>
              </div>
            </div>
            <!-- <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content2">Warranty</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content2]" id="id_product_content2" class="tinymce">{$fields.product_content2}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content3">Care &amp; cleaning</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content3]" id="id_product_content3" class="tinymce">{$fields.product_content3}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content4">Delivery &amp; returns</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content4]" id="id_product_content4" class="tinymce">{$fields.product_content4}</textarea>
              </div>
            </div> -->
          </div>
        </div>
         <!--===+++===+++===+++===+++===+++ VARIANTS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="variants">
          <div class="form" id="variants-wrapper" data-error="Error found on <b>Variants tab</b>. View <b>Variants tab</b> to see specific error notices.">
            {assign var='variantno' value=0} 
            {assign var='typeID' value=$fields.product_type_id} 
            {foreach $fields.variants as $variant} 
              {assign var='variantno' value=$variantno+1} 
              {include file='ec_variant.tpl'} 
            {/foreach}
          </div>
          <div class="row btn-inform">
            <a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.variants').slideUp();newVariant();"> Add new variant</a>
          </div>
          <input type="hidden" value="{$variantno}" id="variantno">
        </div>
        <!--===+++===+++===+++===+++===+++ IMAGES TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="images">
          <div class="form" id="images-wrapper">
            {assign var='imageno' value=0} 
            {assign var='gTableName' value='product'} 
            {assign var='image_size' value='Size: 1170px Wide x 560px Tall'} 
            {foreach $fields.gallery as $images} 
              {assign var='imageno' value=$imageno+1} 
              {include file='gallery.tpl'} 
            {/foreach}
          </div>
          <div class="row btn-inform">
            <a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.images').slideUp();newImage();"> Add New Image</a>
          </div>
          <input type="hidden" value="{$imageno}" id="imageno">
          <!--  gallery -->
        </div>
        <!--===+++===+++===+++===+++===+++ CATEGORIES PRODUCTS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="categories">
          <div class="form">
            <input type="hidden" value="product_id" name="default[productcat_product_id]" />
            {assign var='data' value=$fields.options.categories} 
            {assign var='proccess_order' value='4'} 
            {assign var='table_primary_key_field' value='product_id'} 
            {assign var='table_primary_key_value' value=$fields.product_id} 
            {assign var='linking_table' value='tbl_productcat'} 
            {assign var='linking_table_primary_key_field' value='productcat_id'} 
            {assign var='linking_table_link_field' value='productcat_product_id'} 
            {assign var='linking_table_associated_field' value='productcat_listing_id'} 
            {assign var='linking_table_deleted_field' value='productcat_deleted'} 
            {assign var='selected_array' value=$fields.productcats} 
            {assign var='ignore_value' value='0'} 
            {include file='form_linking_input.tpl'}
          </div>
        </div>
        <!--===+++===+++===+++===+++===+++ ASSOCIATED PRODUCTS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="associated">
          <div class="form">
            <input type="hidden" value="product_id" name="default[productassoc_product_id]" />
            {assign var='data' value=$fields.options.products} 
            {assign var='proccess_order' value='5'} 
            {assign var='table_primary_key_field' value='product_id'} 
            {assign var='table_primary_key_value' value=$fields.product_id} 
            {assign var='linking_table' value='tbl_productassoc'} 
            {assign var='linking_table_primary_key_field' value='productassoc_id'} 
            {assign var='linking_table_link_field' value='productassoc_product_id'} 
            {assign var='linking_table_associated_field' value='productassoc_product_object_id'} 
            {assign var='linking_table_deleted_field' value='productassoc_deleted'} 
            {assign var='selected_array' value=$fields.productassoc} 
            {assign var='ignore_value' value=$fields.product_object_id} 
            {include file='form_linking_input.tpl'}
          </div>
        </div>
        <!--===+++===+++===+++===+++===+++ SHARE TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="share">
          <div class="form" data-error="Error found on <b>Social Sharing tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_share_title">Share Title</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.product_share_title}" name="field[1][tbl_product][{$cnt}][product_share_title]" id="id_product_share_title">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="product_share_image">
                Share Image<br> <small>Size: 1200px Wide x 630px Tall (less than 1Mb) <br>("None" for default image)
                </small>
              </label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.product_share_image}" name="field[1][tbl_product][{$cnt}][product_share_image]" id="product_share_image_link" class="fileinput">
                <span class="file-view" id="product_share_image_path"> {if $fields.product_share_image}<a href="{$fields.product_share_image}" target="_blank">View</a>{else}None{/if}
                </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('product_share_image','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="$('#product_share_image_link').val('');$('#product_share_image_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_share_text">
                Share Text <br> <span class="small">(120 Characters)</span>
              </label>
              <div class="col-sm-5">
                <textarea class="form-control" name="field[1][tbl_product][{$cnt}][product_share_text]" id="id_product_share_text" maxlength="120">{$fields.product_share_text}</textarea>
              </div>
            </div>
          </div>
        </div>
        <!--===+++===+++===+++===+++===+++ LOG TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="log">
          <div class="form">
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
                  <tr {if $log.product_id eq $fields.product_id}class="info"{/if}>
                    <td>{$log.log_created|date_format:"%d/%b/%Y %r"}</td>
                    <td>{$log.log_action}{if $log.log_action eq 'Add' || $log.log_action eq 'Delete'} draft{/if}</td>
                    <td>{$log.admin_name}</td>
                  </tr>
                  {/foreach}
                </tbody>
              </table>
              {else} Log empty. {/if}
            </div>
          </div>
        </div>
      </div>

      <div class="row form-group form-bottom-btns">
        <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
        <div class="published" {if $fields.product_published eq 0}style="display: none;"{/if}>
          <!-- PUBLISHED -->
          <a href="javascript:void(0);" onClick="unpublish('product_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a> <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
        </div>
        <div class="drafts" {if $fields.product_published eq 1}style="display: none;"{/if}>
          <!-- DRAFT -->
          <a href="javascript:void(0);" onClick="publish('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
        </div>
        <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
      </div>
    </form>
  </div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">

  var lastSel = $("#product_type_id option:selected");
  $(document).ready(function() {
    
    $('#Edit_Record').validate({
      onkeyup: false
    });
    
    $('#id_product_url').rules("add", {
      uniqueURL: {
        id: $('#product_object_id').val(),
        idfield: "product_object_id",
        table: "tbl_product",
        field: "product_url",
        field2: "",
        value2: ""
      },
      uniqueURL2: {
        id: $('#product_object_id').val(),
        idfield: "listing_object_id",
        table: "tbl_listing",
        field: "listing_url",
        field2: "",
        value2: ""
      }
    });
    
    $('.images').hide();
    $('.variants').hide();
    SetImgVariantOpts();
   
    $('#product_type_id').change(function(){
      if($('.variant_wrappers').length){
        if(!confirm("The product variants may be affected by changing the product type. Do you still want to continue?")){
          lastSel.attr("selected", true);       
        }
      }
    });
    
    //$('.product-imgs').click(function(){
    //  SetImgVariantOpts('#'+ $(this).closest('.gallery-image').attr('id'));
    //});
    
  });
  
  function saveDraft(id_name, objId_name, publish_name, field_name, preview) {
    if($('#Edit_Record').valid()){
      $('body').css('cursor', 'wait');
      $('#' + publish_name).val('0');
      var id_key0 = encodeURIComponent(id_name + '[0]');
      var id_key1 = encodeURIComponent(id_name + '[1]');
      var objId_key = encodeURIComponent($('#' + objId_name).attr('name'));
      var publish_key = encodeURIComponent($('#' + publish_name).attr('name'));
      var field_key = encodeURIComponent(field_name);
      var field_value = encodeURIComponent(mysql_now());
      $.ajax({
        type: "POST",
        url: "/admin/includes/processes/processes-record.php",
        cache: false,
        async: false,
        data: id_key0 + '=' + objId_name + '&' + id_key1 + '=' + publish_name + '&' + objId_key + "=" + $('#' + objId_name).val() + "&" + publish_key + "=0&" + field_key + "=" + field_value + '&formToken=' + $('#formToken').val(),
        dataType: "html",
        success: function(data, textStatus) {
          try{
            var obj = $.parseJSON(data);
            if(obj.notice){
              $('.key').val('');
              $('#Edit_Record').submit();
              $('.published').hide();
              $('.drafts').show();
              buildUrl('tbl_product', 'product_listing_id', objId_name, preview);
            }
          }catch(err){}
          $('body').css('cursor', 'default');
        }
      });
      $('body').css('cursor', 'default');
    }
  }

  function publish(id_name, objId_name, publish_name, field_name) {
    if($('#Edit_Record').valid()){
      $('body').css('cursor', 'wait');
      $('#' + publish_name).val('1');
      var id_key0 = encodeURIComponent(id_name + '[0]');
      var id_key1 = encodeURIComponent(id_name + '[1]');
      var objId_key = encodeURIComponent($('#' + objId_name).attr('name'));
      var publish_key = encodeURIComponent($('#' + publish_name).attr('name'));
      var field_key = encodeURIComponent(field_name);
      var field_value = encodeURIComponent(mysql_now());
      $.ajax({
        type: "POST",
        url: "/admin/includes/processes/processes-record.php",
        cache: false,
        data: id_key0 + '=' + objId_name + '&' + id_key1 + '=' + publish_name + '&' + objId_key + "=" + $('#' + objId_name).val() + "&" + publish_key + "=1&" + field_key + "=" + field_value + '&formToken=' + $('#formToken').val(),
        dataType: "html",
        success: function(data, textStatus) {
          try{
            var obj = $.parseJSON(data);
            if(obj.notice){
              $('#Edit_Record').submit();
              $('.drafts').hide();
              $('.published').show();
            }
          }catch(err){}
          $('body').css('cursor', 'default');
        }
      });
      $('body').css('cursor', 'default');
    }
  }

  function unpublish(publish_name) {
    $('#' + publish_name).val('0');
    $('#Edit_Record').submit();
    $('.published').hide();
    $('.drafts').show();
  }

  function seturl(str) {
    seturl(str, false);
  }

  function seturl(str, editexisting) {
    $.ajax({
      type: "POST",
      url: "/admin/includes/processes/urlencode.php",
      cache: false,
      data: "value=" + encodeURIComponent(str),
      dataType: "json",
      success: function(res, textStatus) {
        try{
          if($('#product_id').val() == "" || editexisting == true){
            $('#id_product_url').val(res.url);
            $('#id_product_url_text').html(res.url);
          }
        }catch(err){}
      }
    });
  }

  function newImage() {
    $('body').css('cursor', 'wait');
    var no = $('#imageno').val();
    no++ ;
    $('#imageno').val(no);
    $.ajax({
      type: "POST",
      url: "/admin/includes/processes/load-template.php",
      cache: false,
      data: "template=gallery.tpl&imageno=" + no + "&gTableName=product&image_size=" + encodeURIComponent('Size: 1170px Wide x 560px Tall'),
      dataType: "html",
      success: function(data, textStatus) {
        try{
          $('#images-wrapper').append(data);
          $('body').css('cursor', 'default');
          scrolltodiv('#image_wrapper' + no);
          SetImgVariantOpts('#image_wrapper' + no);
        }catch(err){
          $('body').css('cursor', 'default');
        }
      }
    });
  }

  function toggleImage(ID) {
    if($('#image' + ID).is(':visible')){
      $('.images').slideUp();
    }else{
      $('.images').slideUp();
      $('#image' + ID).slideDown();
    }
  }

  function deleteImage(ID) {
    if(ConfirmDelete()){
      var count = $('#' + ID).attr('rel');
      var today = mysql_now();
      
      html = '<input type="hidden" value="'+today+'" name="field[10][tbl_gallery]['+count+'][attr_value_deleted]"/>';
      $('#' + ID).append(html);
      $('#' + ID).css('display', 'none');
      $('#' + ID).removeClass('attr_values');
    }else{
      return false;
    }
  }

  function newVariant() {
    $('body').css('cursor', 'wait');
    var no = $('#variantno').val();
    no++ ;
    $('#variantno').val(no);
    $.ajax({
      type: "POST",
      url: "/admin/includes/processes/load-template.php",
      cache: false,
      data: "template=ec_variant.tpl&process_file=load-product-attributes.php&variantno=" + no + "&typeID=" + $('#product_type_id').val(),
      dataType: "html",
      success: function(data, textStatus) {
        try{
          $('#variants-wrapper').append(data);
          $('body').css('cursor', 'default');
          scrolltodiv('#variant-wrapper' + no);
        }catch(err){
          $('body').css('cursor', 'default');
        }
      }
    });
  }

  function deleteVariant(ID) {
    if(ConfirmDelete()){
      var count = $('#' + ID).attr('rel');
      var today = mysql_now();
      
      html = '<input type="hidden" value="'+today+'" name="field[2][tbl_variant]['+count+'][variant_deleted]"/>';
      $('#' + ID).append(html);
      $('#' + ID).css('display', 'none');
      $('#' + ID).removeClass('variants');
    }else{
      return false;
    }
  }
  
  function SetImgVariantOpts(ID){
    //Get all variants
    console.log('+');
    console.log(ID);
    var variants = {};
    variants[0] = 'Select one';
    $('.variant_wrappers').each(function(){
      var value = $(this).find('.variant-ids').val()
      if(value){
        variants[value] = $(this).find('.variant-titles').html();
      }
    });
    
    if(ID){
      $(ID).find('.product-imgs').empty();
      $.each(variants, function(i, item){
        $(ID).find('.product-imgs').append($('<option>', { 
            value: i,
            text : item 
        }));
      });
      $(ID).find('.product-imgs').val($(ID).find('.product-imgs').attr('data-value'));
    }else{
      $('.product-imgs').empty();
      $.each(variants, function (i, item) {
        $('.product-imgs').each(function(){
          $(this).append($('<option>', { 
            value: i,
            text : item 
        	}));
          $(this).val($(this).attr('data-value'));
        });
      });
    }
  }
  
</script>
{/block}
