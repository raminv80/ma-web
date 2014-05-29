{block name=body}
<div class="row">
	<div class="col-sm-12">
		<p>Use menu on the left</p>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<iframe id="them-iframe" class="them-promotion-frame" src="{$them_news_page}" width="100%" height="640" border="0">
			<p>Your browser does not support iframes.</p>
		</iframe>
	</div>
</div>

<script type="text/javascript">
	$('#them-iframe').load(function(){
		 var win = document.getElementById("them-iframe").contentWindow;
		 win.postMessage('unused',"{$them_news_domain}"); 
	});
</script>
{/block}
