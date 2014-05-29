{block name=head}
<style type="text/css">
#headbanner {
    background: url("{if $listing_image}{$listing_image}{else}/images/header3.jpg{/if}") no-repeat scroll center top / cover rgba(0, 0, 0, 0);
    height: 345px;
    margin-bottom: 15px;
}
</style>
{/block}

{block name=body}
<script type="text/javascript">
{$ga_ecommerce}
</script>

<div id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
		
		
		<div class="row">
			<div id="maintext" class="col-sm-12">
				<h2 class="title">{$listing_name}</h2>
		  	Thank you. Your order <span class="bold">{$orderNumber}</span> is being processed.<br />
				Please check your email for confirmation and receipt.
				<br /><br />
			</div>
		</div>


				
	</div>
</div>

{/block}
