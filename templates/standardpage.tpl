{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{if $listing_content3}
					{if $listing_content5}
						<a target="_blank" href="{$listing_content5}"><img src="{$listing_content3}" class="img-responsive ad-banner" alt="{$listing_title} ad-banner" /></a>
					{else}
						<img src="{$listing_content3}" class="img-responsive ad-banner" alt="{$listing_title} ad-banner" />
					{/if}
				{/if}
			</div>
		</div>
		
		<div class="row">
			<div id="maintext" class="col-sm-12">
				<h2 class="title">{$listing_name}</h2>
				{$listing_content1}
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">				
				<h2 class="title">STANDARD PAGE</h2>
			</div>
		</div> 
		

		
	</div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">

</script>
{/block}
