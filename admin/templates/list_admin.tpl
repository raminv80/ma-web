{block name=body}
<div class="grid_12 right">
	<div class="grid_4 left"><a href="/new_admin/edit/admin">ADD NEW</a></div>
	<div class="grid_12 alpha omega">
	<ul id='nav-list'>
		{foreach item=li from=$list}
			<li><a href='{$li.url}' class='list-header'><b>{$li.title}</b></a></li>
		{/foreach}
	</ul>
	</div>
</div>
{/block}