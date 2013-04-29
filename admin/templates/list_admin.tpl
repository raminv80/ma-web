{block name=body}
<div class="row-fluid ">
	<div class="span4"><a href="/admin/edit/admin">ADD NEW</a></div>
	<div class="span8">
		<ul id='nav-list'>
			{foreach item=li from=$list}
				<li><a href='{$li.url}' class='list-header'><b>{$li.title}</b></a></li>
			{/foreach}
		</ul>
	</div>
</div>
{/block}