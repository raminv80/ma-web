{block name=body} {* Define the function *} {function name=options_list level=0}
	{foreach $opts as $opt}
		{if $ignore neq $opt.id}
			<option value="{$opt.id}" class="{$opt.extra.menu_location}" {if $selected eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1 selected=$selected} {/if}
		{/if}
	{/foreach}
{/function}
<div class="row">
  <div class="col-sm-12">
    <form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
      <div class="row">
        <div class="col-sm-12 edit-page-header">
          <span class="edit-page-title">{if $fields.menu_id neq ""}Edit{else}New{/if} {$zone}</span> {if $cnt eq ""}{assign var=cnt value=0}{/if} <a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>

          <input type="hidden" value="menu_id" name="primary_id" id="primary_id" />
          <input type="hidden" value="menu_id" name="field[1][tbl_menu][{$cnt}][id]" id="id" />
          <input type="hidden" value="{$fields.menu_id}" name="field[1][tbl_menu][{$cnt}][menu_id]" id="menu_id">
          <input type="hidden" value="{if $fields.menu_created}{$fields.menu_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_menu][{$cnt}][menu_created]" id="menu_created">
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
              <label class="col-sm-3 control-label" for="menu_type">Type</label>
              <div class="col-sm-3">
                <select class="form-control" name="field[1][tbl_menu][{$cnt}][menu_type]" id="menu_type">
                  <option value="0" {if !$fields.menu_type}selected="selected"{/if}>Page</option>
                  <option value="1" {if $fields.menu_type eq 1}selected="selected"{/if}>Page - Member's only</option>
                  <option value="2" {if $fields.menu_type eq 2}selected="selected"{/if}>Category/Group</option>
                  <option value="3" {if $fields.menu_type eq 3}selected="selected"{/if}>External page</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="menu_location">Location</label>
              <div class="col-sm-3">
                <select class="form-control" name="field[1][tbl_menu][{$cnt}][menu_location]" id="menu_location">
                  <option value="top-header" {if $fields.menu_location eq 'top-header'}selected="selected"{/if}>Top header</option>
                  <option value="main-header" {if !$fields.menu_location || $fields.menu_location eq 'main-header'}selected="selected"{/if}>Main header</option>
                  <option value="footer" {if $fields.menu_location eq 'footer'}selected="selected"{/if}>Footer</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="menu_parent_id">Parent</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_menu][{$cnt}][menu_parent_id]" id="menu_parent_id">
                  <option value="0">None</option>
                  {call name=options_list opts=$fields.options.menus selected=$fields.menu_parent_id ignore=$fields.menu_id}
                </select>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="menu_name">Name*</label>
              <div class="col-sm-5">
                <input class="form-control" maxlength="100" type="text" value="{$fields.menu_name}" name="field[1][tbl_menu][{$cnt}][menu_name]" id="menu_name" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group types type0 type1" style="display:none">
              <label class="col-sm-3 control-label" for="menu_listing_id">Page</label>
              <div class="col-sm-5">
                <select class="form-control" name="field[1][tbl_menu][{$cnt}][menu_listing_id]" id="menu_listing_id">
                  {call name=options_list opts=$fields.options.pages selected=$fields.menu_listing_id}
                </select>
              </div>
            </div>
            <div class="row form-group types type3" style="display:none">
              <label class="col-sm-3 control-label" for="menu_external">External <small>(Include http or https)</small></label>
              <div class="col-sm-5">
                <input class="form-control" maxlength="1000" type="text" value="{$fields.menu_external}" name="field[1][tbl_menu][{$cnt}][menu_external]" id="menu_external">
              </div>
            </div>
            <div class="row form-group types type0 type1 type2" style="display:none">
                <label class="col-sm-3 control-label" for="menu_icon">Icon<br>
                <small>Size: 20px Wide x 20px Tall</small></label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.menu_icon}" name="field[1][tbl_menu][{$cnt}][menu_icon]" id="menu_icon_link" class="fileinput"> 
                <span class="file-view" id="menu_icon_path"> {if $fields.menu_icon}<a href="{$fields.menu_icon}" target="_blank" >View</a>{else}None{/if} </span> 
                <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('menu_icon','','');">Select File</a> 
                <a href="javascript:void(0);" class="btn btn-info" onclick="$('#menu_icon_link').val('');$('#menu_icon_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="menu_order">Order(#position)</label>
              <div class="col-sm-5 ">
                <input class="form-control number" type="text" value="{if $fields.menu_order neq ''}{$fields.menu_order}{else}999{/if}" name="field[1][tbl_menu][{$cnt}][menu_order]" id="menu_order">
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
    
    RefreshParents();
    RenderFieldsByType();
    
    $('#menu_location').change(function(){
      RefreshParents();
    });
    
    $('#menu_type').change(function(){
      RenderFieldsByType(true);
    });
  });
  
  function RefreshParents(){
    $('#menu_parent_id option').hide();
    $('#menu_parent_id').find('.'+ $('#menu_location').val()).show();
  }
  
  function RenderFieldsByType(SLOWMODE){
    SLOWMODE = (SLOWMODE)?'slow':'';
    $('.types').hide();
    $('.type'+ $('#menu_type').val()).show(SLOWMODE);
  }
  
</script>
{/block}
