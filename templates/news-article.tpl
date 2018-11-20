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
        {if $data.3 || $data.5}
			<div class="col-sm-4 col-sm-offset-1" id="contright">
				{if $data.5}
                <h3>Videos</h3>
				<div class="videobox">
					{$cnt = 0}
                    {foreach $data.5 as $vid}
                    <div class="row vidb{if $cnt gt 3} video-hidden{/if}" style="{if $cnt gt 3}display:none;{/if}">
						<div class="col-xs-5 col-sm-5">
							<a href="{$vid.listing_content1}" target="_blank" title="Click to view video">
							<img src="{$vid.listing_image}" class="img-responsive" alt="{$vid.listing_name} thumbnail" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="{$vid.listing_content1}" target="_blank" title="Click to view video">
							<h5>{$vid.listing_name}</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>{$vid.news_start_date|date_format:"%e %B %Y"}</div>
						</div>
					</div>
                    {$cnt = $cnt+1}
                    {/foreach}
                    {if $cnt gt 3}
					<div class="row vidb">
						<div class="col-sm-12">
							<a href="javascript:void(0)" onclick="$('.video-hidden').show('slow');$(this).hide();" class="red">See all ></a>
						</div>
					</div>
                    {/if}
				</div>
                {/if}
				{if $data.3}
				<h3>Newsletters</h3>
				<div class="newslbox">
                    {$cnt = 0}
					{foreach $data.3 as $nws}
                    <div class="row newsl{if $cnt gt 3} newsletter-hidden{/if}" style="{if $cnt gt 3}display:none;{/if}">
						<div class="col-sm-12">
							<a href="/{$listing_url}/{$nws.listing_url}" title="Click to read more"><h5>{$nws.listing_name}</h5></a>
							<div>{$nws.news_start_date|date_format:"%e %B %Y"}</div>
						</div>
					</div>
                    {$cnt = $cnt+1}
                    {/foreach}
                    {if $cnt gt 3}
                    <div class="row vidb">
                      <div class="col-sm-12">
                        <a href="javascript:void(0)" onclick="$('.newsletter-hidden').show('slow');$(this).hide();" class="red">See all ></a>
                      </div>
                    </div>
                    {/if}
				</div>
                {/if}
			</div>
      {/if}
		</div>
	</div>
</div>
{/block}
{block name=tail}
{printfile file='/node_modules/flexslider/jquery.flexslider-min.js' type='script'}
<script type="text/javascript">
$(window).on('load', function() {

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
