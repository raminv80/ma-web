{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='menu' value=0}
			{if $item.url eq 'our-menu'}{assign var='menu' value=1}{/if}
			{if $item.url eq 'our-locations'}{assign var='menu' value=2}{/if}
			<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				</ul>
			{/if}
			</li>
		{else}
			{if $menu eq 1}
				{if $item.category eq 1 and $item.listings eq 1}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}#{$item.url}">{$item.title}</a>
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{elseif $menu eq 2}
				{if $item.category eq 1 and $item.listings eq 1}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}#{$item.category_name|strtolower}">{$item.category_name}</a>
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{elseif $item.url eq "news-and-media"}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{else}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
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
	  				<div id="find"><a title="Our Locations" href="/locations">Find your Nearest Location</a></div>
	  				<div id="search"><input id="searchbox" type="text" value="Search..." /></div>
	  				<div id="social">
	  					<a title="Facebook" href="#"><img src="/images/facebook.png" alt="Facebook" /></a>
	  					<a title="Twitter" href="#"><img src="/images/twitter.png" alt="Twitter" /></a>
	  					<a title="Instagram" href="#"><img src="/images/instagram.png" alt="Instagram" /></a>
	  					<a title="4Square" href="#"><img src="/images/4square.png" alt="4Square" /></a>
	  				</div>
	  				<!-- <div id="franchise">
	  					<a title="Franchise Login Section" href="/login"><img src="images/franchise.png" alt="Franchise" /></a>
	  				</div> -->
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