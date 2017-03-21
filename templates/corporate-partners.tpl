{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-8 col-md-offset-2 text-center">
				<h1>{$listing_title}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="partners-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-md-offset-2">
          {foreach $additionals as $ad}
            <div class="partners row">
	            <div class="col-sm-3">
		            <img src="{if $ad.additional_image}{$ad.additional_image}?width=250&height=150&crop=1{else}/images/no-image-available.jpg?width=250&height=150&crop=1{/if}" alt="{$ad.additional_name}" class="img-responsive" />
	            </div>
	            <div class="col-sm-9">
		            <h3>{$ad.additional_name}</h3>
					{if $ad.additional_description}<div class="website"><a href="{$ad.additional_description}" title="Click to go to website" target="_blank">Website</a></div>{/if}
					<div class="description">{$ad.additional_content1}</div>
	            </div>
            </div>
          {/foreach}
      </div>
      <div class="col-sm-12 col-md-8 col-md-offset-2"><br>{$listing_content2}</div>
    </div>
    
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript">


</script>
{/block}
