{block name=body}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.pcare_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <input type="hidden" value="pcare_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="pcare_id" name="field[1][tbl_pcare][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.pcare_id}" name="field[1][tbl_pcare][{$cnt}][pcare_id]" id="pcare_id" class="key">
          <input type="hidden" value="{if $fields.pcare_created}{$fields.pcare_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_pcare][{$cnt}][pcare_created]" id="pcare_created">
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
              <label class="col-sm-3 control-label" for="pcare_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.pcare_name}" name="field[1][tbl_pcare][{$cnt}][pcare_name]" id="pcare_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="pcare_description">Description</label>
              <div class="col-sm-5">
                <textarea class="tinymce" name="field[1][tbl_pcare][{$cnt}][pcare_description]" id="pcare_description">{$fields.pcare_description}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="pcare_order">Order</label>
              <div class="col-sm-5">
                <input class="form-control number" type="text" value="{if $fields.pcare_order neq ''}{$fields.pcare_order}{else}999{/if}" name="field[1][tbl_pcare][{$cnt}][pcare_order]" id="pcare_order">
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
                  <tr {if $log.pcare_id eq $fields.pcare_id}class="info"{/if}>
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
