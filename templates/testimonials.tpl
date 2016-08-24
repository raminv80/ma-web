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


<div id="testimonial-list">
  <div class="container">
    <div class="row" id="testcont">
      {assign var='cnt' value=0} {foreach $data.4 as $da} {assign var='cnt' value=$cnt+1}
      <div class="col-sm-6 col-md-4 testresout" data-sort="{$cnt}" data-{$da.listing_schedule_start_date|date_format:"%B-%Y"}="1" data-all="1" {foreach $da.linkedcats as $cat}data-{$cat.news_category_value}="1"{/foreach}>
        <div class="testres text-center">
          <a href="{$listing_url}/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/testimonial-noimg.png{/if}?width=96&height=96&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
          </a>
          <div class="testrestext">
            <div class="h5">{$da.listing_name}</div>
            <div class="testloc">{$da.listing_content1}</div>
            <div class="testtext">{$da.listing_content2|truncate:90:"..."}</div>
            <a href="{$listing_url}/{$da.listing_url}" class="readart"> Read my story ></a>
          </div>
        </div>
      </div>
      {/foreach}
    </div>
    <div class="row" id="hidden-newscont" style="display: none;"></div>

    {if $cnt > 6}
    <div class="row" id="seemore">
      <div class="col-sm-12 text-center" id="showmore">
        Show more
          <img src="/images/down-arrow.png" alt="">
      </div>
    </div>
    {/if}
  </div>
</div>

<div id="testimonial-share">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>If you have your own MedicAlert story to tell, we’d love to hear from you. </h3>

				<a href="#" class="btn btn-red">Share your story</a>
			</div>
		</div>
	</div>
</div>

{/block}

{block name=tail}
<script type="text/javascript">
$(document).ready(function(){
	$("#showmore").click(function(){
		$(this).hide();
		$("#testimonial-list #testcont .testresout").css("display","block");
	});
});
</script>
{/block}
