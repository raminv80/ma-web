{block name=body}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.banner_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <input type="hidden" value="banner_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="banner_id" name="field[1][tbl_banner][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.banner_id}" name="field[1][tbl_banner][{$cnt}][banner_id]" id="banner_id" class="key">
          <input type="hidden" value="{if $fields.banner_created}{$fields.banner_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_banner][{$cnt}][banner_created]" id="banner_created">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
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
            <input type="hidden" value="0" name="field[1][tbl_banner][{$cnt}][banner_type]" id="banner_type" >
            {* COMMENTED OUT UNTIL REQUIRED - DON'T FORGET TO REMOVE THE ABOVE HIDDEN FIELD
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_type">Type</label>
              <div class="col-sm-3">
                <select class="form-control" name="field[1][tbl_banner][{$cnt}][banner_type]" id="banner_type">
                  <option value="0" {if !$fields.banner_type}selected="selected"{/if}>Standard</option>
                  <option value="1" {if $fields.banner_type eq 1}selected="selected"{/if}>Special</option>
                </select>
              </div>
            </div>
            *}
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.banner_name}" name="field[1][tbl_banner][{$cnt}][banner_name]" id="banner_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_description">Description</label>
              <div class="col-sm-5">
                <textarea class="form-control" name="field[1][tbl_banner][{$cnt}][banner_description]" id="banner_description">{$fields.banner_description}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_link">Link*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.banner_link}" name="field[1][tbl_banner][{$cnt}][banner_link]" id="banner_link" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_image1">
                Image - Desktop<br> <small>Size: 1140px Wide x 147px Tall</small>
              </label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.banner_image1}" name="field[1][tbl_banner][{$cnt}][banner_image1]" id="banner_image1_link" class="fileinput">
                <span class="file-view" id="banner_image1_path"> {if $fields.banner_image1}<a href="{$fields.banner_image1}" target="_blank">View</a>{else}None{/if}
                </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('banner_image1','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="$('#banner_image1_link').val('');$('#banner_image1_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_image2">
                Image - Mobile<br> <small>Size: 690px Wide x 490px Tall</small>
              </label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.banner_image2}" name="field[1][tbl_banner][{$cnt}][banner_image2]" id="banner_image2_link" class="fileinput">
                <span class="file-view" id="banner_image2_path"> {if $fields.banner_image2}<a href="{$fields.banner_image2}" target="_blank">View</a>{else}None{/if}
                </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('banner_image2','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="$('#banner_image2_link').val('');$('#banner_image2_path').html('None');">Remove File</a>
              </div>
            </div>
            {* COMMENTED OUT UNTIL REQUIRED 
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_flag1">Featured</label>
              <div class="col-sm-5">
                <input type="hidden" value="{if $fields.banner_flag1 eq 1}1{else}0{/if}" name="field[1][tbl_banner][{$cnt}][banner_flag1]" class="value">
                <input class="chckbx" type="checkbox" {if $fields.banner_flag1 eq 1} checked="checked" {/if}
                   onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="banner_flag1">
              </div>
            </div>
            *}
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="banner_order">Order</label>
              <div class="col-sm-5">
                <input class="form-control number" type="text" value="{if $fields.banner_order neq ''}{$fields.banner_order}{else}999{/if}" name="field[1][tbl_banner][{$cnt}][banner_order]" id="banner_order">
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
                  <tr {if $log.banner_id eq $fields.banner_id}class="info"{/if}>
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
      </div>
    </form>
  </div>
</div>

{include file='jquery-validation.tpl'}
<script type="text/javascript">
  
  $(document).ready(function() {
    $('#Edit_Record').validate({
      onkeyup: false
    });
  });

</script>
{/block}
