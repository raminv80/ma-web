{block name=breadcrumb}
{* Define the function *}
{function name=breadcrumbs level=0 parenturl=""}
	{foreach $items as $item}
		{if count($item.subs) > 0} / <a href="{$parenturl}#{$item.url}">{$item.title}</a> {call name=breadcrumbs items=$item.subs level=$level+1 parenturl="/our-menu"}{else} / {$item.title}{/if}
	{/foreach}
{/function}
	<div class="crumbs">
		<a href="/">Home</a>{call name=breadcrumbs items=$breadcrumbs}
	</div>
{/block}