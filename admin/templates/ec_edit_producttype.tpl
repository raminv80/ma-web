{block name=body}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.producttype_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <input type="hidden" value="producttype_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="producttype_id" name="field[1][tbl_producttype][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.producttype_id}" name="field[1][tbl_producttype][{$cnt}][producttype_id]" id="producttype_id" class="key">
          <input type="hidden" value="{if $fields.producttype_created}{$fields.producttype_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_producttype][{$cnt}][producttype_created]" id="producttype_created">
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
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="producttype_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.producttype_name}" name="field[1][tbl_producttype][{$cnt}][producttype_name]" id="producttype_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="producttype_description">Description</label>
              <div class="col-sm-5">
                <textarea class="form-control" rows="3" name="field[1][tbl_producttype][{$cnt}][producttype_description]" id="producttype_description">{$fields.producttype_description}</textarea>
              </div>
            </div>
            <hr>
            <div class="row form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <h3>Associated attributes</h3>
                <div class="form">
                  <input type="hidden" value="producttype_id" name="default[productschema_type_id]" />
                  {assign var='data' value=$fields.options.attributes}
                  {assign var='proccess_order' value='5'}
                  {assign var='table_primary_key_field' value='producttype_id'}
                  {assign var='table_primary_key_value' value=$fields.producttype_id}
                  {assign var='linking_table' value='tbl_productschema'}
                  {assign var='linking_table_primary_key_field' value='productschema_id'}
                  {assign var='linking_table_link_field' value='productschema_type_id'}
                  {assign var='linking_table_associated_field' value='productschema_attribute_id'}
                  {assign var='linking_table_deleted_field' value='productschema_deleted'}
                  {assign var='selected_array' value=$fields.productschemas}
                  {assign var='ignore_value' value='0'}
                  {include file='form_linking_input.tpl'}
                </div>
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
                  <tr {if $log.producttype_id eq $fields.producttype_id}class="info"{/if}>
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
