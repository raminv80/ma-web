{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='menu' value=0}
			<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				</ul>
			{/if}
			</li>
		{else}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.category_name}</a>
				{if $item.category eq 1 and $item.listings eq 1}
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				{/if}
		{/if}
	{/foreach}	
{/function}

<div id="headout2">
	  	<div class="container">
	  		<div class="row-fluid">
	  			<div id="logo"><a title="Cocolat" href="/"><img src="/images/logo.png" alt="Cocolat Logo" /></a></div>

	  			<div id="menu1">
	  				<div id="find"><a title="Our Locations" href="/our-locations">Find your Nearest Location</a></div>
	  				<div id="search"><input id="searchbox" type="text" value="" placeholder="Search..."/></div>
	  				<div id="social">
	  					<a title="Facebook" href="#"><img src="/images/facebook.png" alt="Facebook" /></a>
	  					<a title="Twitter" href="#"><img src="/images/twitter.png" alt="Twitter" /></a>
	  					<a title="Instagram" href="#"><img src="/images/instagram.png" alt="Instagram" /></a>
	  					<a title="4Square" href="#"><img src="/images/4square.png" alt="4Square" /></a>
	  				</div>
	  				<!-- <div id="franchise">
	  					<a title="Franchise Login Section" href="/login"><img src="images/franchise.png" alt="Franchise" /></a>
	  				</div> -->
	  				<script type="text/javascript">
	  				$(document).ready(function(){
	  				$('#searchbox').bind('keyup', function(event) {
	  				    if(event.keyCode==13){
	  				    	$('body').append($('<form/>')
	  				    		  .attr({ 'action': '/search', 'method': 'post', 'id': 'replacer' })
	  				    		  .append($('<input/>')
	  				    		    .attr({ 'type': 'hidden', 'name': 'search', 'value': $('#searchbox').val() })
	  				    		  )
	  				    		).find('#replacer').submit();  
	  				    }
	  				});
	  				});
	  				</script>
	  			</div>

	  			<div id="menu2">
	  				<ul>
		  			{call name=render_list items=$menuitems}
		  			</ul>
	  			</div>
	  		</div>
	  	</div>
	  </div>
{/block}