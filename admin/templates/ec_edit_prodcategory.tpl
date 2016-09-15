{block name=body} {* Define the function *} {function name=options_list level=0} {foreach $opts as $opt} {if $fields.listing_object_id neq $opt.id}
<option value="{$opt.id}" {if  $selected eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1 selected=$selected} {/if} {/if} {/foreach} {/function}

<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <div class="published" {if $fields.listing_published eq 0}style="display: none;"{/if}>
            <!-- PUBLISHED -->
            <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a> <a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
          </div>
          <div class="drafts" {if $fields.listing_published eq 1}style="display: none;"{/if}>
            <!-- DRAFT -->
            <a href="javascript:void(0);" onClick="publish('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
          </div>
          <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
          <input type="hidden" value="listing_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_id" class="key">
          <input type="hidden" value="{$typeID}" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
          <input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" id="listing_parent_flag">
          <input type="hidden" value="0" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" id="listing_display_menu">
          <input type="hidden" value="{if $fields.listing_object_id}{$fields.listing_object_id}{else}{$objID}{/if}" name="field[1][tbl_listing][{$cnt}][listing_object_id]" id="listing_object_id">
          <input type="hidden" value="{if $fields.listing_published}{$fields.listing_published}{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_published]" id="listing_published">
          <input type="hidden" value="{if $fields.listing_created}{$fields.listing_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_listing][{$cnt}][listing_created]" id="listing_created">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
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
        <li><a href="#log" data-toggle="tab">Log</a></li>
      </ul>

      <div class="tab-content">
        <!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane active" id="details">
          <div class="form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_name">Name *</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" onchange="seturl(this.value);" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_url">URL *</label>
              <div class="col-sm-5">
                <input class="form-control" type="hidden" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" onchange="seturl(this.value, true);">
                <span id="id_listing_url_text" class="form-control url-text edit-url">{$fields.listing_url}&nbsp;</span> <a href="javascript:void(0);" class="btn btn-info btn-sm marg-5r edit-url" onclick="$('.edit-url').removeClass('url-text').hide();$('#id_listing_url').get(0).type='text';">Edit URL</a> <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_parent_id">Parent</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="id_listing_parent_id">
                  <option value="{$rootParentID}">None</option>
                  {call name=options_list opts=$fields.options.listing_parent_id selected=$fields.listing_parent_id}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_seo_title">SEO Title *</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" required>
                <span class="small pull-right charcount">{$fields.listing_seo_title|count_characters:true}</span><span class="small pull-right">characters: </span><span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_meta_description">Meta Description</label>
              <div class="col-sm-5">
                <textarea class="form-control" rows="3" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description">{$fields.listing_meta_description}</textarea>
                <span class="small pull-right charcount">{$fields.listing_meta_description|count_characters:true}</span><span class="small pull-right">characters: </span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_meta_words">Meta Words</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="listing_image">
                Image<br>
                <small>Size: 800px Wide x 440px Tall</small>
              </label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.listing_image}" name="field[1][tbl_listing][{$cnt}][listing_image]" id="listing_image_link" class="fileinput">
                <span class="file-view" id="listing_image_path"> {if $fields.listing_image}<a href="{$fields.listing_image}" target="_blank">View</a>{else}None{/if}
                </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('listing_image','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="$('#listing_image_link').val('');$('#listing_image_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_content1">Content</label>
              <div class="col-sm-5">
                <textarea name="field[1][tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_associate1">Banner ad</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_listing][{$cnt}][listing_associate1]" id="id_listing_associate1">
                  <option value="0">None</option>
                  {call name=options_list opts=$fields.options.banners selected=$fields.listing_associate1}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_listing_order">Order</label>
              <div class="col-sm-5">
                <input class="form-control number" type="text" value="{$fields.listing_order}" name="field[1][tbl_listing][{$cnt}][listing_order]" id="id_listing_order">
              </div>
            </div>
          </div>
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
              {else} Log empty. {/if}
            </div>
          </div>
        </div>
      </div>
      <div class="row form-group form-bottom-btns">
        <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
        <div class="published" {if $fields.listing_published eq 0}style="display: none;"{/if}>
          <!-- PUBLISHED -->
          <a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_listing][{$cnt}][id]','listing_object_id','listing_published','field[1][tbl_listing][{$cnt}][listing_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a> <a href="javascript:void(0);" onClick="unpublish('listing_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
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
          if($('#listing_id').val() == "" || editexisting == true){
            $('#id_listing_url').val(res.url);
            $('#id_listing_url_text').html(res.url);
          }
        }catch(err){}
      }
    });
  }

  $(document).ready(function() {
    
    $('#Edit_Record').validate({
      onkeyup: false
    });
    
    $('#id_listing_url').rules("add", {
      uniqueURL: {
        id: $('#listing_object_id').val(),
        idfield: "listing_object_id",
        table: "tbl_listing",
        field: "listing_url",
        field2: "listing_parent_id",
        value2: "id_listing_parent"
      }
    });
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
              buildUrl('tbl_listing', 'listing_parent_id', objId_name, preview);
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
</script>
{/block}
