{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 menu=0 ismobile=0}
  {assign var='count' value=0}
    {foreach $items as $item}
      {if $level lt 1}
		<li class="{if count($item.subs.list) > 0}dropdown{/if} {if $item.selected eq 1}active{/if}">
		  <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{if $item.type neq 2}{$item.url}{else}javascript:void(0){/if}" >
		  {if $item.icon}<img src="{$item.icon}" alt="" />{/if}
          {$item.title} {if count($item.subs.list) > 0 && $ismobile neq 1}<span class="arrow-down"></span>{/if}
          </a>
		  {if count($item.subs.list) > 0 && $item.type neq 1}
            <ul class="{if $ismobile neq 1}dropdown-menu{else}subcat-menu{/if}">
              {call name=render_list items=$item.subs.list level=$level+1 menu=$menu ismobile=$ismobile}
            </ul>
		  {/if}
	     </li>
       {else}
	     <li class="sub-li {if $item.selected eq 1}active{/if}">
		 <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{$item.url}">{$item.title}</a>
	   {/if}
	{/foreach}
{/function}

  <!-- ***** Top menu ***** -->
<div id="top">
	<div class="container">
		<div class="row">
		<div class="col-xs-2 col-sm-2 visible-xs visible-sm" id="mobmenu">
        	<button aria-controls="main-navbar" aria-expanded="false" class="collapsed navbar-toggle" data-target="#mobile-navbar" data-toggle="collapse" type="button">
			<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
			</button>
			</div>
			<div class="col-xs-8 col-sm-8 col-md-3" id="logoout">
				<a href="/" title="Home"><img src="/images/moblogo.svg"	class="img-responsive" alt="Logo" id="logo" /></a>
			</div>
			<div class="col-sm-4 col-sm-offset-1 hidden-sm hidden-xs" id="top-menu">
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						{call name=render_list items=$menuitems['top-header']['list'] ismobile=0}
					</ul>
				</nav>
			</div>
			<div class="hidden-xs hidden-sm col-sm-3 text-right" id="call">
				<a href="tel:1800 88 22 22">
					<div class="callout">
						Call Membership Services
						<div class="phno">1800 88 22 22</div>
					</div>
					<img src="/images/header-phone.png" alt="Phone" />
				</a>
			</div>
			<div class="col-xs-2 col-sm-2 visible-xs visible-sm" id="mobcart">

			</div>
		</div>
	</div>
</div>
  <!-- ***** Main menu ***** -->
<div id="main">
	<div class="container">
		<div class="row">
		  	<div class="hidden-md hidden-lg">
			<nav class="collapse navbar-collapse" id="mobile-navbar">
				<ul class="nav navbar-nav">
					{call name=render_list items=$menuitems['mobile-header']['list'] ismobile=0}
				</ul>
			</nav>
		  	</div>

			<div class="col-sm-10">
				<div class="hidden-xs hidden-sm" id="main-navbar">
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
					{call name=render_list items=$menuitems['main-header']['list'] ismobile=0}
					</ul>
				</nav>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 visible-xs visible-sm" id="mobsearch">
				<div id="mobin">
					<input type="text" id="search" placeholder="I'm looking for..." /><input type="image" src="/images/mob-header-search.png" alt="Search" />
				</div>
			</div>
			<div class="col-sm-2 hidden-xs hidden-sm" id="headactions">
				<a href="#" id="wishlist"><img src="/images/header-wishlist.png" alt="Wishlist" /></a>
				<div id="cartout">
				<div id="cart" class="dropdown">
					<a href="/shopping-cart" class="dropdown-toggle" id="cart-hover"> <img src="/images/cart.svg" alt="Shopping Cart" class="visible-xs visible-sm" /> <img src="/images/header-basket.png" alt="Shopping Cart" class="hidden-xs hidden-sm" /> <span style="" class="nav-itemNumber">{$itemNumber}</span>
					</a>
					<ul class="dropdown-menu" id="shop-cart-btn">{include file='popover-shopping-cart.tpl'}
					</ul>
				</div>
				</div>
				<a href="#" id="wishlist"><img src="/images/header-search.png" alt="Search" /></a>
			</div>
		</div>
	</div>
</div>
{/block}


