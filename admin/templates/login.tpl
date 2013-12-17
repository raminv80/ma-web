{block name=body}
<form>
	<div class="row-fluid">
		<div class="span12">
			<h3>Login Details</h3>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">Email:</div>
		<div class="span8">
			<input type="text" name="username" id="email" class="text-box-login">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">Password:</div>
		<div class="span8">
			<input type="password" name="password" id="password" class="text-box-login-password">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</div>
		<div class="span8">
			<img src='/admin/images/login_button.png' id="logme" onclick="Login();" alt="Login" title="login" class="login_button">
		</div>
	</div>
	<script type="text/javascript" src="/admin/includes/js/login.js"></script>
	<div id="log"></div>
	{/block}