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


<div id="faqs">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
      {$addcats = []}{foreach $additionals as $ad}{if !$add.additional_category|in_array:$addcats}{$addcats[] = $ad.additional_category}{/if}{/foreach}
      {foreach $addcats as $cat}
        <div class="">
          <div class="h2">{$cat}</div>
          {foreach $additionals as $ad} 
          {if $cat eq $ad.additional_category}
            <div class="qa-wrapper">
              <div class="question">{$ad.additional_name}</div>
              <div class="answer">{$ad.additional_content1}</div>
              <br>
            </div>
          {/if}
          {/foreach}
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
