{block name=body}
	<form>
	<table   class="login-table">
		<tr>
			<td colspan='2'>
			<h3>Login Details</h3>
			</td>
		</tr>
		<tr>
			<td class="td-user">Username:</td>
			<td><input type="text" name="username" id="email"   class="text-box-login"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" id="password" class="text-box-login-password"	 ></td>
		</tr>
		<tr>
			<td>&nbsp;
			<input type="hidden" name="formToken" id="formToken" value="{$token}" /></td>
			<td align="right">
			<img src='/new_admin/images/login_button.png' id="logme" onclick="Login();"
				alt="Login" title="login" class="login_button"></td>
		</tr>
	</table>
	</form>
	<script type="text/javascript" src="/new_admin/includes/js/login.js"></script>
	<div id="log"></div>
{/block}