{block name=body}
<style>
.error {
    border: 1px solid #FF0000!important;
}
</style>
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Ask a question</small><br><br></div>
    <div class="grid_8">
		{$content}
        <script type="text/javascript" src="/includes/js/validation.js"></script>	   
        <div class="grid_8 alpha omega contact-us">
        	 {foreach key=key item=item from=$error}
	        <br/><div class="error">{$item}</div><br/>
	        {/foreach}
        	<form id="emailform" name="emailform" action="/includes/processes/processes-general.php" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="contact-us-table">
                <tr>
                    <td><input type="text" placeholder="Name" class="req" name="name" /></td>
                </tr>
                <tr>
                    <td><input type="text" placeholder="Email"  class="req email" name="email"/></td>
                </tr>
                <tr>
                    <td><input type="text" placeholder="Question"  class="req " name="question"/>
                </tr>
            </table>
            <input type="hidden" name="Action" value="UserAsk">
            </form>
        </div>
        <a href="javascript:void(0);"  class="submit-btn" onclick="validateSubmit('emailform');" >
        	<img src="/images/template/submit-btn-l.png" />
        </a>
     	
    </div><!-- end of grid_8 -->
    <div class="grid_8">
	</div>
</div><!-- end of container_16 -->
{/block}