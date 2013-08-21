{block name=mobilemenu}
{function name=render_list level=0 parenturl=""}
	
	{foreach $items as $item}
		{if $level lt 1}
			<li><a title="Our Menu" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>
				{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url}
				</ul>
			{/if}
			</li>
		{else}
			<li><a title="Our Menu" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url}
		{/if}
	{/foreach}	
	
{/function}

<div id="top-menu" class="row-fluid">
 		<div id="menu-icon"><span></span><span></span><span></span></div>
			<ul id="menu-top">
				{call name=render_list items=$menuitems}
			</ul>
	</div>
{/block}