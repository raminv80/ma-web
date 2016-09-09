{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_title}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="partners-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
          {foreach $additionals as $ad} 
            <div class="partners">
              <div class="h3"><img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-who-needs-icon.png{/if}" alt="{$ad.additional_name} icon">{$ad.additional_name}</div>
              <div class="website">{$ad.additional_description}</div>
              <div class="description">{$ad.additional_content1}</div>
              <br>
            </div>
          {/foreach}
      </div>
    </div>
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript">


</script>
{/block}
