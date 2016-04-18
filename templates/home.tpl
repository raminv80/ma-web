{block name=head}
<style type="text/css">
#headbanner {
	background: url("{if $listing_image}{$listing_image}{else}/images/headerhome.jpg{/if}") no-repeat scroll center top / cover rgba(0, 0, 0, 0);
  height: 420px;
  margin-bottom: 30px;
}
</style>
{/block}

{block name=body}
<div id="main">
	<div id="mainin" class="container">
		<div class="row">
			<div class="col-sm-12">
			<h1>{$listing_name}</h1>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12 pagetxt">
			{$listing_content1}
			{if $listing_content2}
			<iframe class="visible-xs" id="video" width="507" height="380" src="{$listing_content2}" frameborder="0" allowfullscreen></iframe>
			{/if}
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{if $listing_content4}
					{if $listing_content5}
						<a target="_blank" href="{$listing_content5}"><img src="{$listing_content4}" class="img-responsive ad-banner" alt="Bottom ad-banner" /></a>
					{else}
						<img src="{$listing_content4}" class="img-responsive ad-banner" alt="Bottom ad-banner" />
					{/if}
				{/if}
			</div>
		</div>
	</div>
</div>
{/block}
