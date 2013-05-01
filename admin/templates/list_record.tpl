{block name=body}
<div class="row-fluid ">
	<div class="span12">
		<ul id='nav-list'>
			{foreach item=li from=$list}
				<li><a href='{$li.url}' class='list-header'><b>{$li.title}</b></a></li>
			{/foreach}
		</ul>
	</div>
</div>
{/block}