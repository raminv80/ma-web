{block name=body}
{* Define the function *} 
{function name=render_products level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=it}
		<div class="col-xs-3 image">
			<img src="{$it.gallery.0.gallery_link}" alt="{$g.gallery_alt_tag}" title="{$g.gallery_title}" style="width:90px; height:90px;"/>
			<span class="caption">{$g.gallery_caption}</span>
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$it.product_name}</div>
		
		{if $it.attribute} 
			{if $it.product_specialprice eq '0.00'}
				{assign var='lowest' value=$it.product_price}
				{foreach $it.attribute key=katt item=itatt name=attr}
					{foreach $itatt.attr_value key=kattval item=itattval name=attr_val}
						{if $smarty.foreach.attr_val.first}
							{assign var='lowest_attr' value=$itattval.attr_value_price}
						{else}
							{if $itattval.attr_value_price lt $lowest_attr}
								{assign var='lowest_attr' value=$itattval.attr_value_price}
							{/if}
						{/if}
					{/foreach} 
					{assign var="lowest" value=$lowest_attr+$lowest}
				{/foreach} 
				<div class="col-xs-12">from ${$lowest|number_format:2:'.':','}</div>
			{else}
				{assign var='lowest' value=$it.product_specialprice}
				{foreach $it.attribute key=katt item=itatt name=attr}
					{foreach $itatt.attr_value key=kattval item=itattval name=attr_val}
						{if $smarty.foreach.attr_val.first}
							{assign var='lowest_attr' value=$itattval.attr_value_specialprice}
						{else}
							{if $itattval.attr_value_specialprice lt $lowest_attr}
								{assign var='lowest_attr' value=$itattval.attr_value_specialprice}
							{/if}
						{/if}
					{/foreach} 
					{assign var="lowest" value=$lowest_attr+$lowest}
				{/foreach} 
				<div class="col-xs-12" style="color:#FF4822">Special Price from ${$lowest|number_format:2:'.':','}</div>
			{/if} 
		{else}
			{if $it.product_specialprice eq '0.00'}
				<div class="col-xs-12">${$it.product_price|number_format:2:'.':','}</div>
			{else}
				<div class="col-xs-12" style="text-decoration: line-through;">${$it.product_price|number_format:2:'.':','}</div>
				<div class="col-xs-12" style="color:#FF4822">Special Price: ${$it.product_specialprice|number_format:2:'.':','}</div>
			{/if}
		{/if}
		<div class="col-xs-12">{$it.product_description}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$it.product_url}-{$it.product_id}" class="btn btn-info">View product</a></div>
		</div>
	{/foreach} 
	</div>
{/function}

{function name=render_categories level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=cat}
		<div class="col-xs-3 image">
			<img src="{$cat.listing_image}" alt="{$cat.listing_title}" title="{$cat.listing_title}" style="width:90px; height:90px;"/>
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$cat.listing_title}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$cat.listing_url}" class="btn btn-default">View Category</a></div>
		</div>
		{call name=render_products items=$cat.products parentUrl=$parentUrl+"/"+$cat.listing_url}
	{/foreach} 
	</div>
{/function}

	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div class="container">
		<div class="row">
			<div class="col-xs-12"><h3>Products in {$listing_title}</h3></div>
			{call name=render_products items=$data.products parentUrl="./{$listing_url}/"}
			<div class="row" style="height:50px;"></div>
			{call name=render_categories items=$data.listings parentUrl="./{$listing_url}/"}
		</div>
	</div>
{/block}
