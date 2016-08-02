{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 menu=0 ismobile=0}
  {assign var='count' value=0}
    {foreach $items as $item}
      {if $level lt 1}
		<li class="{if count($item.subs.list) > 0}dropdown{/if} {if $item.selected eq 1}active{/if}">
		  {if $item.type eq 1 && !$user.id}		
            <a title="Login" href="/login" >Login</a>
          {else}
            <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{$item.url}" >{$item.title} {if count($item.subs.list) > 0 && $ismobile neq 1}<span class="arrow-down"></span>{/if}</a>
          {/if}          
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

<header class="" id="menu">
  <!-- ***** Top menu ***** -->
  <div class="bs-docs-nav navbar navbar-static-top hidden-sm hidden-xs" id="top-menu">
    <div class="container">
      <nav class="collapse navbar-collapse navbar-inverse" id="main-navbar">
        <ul class="nav navbar-nav navbar-right">
         {call name=render_list items=$menuitems['top-header']['list'] ismobile=0}
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
          {call name=render_list items=$menuitems['main-header']['list'] ismobile=0}
        </ul>
      </nav>
       <div id="cart" class="dropdown navbar-right">
          <a href="/shopping-cart" class="dropdown-toggle" id="cart-hover"> <img src="/images/cart.png" alt="Shopping Cart" /> <span style="" class="nav-itemNumber">{$itemNumber}</span>
          </a>
          <ul class="dropdown-menu" id="shop-cart-btn">{include file='popover-shopping-cart.tpl'}
          </ul>
        </div>
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
          {call name=render_list items=$menuitems['main-header']['list'] ismobile=1}
        </ul>
      </nav>
    </div>
  </div>
</header>
{/block}


