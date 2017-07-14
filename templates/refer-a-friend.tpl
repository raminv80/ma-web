{block name=body}

<div id="pagehead">
  <div class="bannerout">
	<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container" id="contpage">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
      	<h1>{$listing_title}</h1>
      </div>
      <div class="col-md-10 col-md-offset-1 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="contact" class="refer">
  <div class="container">
 	 <div class="row">
		<div class="col-md-offset-1 col-md-10 text-center" id="referfriend">
    	 	<form id="refer_friend_form" accept-charset="UTF-8" method="post" action="/process/refer-friend" novalidate="novalidate">
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Refer a friend" name="form_name" id="form_name" />
    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="name">Your name<span>*</span>:</label>
    					<input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="email">Your email<span>*</span>:</label>
    					<input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="memberno">Membership number:</label>
    				  <input class="form-control" value="{if $post.memberno}{$post.memberno}{else}{$user.id}{/if}" type="text" name="memberno" id="memberno">
						<div class="error-msg help-block"></div>
    				</div>

                    <div class="col-sm-6 form-group">
                    </div>
    			</div>
    			<div class="row">
	    			<div class="col-sm-12">
		    			<hr />
	    			</div>
    			</div>

    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="frname">Your friend's name<span>*</span>:</label>
    					<input class="form-control" value="{$post.friendname}" type="text" name="friendname" id="frname" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="fremail">Your friend's email<span>*</span>:</label>
    					<input class="form-control" value="{$post.friendemail}" type="email" name="friendemail" id="fremail" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>

    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="submit" value="Refer your friend now" class="btn-red btn" id="fbsub">
    				</div>
    			</div>
    	  </form>
          <br><br>
		</div>


		</div>
	</div>
</div>

<div id="orangebox" class="visible-xs">

</div>
{/block}

{block name=tail}
<script type="text/javascript">
$(document).ready(function(){
	$('#refer_friend_form').validate();
});
</script>
{/block}
