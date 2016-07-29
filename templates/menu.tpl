{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0 ismobile=0}
	{assign var='count' value=0}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='news' value=0}
			{if $item.url eq 'blog'}{assign var='blog' value=1}{/if}
			<li class="{if count($item.subs) > 0}dropdown{/if} {if $item.selected eq 1}current{/if}">
				<a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}" >{$item.title} {if count($item.subs) > 0 && $ismobile neq 1}<span class="arrow-down"></span>{/if}</a>
			  {if count($item.subs) > 0}
          	<ul class="{if $ismobile neq 1}dropdown-menu{else}subcat-menu{/if}">
              {call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu ismobile=$ismobile}
            </ul>
			  {/if}
			</li>
		{else}
	     <li class="sub-li {if $item.selected eq 1}active{/if}">
		<a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}">{$item.title}</a>
		{/if}
	{/foreach}
{/function}

{*
<!-- Menu and Header sections go here. -->
<div id="top">
  <div class="container">
    <div class="row">
      <div class="col-sm-3 hidden-xs">
        <a href="/"> <img src="/images/logo.png" alt="" class="img-responsive" />
        </a>
      </div>
      <div class="col-xs-3 visible-xs">
        <a href="/"> <img id="mobilelogo" src="/images/mobile-logo.png" alt="" class="img-responsive" />
        </a>
      </div>
      <div class="col-xs-5 col-xs-offset-2 col-sm-3 col-sm-offset-2 col-md-9 col-md-offset-0 col-lg-8 col-lg-offset-1">
        {if isset($user.gname)}
        <a title="My account" class="btn btn-blue pull-right hidden-xs hidden-sm" href="/my-account">My account</a>
        {else}
        <a id="signin" href="/login-register" class="btn btn-blue pull-right hidden-xs hidden-sm">Sign in</a>
        {/if}
        <div id="cart" class="dropdown navbar-right">
          <a href="/shopping-cart" class="dropdown-toggle" id="cart-hover"> <img src="/images/basket.png" alt="Shopping Cart" /> <span style="" class="nav-itemNumber">{$itemNumber}</span>
          </a>
          <ul class="dropdown-menu" id="shop-cart-btn">{include file='popover-shopping-cart.tpl'}
          </ul>
        </div>

        <div class="navbar-offcanvas navbar-offcanvas-touch navbar-offcanvas-right " id="navbar-collapse-1">
          <ul id="topmenu" class="nav navbar-nav" style="margin-right: 20px;">{call name=render_list items=$menuitems}</ul>
        </div>

        <div class="navbar-offcanvas navbar-offcanvas-touch navbar-offcanvas-right hidden-md hidden-lg" id="navbar-collapse-2">
          <ul id="topmenu2" class="nav navbar-nav ">
            {call name=render_list items=$menuitems ismobile=1} {if $user.id}
            <li><a title="My Account" href="/my-account">My account</a></li>
            <li><a title="Log Out" href="/process/user?logout=true">Log Out</a></li> {else}
            <li><a title="Log In" href="/login-register">Log In</a></li> {/if}
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
*}

<header class="" id="menu">
  <!-- ***** Top menu ***** -->
  <div class="bs-docs-nav navbar navbar-static-top hidden-sm hidden-xs" id="top-menu">
    <div class="container">
      <nav class="collapse navbar-collapse navbar-inverse" id="main-navbar">
        <ul class="nav navbar-nav navbar-right">
         {call name=render_list items=$menuitems.top-header ismobile=0}
        </ul>
      </nav>
    </div>
  </div>
  <!-- ***** Main menu ***** -->
  <div class="bs-docs-nav navbar navbar-static-top hidden-sm hidden-xs" id="main-menu">
    <div class="container">
      <div class="navbar-header">
        <button aria-controls="main-navbar" aria-expanded="false" class="collapsed navbar-toggle" data-target="#main-navbar" data-toggle="collapse" type="button">
          <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
        </button>
        <a href="/" title="Home" class="navbar-brand"><img src="/images/logo.png" alt="logo" class="img-responsive" /></a>
      </div>
      <nav class="collapse navbar-collapse" id="main-navbar">
        <ul class="nav navbar-nav">
          {call name=render_list items=$menuitems.main-header ismobile=0}
        </ul>
      </nav>
    </div>
  </div>
  <!-- ***** Mobile menu ***** -->
  <div class="bs-docs-nav navbar navbar-static-top visible-sm visible-xs" id="mobile-menu">
    <div class="container">
      <div class="navbar-header">
        <button aria-controls="main-navbar" aria-expanded="false" class="collapsed navbar-toggle" data-target="#main-navbar" data-toggle="collapse" type="button">
          <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
        </button>
        <a href="/" title="Home" class="navbar-brand"><img src="/images/logo.png" alt="logo" class="img-responsive" /></a>
      </div>
      <nav class="collapse navbar-collapse" id="main-navbar">
        <ul class="nav navbar-nav">
          {call name=render_list items=$menuitems.main-header ismobile=1}
        </ul>
      </nav>
    </div>
  </div>
</header>
{/block}


