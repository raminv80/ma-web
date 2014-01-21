{* This template contains the structure for dynamically building a menu. Any hard coded menu items should also go here Variables: Array of menu items = $menuitems Company Name = {$company_name} *} {block name=nav}
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span12">
				<h4>
					<a href='/admin/file-manager'>FILE MANAGER</a> <a href="/admin/logout" class="right">LOGOUT</a>
				</h4>
			</div>
		</div>
		{foreach item=item from=$menu name=foo}
		<div class="row-fluid">
			<div class="span12">
				<div class="well">
					<ul class='nav nav-list'>
						<li class="nav-header">{$item.title}</li>
						<li><a href='{$item.url}' class='list-header'>ALL</a></li> {foreach item=li from=$item.list}
						<li><a href='{$li.url}' class='list-header'>{$li.title}</a></li> {/foreach}
					</ul>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
</div>
{/block}
