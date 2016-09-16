{block name=body}
<div id="pagehead">
	<div class="bannerout">
      <img src="{if $listing_parent.listing_image}{$listing_parent.listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_parent.listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 breadcrumb">
				<a href="/{$listing_parent.listing_url}">< Back to {$listing_parent.listing_name|lower}</a>
			</div>
		</div>
	</div>
</div>

<div id="news">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="h3">{$listing_name}</h1>
				<p class="date">{$news_start_date|date_format:"%e %B %Y"}</p>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>
{/block}
{block name=tail}
<script type="text/javascript">
$(window).load(function() {

});
</script>
{/block}