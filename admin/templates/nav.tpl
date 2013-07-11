{*	This template contains the structure for dynamically building a menu.
	Any hard coded menu items should also go here

	Variables:
	 	Array of menu items = $menuitems
		Company Name = {$company_name}
*}
{block name=nav}
<div class="row-fluid">
	<div class="span12">
	<div class="row-fluid"><div class="span12"><h4><a href='/admin/file-manager'>FILE MANAGER</a>  <a href="/admin/logout" class="right">LOGOUT</a></h4></div></div>
	{foreach item=item from=$menu name=foo}
		{if !$item.url|strstr:"slides"}
		<div class="row-fluid"><div class="span12">
		<div class="well">
			<ul class='nav nav-list'>
            	<li class="nav-header">{$item.title}</li>
				<li><a href='{$item.url}' class='list-header'>ALL</a></li>
				{foreach item=li from=$item.list}
					<li><a href='{$li.url}' class='list-header'>{$li.title}</a></li>
				{/foreach}

			</ul>
		</div>
		</div>
		</div>
			{if $smarty.foreach.foo.iteration eq "2" }
			<div class="row-fluid"><div class="span12">
				<div class="well">
					<ul class='nav nav-list'>
						<li class="nav-header"> Slides </li>
		            	<li class="nav-header"><a href='/admin/list/page-slides' class='list-header'>Home Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/carpet-page-slides' class='list-header'>Carpet and Flooring Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/curtains-page-slides' class='list-header'>Curtains and Blinds Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/specials-page-slides' class='list-header'>Special Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/product-page-slides' class='list-header'>Product Care Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/contact-page-slides' class='list-header'>Contact us Slides</a></li>
		            	<li class="nav-header"><a href='/admin/list/about-page-slides' class='list-header'>About us Slides</a></li>
					</ul>
				</div>
			 </div>
			</div>
			{/if}
		{/if}
	{/foreach}
	</div>
</div>
{/block}