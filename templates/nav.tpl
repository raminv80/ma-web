{*	This template contains the structure for dynamically building a menu.
	Any hard coded menu items should also go here

	Variables:
	 	Array of menu items = $menuitems
		Company Name = {$company_name}
*}
{block name=nav}
 <div id="navigation" class="alpha grid_11 omega right">
        <div id="top-nav" class="alpha grid_8 omega">
          <ul>
                <li><a href="/about-us">About us</a></li>
                <li><a href="/contact-us">Contact us</a></li>
            </ul>
        </div><!-- end of top-nav -->
        <div class="clear"></div>
</div>
<div id="main-nav" class="grid_16">
	 <div class="main-menu">
		 <div id="ddtopmenubar" class="mattblackmenu">
		 		<ul>
		       	{foreach $menuitems as $value}
				   <li {if !$first}class="first"{assign var=first value='1'}{/if} {if $value.selected}class="active" {/if}>
				   <a title="{$value.title}" href='/{$value.url}' {$value.rel}  >{$value.title}</a></li>
				{/foreach}
				</ul> 
		</div>	
	 	<div class="submenu" sytle="display:none;" >
			{foreach $submenuitems as $subvalue}
			   <ul id="ddsubmenu{$subvalue@key}"  class="ddsubmenustyle" >
			   	{foreach $subvalue as $livalue}
			   	<li><a title="{$livalue.title}" href='/{$livalue.url}' >{$livalue.title}</a></li>
			    {/foreach}
			   </ul>
			{/foreach}
		</div>
		
		{* Insert script to initialise drop down menu script *}
		<script type="text/JavaScript">
	 	 ddlevelsmenu.setup("ddtopmenubar", "topbar") ;
	 	</script>
	</div>
</div><!-- end of main-nav -->
{/block}