{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12 edit-page-header">
					<span class="edit-page-title">{if $fields.user_id neq ""}Edit{else}New{/if} {$zone}</span>
					{if $cnt eq ""}{assign var=cnt value=0}{/if} 
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
						
					<input type="hidden" value="user_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="{$fields.user_id}" name="field[1][tbl_user][{$cnt}][user_id]" id="user_id" /> 
					<input type="hidden" value="user_id" name="field[1][tbl_user][{$cnt}][id]" id="id" /> 
					<input type="hidden" value="{$fields.user_username}" name="field[1][tbl_user][{$cnt}][user_username]" id="user_username"> 
					<input type="hidden" value="{$fields.user_password}" name="field[1][tbl_user][{$cnt}][user_password]" id="user_password">
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					
					<input type="hidden" id="error" name="error" value="0" />
					
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
							<label class="col-sm-3 control-label" for="user_gname">Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.user_gname}" name="field[1][tbl_user][{$cnt}][user_gname]" id="user_gname" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="user_surname">Surname</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.user_surname}" name="field[1][tbl_user][{$cnt}][user_surname]" id="user_surname">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="user_email">Email *</label>
							<div class="col-sm-5">
								<input class="form-control" type="email" value="{$fields.user_email}" name="field[1][tbl_user][{$cnt}][user_email]" id="user_email" onchange="$('#user_username').val(this.value);createPassword();" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="user_reemail">Retype Email *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.user_email}" id="user_reemail" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="password">Password *</label>
							<div class="col-sm-5">
								<input class="form-control" type="password" value="" name="field1" id="password" onchange="createPassword();">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="re_password">Retype Password *</label>
							<div class="col-sm-5">
								<input class="form-control" type="password" value="" name="field2" id="re_password" >
								<span class="help-block"></span>
							</div>
						</div>
			<!-- 			<div class="row form-group" style="display: none">
							<label class="col-sm-3 control-label" for="user_level">Admin Level</label>
							<div class="col-sm-5">
								<select class="form-control" name="field[1][tbl_user][{$cnt}][user_level]" id="user_level">
									<option value="1" {if $fields.user_level eq 1}selected="selected"{/if}>Admin</option>
									<option value="2" {if $fields.user_level eq 2}selected="selected"{/if}>Sudo-user</option>
									<option value="3" {if $fields.user_level eq 3}selected="selected"{/if}>Member</option>
								</select>
							</div>
						</div> -->
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
							{else}
								Log empty.
							{/if}
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
var init_pass = "{if $fields.user_password}{$fields.user_password}{/if}";

$(document).ready(function(){

	$('#Edit_Record').validate({
		onkeyup: false
	});
	
	$('#re_password').rules("add", {
	      equalTo: '#password',
	      messages: {
	        equalTo: "The passwords you have entered do not match. Please check them."
	      }
	 });
	 
	$('#user_email').rules("add", {
		uniqueEmail: { id: "{if $fields.user_id}{$fields.user_id}{else}0{/if}", table:"user" }
	 });

	$('#user_reemail').rules("add", {
	      equalTo: '#user_email',
	      messages: {
	        equalTo: "The emails you have entered do not match. Please check them."
	      }
	 });
});

function createPassword() {

 	if ($('#password').val() != '' && $('#user_email').val() != '') {
		$.ajax({
			type: "POST",
		    url: "/admin/includes/processes/createPass.php",
			cache: false,
			data: "username="+$('#user_email').val()+"&password="+$('#password').val(),
			dataType: "json",
		    success: function(res, textStatus) {
		    	try{
		    		$('#user_password').val(res.password);
				}catch(err){ }
		    }
		});
	} else {
		$('#user_password').val(init_pass);
	} 
}

</script>

{/block}
