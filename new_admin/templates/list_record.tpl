{block name=body}
<div class="grid_12 right">
	<ul id='nav-list'>
		{foreach item=li from=$list}
			<li><a href='{$li.url}' class='list-header'><b>{$li.title}</b></a></li>
		{/foreach}
	</ul>
</div>
{/block}