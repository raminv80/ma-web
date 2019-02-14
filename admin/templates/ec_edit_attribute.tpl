{block name=body}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.attribute_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
          <input type="hidden" value="attribute_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="attribute_id" name="field[1][tbl_attribute][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.attribute_id}" name="field[1][tbl_attribute][{$cnt}][attribute_id]" id="attribute_id" class="key">
          <input type="hidden" value="{if $fields.attribute_created}{$fields.attribute_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_attribute][{$cnt}][attribute_created]" id="attribute_created">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        </div>
      </div>
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
        <li><a href="#values" data-toggle="tab">Values</a></li>
        <li><a href="#log" data-toggle="tab">Log</a></li>
      </ul>

      <div class="tab-content">
        <!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane active" id="details">
          <div class="form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="attribute_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.attribute_name}" name="field[1][tbl_attribute][{$cnt}][attribute_name]" id="attribute_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="attribute_description">Description</label>
              <div class="col-sm-5">
                <textarea class="form-control" rows="3" name="field[1][tbl_attribute][{$cnt}][attribute_description]" id="attribute_description">{$fields.attribute_description}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="attribute_type">Type</label>
              <div class="col-sm-3">
                <select class="form-control" name="field[1][tbl_attribute][{$cnt}][attribute_type]" id="attribute_type">
                  <option value="0" {if !$fields.attribute_type}selected="selected"{/if}>Standard</option>
                  <option value="1" {if $fields.attribute_type eq 1}selected="selected"{/if}>Image selector</option>
                  <option value="2" {if $fields.attribute_type eq 2}selected="selected"{/if}>Extended fields</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="attribute_order">Order</label>
              <div class="col-sm-5">
                <input class="form-control number" type="text" value="{if $fields.attribute_order neq ''}{$fields.attribute_order}{else}999{/if}" name="field[1][tbl_attribute][{$cnt}][attribute_order]" id="attribute_order">
              </div>
            </div>
          </div>
        </div>
        <!--===+++===+++===+++===+++===+++ VALUES TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="values">
          <div class="form" id="attr_value-wrapper" data-error="Error found on <b>Values tab</b>. View <b>Values tab</b> to see specific error notices.">
            {assign var='attrvalueno' value=0}
            {foreach $fields.attr_values as $attr_value}
              {assign var='attrvalueno' value=$attrvalueno+1}
              {assign var='attrtype' value=$fields.attribute_type}
              {include file='ec_attr_value.tpl'}
            {/foreach}
          </div>
          <div class="row btn-inform">
            <a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.attr_values').slideUp();newAttr_value();"> Add new value</a>
          </div>
          <input type="hidden" value="{$attrvalueno}" id="attr_valueno">
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
                  <tr {if $log.attribute_id eq $fields.attribute_id}class="info"{/if}>
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
    $('.attr_values').hide();
  });

  function newAttr_value() {
    $('body').css('cursor', 'wait');
    var no = $('#attr_valueno').val();
    no++ ;
    $('#attr_valueno').val(no);
    $.ajax({
      type: "POST",
      url: "/admin/includes/processes/load-template.php",
      cache: false,
      data: "template=ec_attr_value.tpl&attrvalueno="+no+"&attrtype="+ $('#attribute_type').val(),
      dataType: "html",
      success: function(data, textStatus) {
        try{
          $('#attr_value-wrapper').append(data);
          $('body').css('cursor', 'default');
          tinyMCEinit('tinymce'+no);
          scrolltodiv('#attr_value-wrapper' + no);
        }catch(err){
          $('body').css('cursor', 'default');
        }
      }
    });
  }
  
  function deleteAttr_value(ID) {
    if(ConfirmDelete()){
      var count = $('#' + ID).attr('rel');
      var today = mysql_now();
      
      html = '<input type="hidden" value="'+today+'" name="field[10][tbl_attr_value]['+count+'][attr_value_deleted]"/>';
      $('#' + ID).append(html);
      $('#' + ID).css('display', 'none');
      $('#' + ID).removeClass('attr_values');
    }else{
      return false;
    }
  }
  
  

</script>
{/block}
