{block name=footer}

<footer>

<div id="foot1">
	<div class="container foot-menu">
		<ul>
		<li class="foot-nav page-nav">
			<ul>
				<li><span class="h3">Talk to us</span></li>
				{foreach $menuitems as $item}
				{if $item.menu_group eq 'talk'}<li class="menu-page"><a href="{$item.url}" target="_blank"><span>{$item.title}</span></a></li>{/if}
			  {/foreach}
			</ul>
			<ul>
				<li><span class="h3">About</span></li>
        {foreach $menuitems as $item}
				{if $item.menu_group eq 'about'}<li class="menu-page"><a href="{$item.url}" target="_blank"><span>{$item.title}</span></a></li>{/if}
				{/foreach}
			</ul>
		</li>
		</ul>
		<ul>
		{assign var="halffooter" value=0}
		{foreach $menuitems as $item}{if $item.type_id eq 4 & count($item.subs) > 0}{assign var="halffooter" value=$halffooter+1}{/if}{/foreach}
		{assign var="halffooter" value=$halffooter/2}
		{assign var="y" value=0}
		{foreach $menuitems as $item}
		{if $item.type_id eq 4 & count($item.subs) > 0}
		<li class="foot-nav cat-nav">
			<ul >
				<li><a href="{$item.url}" target="_blank"><span class="h3">{$item.title}</span></a></li>
    	  {foreach $item.subs as $s}
				<li class="category-page"><a href="{$item.url}/{$s.url}" target="_blank"><span>{$s.title}</span></a></li>
				{/foreach}
			</ul>
		</li>
		{assign var="y" value=$y+1}{if $y gte $halffooter}</ul><ul>{assign var="y" value=-1}{/if}
    {/if}
	  {/foreach}
		</ul>
	</div>
</div>

<div id="foot2">
	<div class="container">
		<div class="col-sm-7 col-md-6 text-left">
		Copyright {'Y'|date} 
		</div>
		<div class="col-sm-5 col-md-6 text-right">
		Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them Advertising</a>
		</div>
	</div>
</div>

</footer>
{/block}
