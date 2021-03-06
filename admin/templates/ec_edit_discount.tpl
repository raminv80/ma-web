{block name=body} {* Define the function *} 
{function name=options_list level=0 } 
  {foreach $opts as $opt} 
      <option value="{$opt.id}" {if $selected eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
      {if count($opt.subs) > 0} 
        {call name=options_list opts=$opt.subs level=$level+1 selected=$selected} 
      {/if}
  {/foreach} 
{/function}

<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.discount_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>

          <input type="hidden" value="discount_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="discount_id" name="field[1][tbl_discount][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.discount_id}" name="field[1][tbl_discount][{$cnt}][discount_id]" id="discount_id">
          <input type="hidden" value="{if $fields.discount_created}{$fields.discount_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_discount][{$cnt}][discount_created]" id="discount_created">
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
          <div class="row form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">

            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.discount_name}" name="field[1][tbl_discount][{$cnt}][discount_name]" id="id_discount_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_code">Code*</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" onblur="this.value = this.value.toUpperCase();" value="{$fields.discount_code}" name="field[1][tbl_discount][{$cnt}][discount_code]" id="id_discount_code" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_description">Description</label>
              <div class="col-sm-5">
                <textarea name="field[1][tbl_discount][{$cnt}][discount_description]" id="id_discount_description" class="tinymce">{$fields.discount_description}</textarea>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_code">Membership System Equivalent Code</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" onblur="this.value = this.value.toUpperCase();" value="{$fields.discount_membership_system_mapped_code}" name="field[1][tbl_discount][{$cnt}][discount_membership_system_mapped_code]" id="discount_membership_system_mapped_code" >
                <span class="help-block"></span>
              </div>
            </div>
            
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_invoice_message">Invoice message</label>
              <div class="col-sm-5">
                <textarea name="field[1][tbl_discount][{$cnt}][discount_invoice_message]" id="id_discount_invoice_message" rows="5" class="form-control">{$fields.discount_invoice_message}</textarea>
              </div>
            </div>
            
            <!-- Special -->
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_special">Special (no discount)</label>
              <div class="col-sm-5">
                <input type="hidden" value="{if $fields.discount_special eq 1}1{else}0{/if}" name="field[1][tbl_discount][{$cnt}][discount_special]" class="value">
                <input class="chckbx" type="checkbox" {if $fields.discount_special eq 1}checked="checked" {/if} 
                onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1'); DisplaySpecial(1); }else{ $(this).parent().children('.value').val('0'); DisplaySpecial(0); }" id="id_discount_special">
              </div>
            </div>
            <div class="row form-group special-true">
              <label class="col-sm-3 control-label" for="id_discount_special_message">Special message<br><small>(on shopping cart page)</small></label>
              <div class="col-sm-5">
                <textarea name="field[1][tbl_discount][{$cnt}][discount_special_message]" id="id_discount_special_message" rows="4" class="form-control">{$fields.discount_special_message}</textarea>
              </div>
            </div>
            
            
            <div class="row form-group special-false">
              <label class="col-sm-3 control-label" for="id_discount_amount">Amount</label>
              <div class="col-sm-9">
                <input class="form-control double" type="text" value="{$fields.discount_amount}" name="field[1][tbl_discount][{$cnt}][discount_amount]" id="id_discount_amount" >
                <div class="col-sm-2">
                  <input type="radio" name="field[1][tbl_discount][{$cnt}][discount_amount_percentage]" id="id_discount_amount_percentage1" value="0" {if $fields.discount_amount_percentage eq 0}checked{/if}>
                  $ AUD
                </div>
                <div class="col-sm-3">
                  <input type="radio" name="field[1][tbl_discount][{$cnt}][discount_amount_percentage]" id="id_discount_amount_percentage2" value="1" {if $fields.discount_amount_percentage eq 1}checked{/if}>
                  % Percentage
                </div>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group special-false">
              <label class="col-sm-3 control-label" for="id_discount_shipping">Free shipping</label>
              <div class="col-sm-5">
                <input type="hidden" value="{$fields.discount_shipping}" name="field[1][tbl_discount][{$cnt}][discount_shipping]" class="value" id="free_shipping">
                <input class="chckbx" type="checkbox" {if $fields.discount_shipping}checked="checked" {/if} 
                onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('Postage & handling') }else{ $(this).parent().children('.value').val('') }" id="id_discount_shipping">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_listing_id">Collection</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_discount][{$cnt}][discount_listing_id]" id="id_discount_listing_id">
                  <option value="0">ALL</option>
                  {call name=options_list opts=$fields.options.categories selected=$fields.discount_listing_id}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_product_id">Product</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_discount][{$cnt}][discount_product_id]" id="id_discount_product_id">
                  <option value="0">ALL</option>
                  {call name=options_list opts=$fields.options.products selected=$fields.discount_product_id}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_usergroup_id">User group</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_discount][{$cnt}][discount_usergroup_id]" id="id_discount_usergroup_id">
                  <option value="0">ALL</option>
                  {call name=options_list opts=$fields.options.usergroups selected=$fields.discount_usergroup_id}
                </select>
              </div>
            </div>
            <!-- <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_user_id">User</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_discount][{$cnt}][discount_user_id]" id="id_discount_user_id">
                  <option value="0">ALL</option>
                  {call name=options_list opts=$fields.options.users selected=$fields.discount_user_id}
                </select>
              </div>
            </div> -->
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_start_date">Start date*</label>
              <div class="col-sm-5">
                <input class="form-control dates" type="text" value="{if $fields.discount_start_date}{$fields.discount_start_date|date_format:" %d/%m/%Y"}{else}{$smarty.now|date_format:"%d/%m/%Y"}{/if}"  name="from" id="from" onchange="setDateValue('id_discount_start_date',this.value);" required>
                <input type="hidden" value="{if $fields.discount_start_date}{$fields.discount_start_date}{else}{$smarty.now|date_format:" %Y-%m-%d"}{/if}" name="field[1][tbl_discount][{$cnt}][discount_start_date]" id="id_discount_start_date">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_end_date">End date</label>
              <div class="col-sm-5">
                <input class="form-control dates" type="text" value="{if $fields.discount_end_date neq '0000-00-00'}{$fields.discount_end_date|date_format:" %d/%m/%Y"}{/if}"  name="to" id="to" onchange="setDateValue('id_discount_end_date',this.value);">
                <input type="hidden" value="{if $fields.discount_end_date}{$fields.discount_end_date}{/if}" name="field[1][tbl_discount][{$cnt}][discount_end_date]" id="id_discount_end_date">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_unlimited_use">Lifespan</label>
              <div class="col-sm-9">
                <div class="radio">
                  <input type="radio" name="field[1][tbl_discount][{$cnt}][discount_unlimited_use]" onclick="$('#id_discount_fixed_time').hide();" id="id_discount_unlimited_use1" value="1" {if $fields.discount_unlimited_use eq 1}checked{/if}>
                  <label for="id_discount_unlimited_use1">Unlimited use</label>
                </div>
                <div class="radio">
                  <input type="radio" name="field[1][tbl_discount][{$cnt}][discount_unlimited_use]" onclick="$('#id_discount_fixed_time').show();" id="id_discount_unlimited_use2" value="0" {if $fields.discount_unlimited_use eq 0}checked{/if}>
                  <label for="id_discount_unlimited_use2">Fixed time use</label>
                  <input {if $fields.discount_unlimited_use eq 1}style="display: none;" {/if} class="form-control number" type="text" value="{if $fields.discount_fixed_time}{$fields.discount_fixed_time}{else}1{/if}" name="field[1][tbl_discount][{$cnt}][discount_fixed_time]" id="id_discount_fixed_time" >
                </div>
              </div>
            </div>
    	   {if $fields.discount_used}
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_discount_used">Used</label>
				<div class="col-sm-5">
					<span class="form-control number">{$fields.discount_used}</span>
				</div>
			</div>
		    {/if} 
            
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_discount_published">Active</label>
              <div class="col-sm-5">
                <input type="hidden" value="{if $fields.discount_published eq 1}1{else}0{/if}" name="field[1][tbl_discount][{$cnt}][discount_published]" class="value">
                <input class="chckbx" type="checkbox" {if $fields.discount_published eq 1}checked="checked" {/if} 
								onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_discount_published">
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
                  <tr>
                    <td>{$log.log_created|date_format:"%d/%b/%Y %r"}</td>
                    <td>{$log.log_action}</td>
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

      <div class="row form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-top: 50px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
        </div>
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
    
    DisplaySpecial({$fields.discount_special});
    
    $('#id_discount_code').rules("add", {
      uniqueURL: {
        id: $('#discount_id').val(),
        idfield: "discount_id",
        table: "tbl_discount",
        field: "discount_code",
        field2: "",
        value2: ""
      },
      messages: {
        equalTo: "Invalid CODE: It's currently being used or has non-alphanumeric characters."
      }
    });
    
    $("#from").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy",
      onSelect: function(selectedDate) {
        var mysqlDate = convert_to_mysql_date_format(selectedDate);
        $("#id_discount_start_date").val(mysqlDate);
        $("#to").datepicker("option", "minDate", selectedDate);
        if(mysqlDate > $("#id_discount_end_date").val()){
          $("#id_discount_end_date").val(mysqlDate);
        }
      }
    });
    
    $("#to").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy",
      onSelect: function(selectedDate) {
        $("#id_discount_end_date").val(convert_to_mysql_date_format(selectedDate));
        //$("#from").datepicker("option", "maxDate", selectedDate);
      }
    });
    
  });
  
  function DisplaySpecial(VAL){
    if(VAL == 1){
      $('.special-true').show('slow');
      $('.special-false').hide();
      $('#id_discount_amount').val('0');
      $('#free_shipping').val('');
      $('#id_discount_shipping').removeAttr('checked');
    }else{
      $('.special-false').show('slow');
      $('.special-true').hide();
    }
  }
</script>
{/block}
