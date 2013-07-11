{block name=body}
<style>
.error {
  	border: 1px solid #FF0000 !important;
	font-weight: bold;
	margin-left: 20px;
}
</style>
<script type="text/javascript" src="/includes/js/validation.js"></script>
<div class="container_16">
    <div class="grid_16">
		<h1 align="center">Register an account with All Fresh</h1> 
    </div><!-- end of grid_16 -->
    
    <div class="grid_8 prefix_4 suffix_4">
    <br><br>
        <h2>Create your account:</h2>
        <div class="error-div">
        {foreach key=key item=item from=$error}
        <br/><div class="error">*{$item}*</div><br/>
        {/foreach}
        </div>
        <div class="grid_8 alpha omega login">
        <form id="createaccount" name="createaccountform" action="/includes/processes/processes-general.php" method="post">
            <table cellspacing="0" cellpadding="0" border="0" class="reg-table">
                <tbody><tr>
		    <td><label for="reg-name">Name*:</label></td>
                    <td><input type="text" placeholder="Name" name="user_name" class="req" id="reg-name" value={$user_name} ></td>
                </tr>
                <tr>
		    <td><label for="reg-job">Job position*:</label></td>
                    <td><input type="text" placeholder="Job position" name="user_job_position" class="req" id="reg-job" value={$user_job_position}></td>
                </tr>
                <tr>
		    <td><label for="reg-business-name">Business name*:</label></td>
                    <td><input type="text" placeholder="Business name" name="user_business_name" class="req" id="reg-business-name" value={$user_business_name} ></td>
                </tr>
                <tr>
		    <td><label for="reg-abn">ABN*:</label></td>
                    <td><input type="text" placeholder="ABN" name="user_abn"  class="req" id="reg-abn" value={$user_abn}></td>
                </tr>
                
                <tr>
		    <td><label for="reg-ac-code">Account code*:</label></td>
                    <td><input type="text" placeholder="Account code" name="user_account_code"  class="req" id="reg-ac-code"  value={$user_account_code}></td>
                </tr>
                <tr>
		    <td><label for="reg-business-add">Business address*:</label></td>
                    <td><textarea  name="user_business_address"  class="req" id="reg-business-add" style="width: 246px; height: 51px;" >{$user_business_address}</textarea></td>
                </tr>
                <tr>
		    <td><label for="reg-business-ph">Business phone*:</label></td>
                    <td><input type="text" placeholder="Business phone" name="user_business_phone"  class="req" id="reg-business-ph"  value={$user_business_phone}></td>
                </tr>
                
                <tr>
		    <td><label for="reg-users-business-email">User's business email*:</label></td>
                    <td><input type="text" placeholder="User's business email" name="user_business_email" class="req email" id="reg-users-business-email"   value={$user_business_email}></td>
                </tr>
                <tr>
		    <td valign="top" ><label for="reg-google-email">Google registered email:</label></td>
                    <td><input type="text" placeholder="Google registered email" name="user_youtube_email" id="reg-google-email"  value={$user_youtube_email}></td>
                </tr>
                <tr>
                <tr>
                	<td>&nbsp;</td>
                	<td><small><a href="https://accounts.google.com/SignUp" target="_blank">how to create a google account</a></small></td>
                </tr>
                <tr>	
		    	<td><label for="reg-pwd">Password*:</label></td>
                    <td><input type="password" placeholder="Password" name="user_password"  class="req" id="reg-pwd"   value={$user_password}></td>
                </tr>
                <tr>
		    <td><label for="reg-pwd2">Re-type password*:</label></td>
                    <td><input type="password" placeholder="Password" name="user_password_confirm"  class="req" id="reg-pwd2"  value={$user_password_confirm} ></td>
                </tr>
            </tbody>
            </table>
        </div>
        <input type="hidden"  name="Action" value="RegisterUser">
        <a href="javascript:void(0);" onclick="validateSubmit('createaccount');"><img src="images/template/submit-btn-register.png"></a>
        </form> 
    </div>
    
</div>	   
       

{/block}