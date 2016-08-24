{block name=body}
<div id="pagehead">
  <div class="bannerout">
    <img src="{if $listing_parent.listing_image}{$listing_parent.listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_parent.listing_name} banner" />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
	    <a href="/{$listing_parent.listing_url}" id="backt">&lt;	Back to testimonials</a>
        <h1>Testimonials</h1>
      </div>
    </div>
  </div>
</div>

<div id="test-details">
  <div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 text-center" id="test-info">
			<img src="{if $listing_image}{$listing_image}{else}/images/testimonial-noimg.png{/if}?width=96&height=96&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
			<div class="h5">{$listing_name}</div>
			<div class="testloc">{$listing_content1}</div>
		</div>
	</div>
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1" id="test-quote">"{$listing_content2}"</div>
    </div>
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">{$listing_content3}</div>
    </div>
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