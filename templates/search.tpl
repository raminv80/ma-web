{block name=body}
<div class="container_16">
<div class="grid_16">
<h1>Search Results: ({$count}) found for {$term}</h1>
<div class="results">
{if $results.pages|count neq 0}
	<div class="items-div">
	<h2>Pages</h2><br/>
	{foreach $results.pages as $item}
		{include file='search-page-item.tpl'}
	{/foreach}
	</div>
{/if}
{if $results.news|count neq 0}
	<div class="items-div">
	<h2>Fresh news</h2><br/>
	{foreach $results.news as $item}
		{include file='search-news-item.tpl'}
	{/foreach}
	</div>
{/if}
{if $results.faq|count neq 0}
	<div class="items-div">
	<h2>FAQ's</h2><br/>
	{foreach $results.faq as $item}
		{include file='search-faq-item.tpl'}
	{/foreach}
	</div>
{/if}
{if $results.video|count neq 0}
	<div class="items-div">
	<h2>Videos</h2><br/>
	{foreach $results.video as $item}
		{include file='search-video-item.tpl'}
	{/foreach}
	</div>
{/if}
</div>
</div>
</div>
{/block}