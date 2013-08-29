{block name=mobilemenu}
{function name=render_mobile_list level=0 parenturl=""}
	
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='menu' value=0}
			{if $item.url eq 'our-menu'}{assign var='menu' value=1}{/if}
			{if $item.url eq 'our-locations'}{assign var='menu' value=2}{/if}
			<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>
				{if $item.url eq 'our-menu'}<li><a title="Customer Favourites" href="/our-menu#favourites">Customer Favourites</a>{/if}
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				{if $item.url eq 'our-menu'}<li><a title="What\'s New" href="/our-menu#whats-new">What's New</a>{/if}
				</ul>
			{/if}
			</li>
		{else}
			{if $menu eq 1}
				{if $item.category eq 1 and $item.listings eq 1}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}#{$item.url}">{$item.title}</a>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{elseif $menu eq 2}
				{if $item.category eq 1 and $item.listings eq 1}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}#{$item.category_name|strtolower}">{$item.category_name}</a>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{elseif $item.url eq "news-and-media"}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{else}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
			{/if}
		{/if}
	{/foreach}	
	
{/function}

<div id="top-menu" class="row-fluid">
 		<div id="menu-icon"><span></span><span></span><span></span></div>
			<ul id="menu-top">
				<li><a title="Home" href="/">Home</a>
				{call name=render_mobile_list items=$menuitems}
			</ul>
	</div>
{/block}