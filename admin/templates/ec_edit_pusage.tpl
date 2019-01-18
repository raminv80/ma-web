{block name=body}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.ptype_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <input type="hidden" value="pusage_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="pusage_id" name="field[1][tbl_pusage][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.pusage_id}" name="field[1][tbl_pusage][{$cnt}][pusage_id]" id="pusage_id" class="key">
          <input type="hidden" value="{if $fields.pusage_created}{$fields.pusage_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_pusage][{$cnt}][pusage_created]" id="pusage_created">
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
              <label class="col-sm-3 control-label" for="pusage_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.pusage_name}" name="field[1][tbl_pusage][{$cnt}][pusage_name]" id="pusage_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="pusage_description">Description</label>
              <div class="col-sm-5">
                <textarea class="form-control" rows="3" name="field[1][tbl_pusage][{$cnt}][pusage_description]" id="pusage_description">{$fields.pusage_description}</textarea>
              </div>
            </div>
            <!-- <div class="row form-group">
              <label class="col-sm-3 control-label" for="pusage_order">Order</label>
              <div class="col-sm-5">
                <input class="form-control number" type="text" value="{if $fields.pusage_order neq ''}{$fields.pusage_order}{else}999{/if}" name="field[1][tbl_usage][{$cnt}][pusage_order]" id="pusage_order">
              </div>
            </div>-->
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
                  <tr {if $log.ptype_id eq $fields.ptype_id}class="info"{/if}>
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
