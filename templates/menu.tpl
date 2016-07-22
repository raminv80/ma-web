{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0}
	{assign var='count' value=0}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='news' value=0}
			{if $item.url eq 'blog'}{assign var='blog' value=1}{/if}
				<li class="{if count($item.subs) > 0}dropdown {/if}{if $item.selected eq 1}active{/if}">
					{if $item.url eq 'specials'}
						{if $specialCnt gt 0}<a style="color:red;" {if count($item.subs) > 0}class="dropdown-toggle"{/if} title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}" >{$item.title}</a>{/if}
					{else}
						<a {if count($item.subs) > 0}class="dropdown-toggle"{/if} title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}" >{$item.title}</a>
					{/if}
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
				<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}">{$item.title}</a>
			{else}
				<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}">{$item.title}</a>
			{/if}
		{/if}
	{/foreach}
{/function}

<div id="header" class="container">
    <div id="logo" class="col-sm-1 col-md-2">
      <a href="/"><img src="/images/logo.png" alt="The Ergo Centre" class="img-responsive {if $listing_id neq 1}insidepg{/if}" /></a>
    </div>
    <div class="visible-xs navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
        <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
      </button>
    </div>
    <div class="visible-xs" id="mobcart">
      <a href="/shopping-cart"><img src="/images/cart.png" alt="Cart" />
        <div style="display: inline;" class="nav-itemNumber">{if $itemNumber}({$itemNumber}){else}(0){/if}</div> </a>
    </div>
    <div id="menuout" class="col-sm-11 col-md-10">
      <ul id="menu" class="nav navbar-collapse collapse {if $listing_id neq 1}insidep{/if}" data-specials="{$specialCnt}">
        {call name=render_list items=$menuitems}

        <li class="dropdown grey navbar-right" id="cartmenu">
          <a href="/shopping-cart" class="dropdown-toggle"> <img src="/images/cart.png" alt="Cart" />
            <div style="display: inline;" class="nav-itemNumber">{if $itemNumber}({$itemNumber}){else}(0){/if}</div> <!--<div class="badge nav-subtotal" style="display:inline;">${$subtotal|number_format:2:'.':','}</div>-->
          </a>
          <ul class="dropdown-menu" id="shop-cart-btn">
            {include file='popover-shopping-cart.tpl'}
          </ul>
        </li> 
        {if $user.id}
          <li class="login navbar-right"><a title="My Account" href="/my-account">MY ACCOUNT</a></li> 
        {else}
          <!-- <li><a title="Log In" href="/login">Log In</a></li> -->
          <li class="login navbar-right"><a title="Log In" href="/login-register">Login</a></li> 
        {/if}
      </ul>
    </div>
  </div> {/block}