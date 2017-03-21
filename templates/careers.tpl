{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="pagehead">
	<div class="bannerout">
      <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="{if $listing_object_id eq 10}col-md-12{else}col-md-8 col-md-offset-2{/if} text-center">
				<h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>
{if $listing_content2}
<div id="cost-grey" class="howto">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>
{/if}
<br>
{if $listing_content3}
<div>
  <div class="container">
    <br>
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content3}
      </div>
    </div>
  </div>
</div>
{/if}
<br>
{if $listing_content4}
<div class="emergency-grey">
  <div class="container">   
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content4}
      </div>
    </div>
  </div>
</div>
{/if}
{if $listing_content5}
<div>
  <div class="container">
    <br>
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content5}
      </div>
    </div>
  </div>
</div>
<br>
{/if}

<br>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">

</script>
{/block}
