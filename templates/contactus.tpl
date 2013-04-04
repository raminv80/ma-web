{block name=body}
<style>
.error {
    border: 1px solid #FF0000!important;
}
</style>
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Contact us</small><br><br></div>
    <div class="grid_8">
		<h1>Contact us</h1> 
        <p>123 What Street<br>
        Adelaide SA 5000<br>
        P 08 8888 8888<br>
        E <a href="#">info@allfresh.com.au</a></p>
        <script type="text/javascript" src="/includes/js/validation.js"></script>	   
        <div class="grid_8 alpha omega contact-us">
        	 {foreach key=key item=item from=$error}
	        <br/><div class="error">{$item}</div><br/>
	        {/foreach}
        	<form id="emailform" name="emailform" action="/includes/processes/processes-contactus.php" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="contact-us-table">
                <tr>
		    <td><label for="contact-name">Name*:</label></td>
                    <td><input type="text" placeholder="Name" class="req" name="name" id="contact-name" /></td>
                </tr>
                <tr>
		    <td><label for="contact-email">Email*:</label></td>
                    <td><input type="text" placeholder="Email"  class="req email" name="email" id="contact-email" /></td>
                </tr>
<tr>
	<td colspan="2">Enter your query here:</td>
</tr>
                <tr>
                    <td colspan="2"><textarea placeholder="Enter your query here" rows="4" name="query"></textarea></td>
                </tr>
            </table>
            </form>
        </div>
        <a href="javascript:void(0);"  class="submit-btn" onclick="validateSubmit('emailform');" >
        	<img src="/images/template/submit-btn-l.png" />
        </a>
     	
    </div><!-- end of grid_8 -->
    <div class="grid_8">
		<h1>&nbsp;</h1> 
    		<iframe width="460" height="365" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com.au/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=adelaide&amp;aq=&amp;sll=-34.985965,138.701955&amp;sspn=1.822688,2.730103&amp;ie=UTF8&amp;hq=&amp;hnear=Adelaide+South+Australia&amp;t=m&amp;ll=-34.922112,138.606606&amp;spn=0.025687,0.039482&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com.au/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=adelaide&amp;aq=&amp;sll=-34.985965,138.701955&amp;sspn=1.822688,2.730103&amp;ie=UTF8&amp;hq=&amp;hnear=Adelaide+South+Australia&amp;t=m&amp;ll=-34.922112,138.606606&amp;spn=0.025687,0.039482&amp;z=14&amp;iwloc=A">View Larger Map</a></small>
    </div>
</div><!-- end of container_16 -->
{/block}