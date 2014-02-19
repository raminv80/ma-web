{block name=body}
<form>
	<div class="row form-group">
		<div class="col-sm-12">
			<h3>Login Details</h3>
		</div>
	</div>
	<div class="row form-group">
		<label  class="col-sm-2 control-label" for="email">Email:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="username" id="email" class="text-box-login">
		</div>
	</div>
	<div class="row form-group">
		<label  class="col-sm-2 control-label" for="password" >Password:</label>
		<div class="col-sm-5">
			<input type="password" class="form-control" name="password" id="password" class="text-box-login-password">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2">
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</div>
		<div class="col-sm-5 form-actions">
			<img src='/admin/images/login_button.png' id="logme" onclick="Login();" alt="Login" title="login" class="login_button">
		</div>
	</div>
	<script type="text/javascript" src="/admin/includes/js/login.js"></script>
	<div id="log"></div>
</form>
	{/block}