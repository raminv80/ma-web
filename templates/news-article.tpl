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
			<div class="col-sm-7">
				<h1 class="h3">{$listing_name}</h1>
				<p class="date">{$news_start_date|date_format:"%e %B %Y"}</p>

				<div id="newsimages">
					<div class="flexslider">
					  <ul class="slides">
					  	{foreach $gallery as $gal}
						<li>
							<div>
								<img src="{$gal.gallery_link}" alt="{$gal.gallery_alt_tag}" />
							</div>
							<div class="caption">{$gal.gallery_caption}</div>
						</li>
						{/foreach}
					  </ul>
					</div>
				</div>

				{$listing_content2}
			</div>

			<div class="col-sm-4 col-sm-offset-1" id="contright">
				<h3>Videos</h3>

				<div class="videobox">
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-1.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-2.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-3.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-sm-12">
							<a href="#" class="red">See all ></a>
						</div>
					</div>
				</div>

				<h3>Newsletters</h3>
				<div class="newslbox">
					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#"><h5>Newsletter name</h5></a>
							<div>14 August 2016</div>
						</div>
					</div>

					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#"><h5>Newsletter name</h5></a>
							<div>14 July 2016</div>
						</div>
					</div>

					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#" class="red">See all ></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}
{block name=tail}
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
$(window).load(function() {

  if($(window).width() < 768){
	  $('.flexslider').flexslider({
	    animation: "slide",
	    controlNav: true,
	    directionNav: false,
	    animationLoop: false,
	    slideshow: true
	  });
  }
  else{
	  $('.flexslider').flexslider({
	    animation: "slide",
	    controlNav: false,
	    animationLoop: false,
	    slideshow: true,
	  });
  }

});
</script>

{/block}