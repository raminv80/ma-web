{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout" class="headerbg">
				{include file='desktopmenu.tpl'}
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_content1}
					  			</div>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	{if $listing_content2 neq ""}
	<div id="orangebox">
		<div class="container">
			<div class="row-fluid orangecontent">
				{if $listing_image neq ""}
		  		<div class="span3">
		  			<img src="{$listing_image}" alt="{$listing_title}">
		  		</div>
		  		{/if}
		  		<div class="span8">
		  			{$listing_content2}
		  		</div>
	  		</div>
		</div>
	</div>
	{/if}

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
