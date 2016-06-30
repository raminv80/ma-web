{block name=body}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=587832504567695";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div class="row form">
	{if $them_news_page neq ''}
	<div class="col-sm-12">
 		<iframe id="them-iframe" class="them-promotion-frame" src="{$them_news_page}" width="100%" height="1000px" border="0">
			<p>Your browser does not support iframes.</p>
		</iframe> 
	</div> 
	{else}
	<div class="col-sm-offset-1 col-sm-11">
		<h3>Stay up to date with Them Advertising</h3>
		<div class="fb-page" data-href="https://www.facebook.com/ThemAdvertising" data-width="500" data-height="1000" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/ThemAdvertising"><a href="https://www.facebook.com/ThemAdvertising">Them Advertising</a></blockquote></div></div>
	</div>
	{/if}
</div>

<script type="text/javascript">
	$('#them-iframe').load(function(){
		 var win = document.getElementById("them-iframe").contentWindow;
		 win.postMessage('unused',"{$them_news_domain}"); 
	});
</script>
{/block}
