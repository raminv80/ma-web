{*	This template contains the structure for dynamically building a menu.
	Any hard coded menu items should also go here

	Variables:
	 	Array of menu items = $menuitems
		Company Name = {$company_name}
*}
{block name=nav}
<div class="row-fluid">
	<div class="span12">
	<div class="row-fluid"><div class="span12"><h3><a href='/admin/file-manager'>FILE MANAGER</a></h3></div></div>
	{foreach item=item from=$menu}
		<div class="row-fluid"><div class="span12"><h3>{$item.title}</h3>
		<div>
			<ul id='nav-list'>
				<li><a href='{$item.url}' class='list-header'><b>ALL</b></a></li>
				{foreach item=li from=$item.list}
					<li><a href='{$li.url}' class='list-header'><b>{$li.title}</b></a></li>
				{/foreach}
			</ul>
		</div>
		</div></div>
	{/foreach}
	</div>
</div>
{/block}