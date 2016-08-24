{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_title}</h1>
				<p>{$listing_content1}</p>
			</div>
		</div>
	</div>
</div>

<div id="our-history">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="h2 text-center">Our history</div>
      </div>
      {if $listing_image2}
      <div class="col-md-4">
        <img src="{$listing_image2}" alt="Our history image">
      </div>
      {/if}
      <div class="col-md-{if $listing_image2}8{else}12{/if}">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>

<div id="directors-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="h2 text-center">Board of Directors</div>
        <p>Our national Board of Directors ...</p>
      </div>
    </div>
    <div class="row">
        {foreach $additionals as $ad} 
          <div class="col-sm-6 col-md-4 directors">
            <img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-director.png{/if}" alt="{$ad.additional_name} photo">
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


</script>
{/block}
