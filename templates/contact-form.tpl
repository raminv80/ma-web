{block name=contactform}
<div id="contact-form-container">
	<form id="emailform" name="emailform" action="processemail.php" method="post">
		<table cellpadding="0" cellspacing="3" border="0">
        	<tr>	
            	<td width="170" align="right"><p class="lable">*Name:</p></td>
            	<td><p><input type="text" id="name" name="name" rel="required" class="contact-form-input" /></p></td>
        	</tr>
        	<tr>	
	            <td align="right"><p class="lable">*Contact No:</p></td>
    	        <td><p><input type="text" id="contactno" name="contact_no" rel="required" class="contact-form-input" /></p></td>
        	</tr>
        	<tr>	
	            <td align="right"><p class="lable">*Email:</p></td>
            	<td><p><input type="text" id="email" name="email" rel="required" class="contact-form-input" /></p></td>
        	</tr>
        	<tr>	
	            <td align="right" valign="top"><p class="lable">Preferred Contact Time:</p></td>
            	<td class="checkbox">
	            	<p><input name="preferred_contact_time_morning" type="checkbox" value=""> <span class="lable">Morning</span></p>
            		<p><input name="preferred_contact_time_afternoon" type="checkbox" value=""> <span class="lable">Afternoon</span></p>
                	<p><input name="preferred_contact_time_evening" type="checkbox" value=""> <span class="lable">Evening</span></p>
            	</td>
        	</tr>
        	<tr>
	        	<td></td>
        		<td><br /><span class="note">Note: fields marked with an asterisk(*) are required to submit this form.<br /><br /></span></td>
        	</tr>
        	<tr>
	        	<td></td>
        		<td><input type="submit" value="Submit" onclick="return CheckAndSubmitForm();" /></td>
        	</tr>
    	</table>
	</form>
</div>
{/block}