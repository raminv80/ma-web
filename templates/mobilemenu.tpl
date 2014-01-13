{block name=mobilemenu}
{function name=render_mobile_list level=0 parenturl=""}
	{foreach $items as $item}
		{if $level lt 1}
			{assign var='news' value=0}
			<!-- {if $item.url eq 'news'}{assign var='news' value=1}{/if} -->
			<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
			{if count($item.subs) > 0}
                <!--  {if $news neq 1} -->
                                <ul>
				{call name=render_mobile_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				</ul>
                <!-- {/if} -->
			{/if}
			</li>
		{else}
			<li {if $item.selected eq 1}class="active"{/if}><a title="{$item.title}" href="{$parenturl}/{$item.url}">{$item.title}</a>
		{/if}
	{/foreach}	
	
{/function}

<div id="top-menu" class="row">
	<div id="menu-icon"><span></span><span></span><span></span></div>
	<ul id="menu-top">
		{call name=render_mobile_list items=$menuitems}
		<li><a title="Bet Now" href="https://tatts.com/tattsbet" target="_blank" >Bet Now</a>
	</ul>
</div>	
{literal}
<script type="text/javascript">
$(document).ready(function(){ $('#menu-icon').attr('tabindex', '0');} )
</script>
{/literal}
{/block}
