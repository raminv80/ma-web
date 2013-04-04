{block name=body}
<style>
.error {
    border: 1px solid #FF0000!important;
}
.notice {
    border: 1px solid #119831 !important;
}
</style>
<script type="text/javascript" src="/includes/js/validation.js"></script>
<div class="container_16">
    <div class="grid_16">
		<h1 align="center">PASSWORD RECOVERY</h1> 
	</div><!-- end of grid_16 -->
    
    <div class="grid_6 prefix_5 suffix_5">
   
        <div class="grid_6 alpha omega login">
        <div class="error-div">
        {foreach key=key item=item from=$error}
        <br/><div class="error">{$item}</div><br/>
        {/foreach}
        {foreach key=key item=item from=$notice}
        <br/><div class="notice">{$item}</div><br/>
        {/foreach}
        </div>
        <form id="UserLogin" name="UserLogin" action="/includes/processes/processes-general.php" method="post">
            <table cellspacing="0" cellpadding="0" border="0" class="login-table">
                <tbody>
	            <tr>
			    <td colspan="2"  align="center"><p  align="center">You will receive an email containing your new password.<p></td>
	            </tr>    
	            <tr>
			    <td><label for="login-email">Email*:</label></td>
	            <td><input type="text" placeholder="Email" class="req email" name="email" id="login-email"></td>
	            </tr>
	            <tr>
                <td colspan="2">
	                <small class="left"><a href="/register">Create account</a></small>
					<small class="right"><a href="/login">Login</a></small>
				</td>
                </tr>
            	</tbody>
            </table>
        </div>
         <input type="hidden"  name="Action" value="UserRecover">
       <a href="javascript:void(0);" onclick="validateSubmit('UserLogin');"><img src="images/template/submit-btn-m.png"></a>
       </form> 
    </div>
    
</div>       

{/block}