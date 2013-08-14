{block name=breadcrumb}
{* Define the function *}
{function name=breadcrumbs level=0}
	{foreach $items as $item}
		{if count($item.subs) > 0} / <a href="{$item.url}">{$item.title}</a> {call name=breadcrumbs items=$item.subs level=$level+1}{else} / {$item.title}{/if}
	{/foreach}
{/function}
	<div class="crumbs">
		<a href="#">Home</a>{call name=breadcrumbs items=$breadcrumbs}
	</div>
{/block}