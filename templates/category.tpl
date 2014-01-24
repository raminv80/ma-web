{block name=body}

	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
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
			<h3>Products in {$listing_title}</h3>
			{if !$product_info} <div class="row">No products found.</div>{/if}
			{foreach $product_info as $prod }
				<div class="row"><a href="./{$listing_url}/{$prod.product_url}-{$prod.product_id}" >{$prod.product_name}</a></div>
			{/foreach}
		</div>
		
		{include file='full-product-cat.tpl'}
	</div>


{/block}
