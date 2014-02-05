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
 <li><a href="/store/shopping-cart" id="shop-cart-btn" data-html="true" data-container="body" data-placement="bottom" data-trigger="hover" data-content="{include file='popover-shopping-cart.tpl'}" data-toggle="popover" data-title="Your Shopping Cart">
 		<span class="glyphicon glyphicon-shopping-cart"></span>
 		<div style="display:inline;" id="nav-itemNumber">{$itemNumber}</div>
 		
 		<div class="badge" style="display:inline;" id="nav-subtotal">${$cart.cart_subtotal}</div>
 		
 	</a>
 </li>
 
{if $user.id}
	<li><a title="Log In" href="/login">G'day {$user.gname}</a></li>
	
	<li><a title="Log Out"  href="/process/user?logout=true"><span class="glyphicon glyphicon-log-out"></span><div style="display:inline;">Log Out</div></a></li>
	
{else}
	<li><a title="Log In" href="/login">Log In</a></li>
{/if}

</ul>
{/block}