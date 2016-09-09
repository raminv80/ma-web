{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_title}</h1>
				<div class="about-text">
				{$listing_content1}
				</div>
			</div>
		</div>
		<div class="row visible-xs">
			<div class="col-xs-12 text-center">
				<a href="#" id="readmore">Read more <img src="/images/down-arrow.png" alt="Down" /></a>
			</div>
		</div>
	</div>
</div>

<div id="our-history">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>Our history</h3>
      </div>
      {if $listing_image2}
      <div class="col-sm-4">
        <img src="{$listing_image2}" alt="Our history image" class="img-responsive">
      </div>
      {/if}
      <div class="col-sm-{if $listing_image2}8{else}12{/if}">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>

<div id="directors-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h3>Board of Directors</h3>
        <p>Our national Board of Directors volunteer their time to ensure we can provide the best possible service for our members</p>
      </div>
    </div>
    <div class="row" id="dirout">
        {foreach $additionals as $ad}
          <div class="col-sm-4 col-md-3 directors text-center">
            <img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-director.png{/if}" alt="{$ad.additional_name} photo" class="img-responsive">
            <div class="date">{$ad.additional_description}</div>
            <div class="h3">{$ad.additional_name}</div>
            {$ad.additional_content1}
          </div>
        {/foreach}
    </div>
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#readmore").click(function(){
		$(this).hide();
		$("#pagehead .about-text").css("height","auto");
	});
});
</script>
{/block}
