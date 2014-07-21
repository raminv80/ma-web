{block name=body}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=212771495598580&version=v2.0";
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
		<div class="fb-like-box" data-href="https://www.facebook.com/ThemAdvertising" data-width="640" data-height="1000" data-colorscheme="light" data-show-faces="false" data-header="true" data-stream="true" data-show-border="false"></div>
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
