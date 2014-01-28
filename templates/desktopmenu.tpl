{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0}
	{assign var='count' value=0}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='news' value=0}
			{if $item.url eq 'blog'}{assign var='blog' value=1}{/if}
				<li class="{if count($item.subs) > 0}dropdown{/if} {if $item.selected eq 1}active{/if}"><a title="{$item.title}" href="{$parenturl}/{$item.url}" >{$item.title}</a>
			{if count($item.subs) > 0}
                {if $blog neq 1} 
                    <ul class="dropdown-menu">                                
                        {call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
                    </ul>
                {/if}
			{/if}
			</li>
		{else}
			{if $event eq 1}
				{assign var='count' value=$count+1}
				<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{else}
				<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{/if}
		{/if}
	{/foreach}	
{/function}

<ul id="menu" class="nav navbar-nav">
 {call name=render_list items=$menuitems}
 <li><a title="Shopping Cart" href="/store/shopping-cart"><span class="glyphicon glyphicon-shopping-cart"></span><div style="display:inline;" id="shopping-cart">{$itemNumber}</div></a>
</ul>
{/block}