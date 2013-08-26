{block name=mobilemenu}
{function name=render_mobile_list level=0 parenturl=""}
	
	{foreach $items as $item}
		{if $level lt 1}
			{if $item.url eq 'our-menu'}{assign var='menu' value=1}{else}{assign var='menu' value=0}{/if}
			<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				</ul>
			{/if}
			</li>
		{else}
			{if $menu eq 1}
				{if $item.category eq 1}
				<li {if $item.selected eq 1}class="current"{/if}><a title="{$item.title}" href="{$parenturl}#{$item.url}">{$item.title}</a>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
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
				{call name=render_mobile_list items=$menuitems}
			</ul>
	</div>
{/block}