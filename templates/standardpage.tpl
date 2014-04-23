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

 		{if $listing_content2}
		<div class="row">
			<div class="col-sm-12">				
				<h2 class="title">Watch our video</h2>
				<iframe id="video" width="507" height="380" src="{$listing_content2}" frameborder="0" allowfullscreen></iframe>
			</div>
		</div> 
		{/if}
	</div>
</div>

{/block}
