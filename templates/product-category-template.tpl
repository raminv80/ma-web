{block name=body}
<link rel="stylesheet" href="/includes/js/lightbox/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="/includes/js/lightbox/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	  jQuery("a[rel^='prettyPhoto']").prettyPhoto();
  });
</script>
<div class="sliderout1" style="height: 321px;">
<div id="myCarousel" class="container carousel slide productslide">
<div id="empty_image">
  <!-- Carousel items -->
  <div class="carousel-inner ">
  {assign var=val value=0}
    {foreach $slider as $slide name=foo_1}
	    {if $slide.slide_type_id eq "2"	and  $category_name eq "carpets and flooring" and $slide.slide_image neq ""}
	     {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if}">
				<img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190" }" />
			</div>
	     {/if}
	     {if $slide.slide_type_id eq "3"	and  $category_name eq "curtains and blinds" and $slide.slide_image neq "" }
	      {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if}">
				<img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190" }" />
			 </div>
	     {/if}
    {/foreach}
 </div>
 <div class="slidecont1 ">
		{trimchars data=$category_data.category_short_description maxchars="150"}
 </div>
 <ol class="carousel-indicators">
	{assign var=val value=-1}
	{foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "2"	and  $category_name eq "carpets and flooring" and $slide.slide_image neq ""}
	      {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val}"  class="{if $val eq "0" }active{/if}" ></li>
	     {/if}
	      {if $slide.slide_type_id eq "3"	and  $category_name eq "curtains and blinds" and $slide.slide_image neq ""}
	      {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val }"  class="{if $val eq "0" }active{/if}" ></li>
	     {/if}
	     {/foreach}
	 </ol>
</div>
</div>


        <div class="container" id="top">
		<div class="row">
			<div class="span12">
				<div class="breadcrumbs">
					<a href="/">Home</a> > <a href="/..">{$category_name|replace:'and':'&'|ucfirst}</a>
				</div>
				<div class="pagetitle">{$category_name|replace:'and':'&'|ucfirst}</div>
				{$category_data.category_long_description}
			</div>
		</div>
		<div class="row-fluid prodboxes">
			<div class="span12">
				{counter start=1 skip=1 assign="count"}
				{foreach $data.categories as $item}
					{if $count == 1}<div class="row-fluid"><div class="span9 prodboxes">{/if}{counter}
							{if $item.category_published eq 1}
								<div class="span4">
									<div class="span6">
									<a href="#item-{$item.category_id}">
									<div class="prodbox">
										{if $item.category_image neq "" }<img src="{$item.category_image}" alt="{$item.category_name}" style="width: 134px;"/>{else}
																		<img src="/images/laminatethumb.jpg" alt="{$item.category_name}" style="width: 134px;"/>{/if}
										<div class="hov">{trimchars data=$item.category_short_description maxchars="150"}</div>

									</div>
									</a>
									</div>
									<div class="span6">
									<div class="prodboxt">{$item.category_name}<br />
										<a href="#item-{$item.category_id}">Read more</a>
									</div>
								</div>
							</div>
							{/if}

					{if $count == 4 or $smarty.foreach.posts.last }</div></div>{counter start=1 skip=1 assign="count"}{else}{/if}
				{/foreach}
				</div>
			</div>

		{counter start=1 skip=1 assign="count"}
		{foreach $data.categories as $item}
		<div id="item-{$item.category_id}" class="row-fluid hide productslist">
			<div class="span12">
				<div class="pagetitle">Our range of {$item.category_name}</div>
				<div class="item-desc">
					{$item.category_long_description}
				</div>
				<div class="span8 prodlist">
					{foreach $data.products as $prod}
					{if $prod.category_id eq $item.category_id}
						<div class="media" itemscope itemtype="http://schema.org/Product" >
								{if $prod.listing_image neq ''}
				              <a class="pull-left" href="#">
				                <img class="media-object" alt="video" style="width: 100px; height: 100px;" src="{$prod.listing_image}" itemprop="image" >
				              </a>
				               {/if}

				              <div class="media-body">
					             <h5 class="media-heading" itemprop="name"> {$prod.listing_name}</h5>

						        <div class="product-desc" itemprop="description" >{$prod.listing_short_description}</div>
						        <div class="hide more" id="read-more-{$count}" style="color:black;">
									{$prod.listing_long_description}
								</div>
								{if $prod.listing_long_description neq ""}<div class="readmore" href="#read-more-{$count}" onclick="$('#read-more-{$count}''">Read more</div>{/if}

								{foreach $data.galleries as $galleries name=foo3}
									{foreach $galleries as $gallery name=foo4}
								 		{if  $gallery.gallery_listing_id eq $prod.listing_id}
												{if $smarty.foreach.foo4.iteration eq 1}
												<a href="{$gallery.gallery_link}" rel="prettyPhoto[pp_{$prod.listing_id}]" >Gallery</a>
												{else}
												<a href="{$gallery.gallery_link}" class="" rel="prettyPhoto[pp_{$prod.listing_id}]" title="{$prod.listing_name}"></a>
												{/if}
										{/if}
									{/foreach}

								{/foreach}

				              </div>
		            	</div>
		            	{counter}
		            {/if}
					{/foreach}
            	</div>
            	<div class="backtop"><a href="#">Back to top</a></div>
				<div class="videosbox">
					<div class="span12">
						{if $item.category_video_1 neq "" or $item.category_video_2 neq ""}<div class="videotitle">Videos</div>{/if}
						{if $item.category_video_1 neq ""}
						<iframe width="560" height="315" src="http://www.youtube.com/embed/{$item.category_video_1}?wmode=opaque" frameborder="0" allowfullscreen></iframe>
						<div class="backtop"><a href="#top">Back to top</a></div>
						{/if}
						{if $item.category_video_2 neq ""}
						<iframe width="560" height="315" src="http://www.youtube.com/embed/{$item.category_video_2}?wmode=opaque" frameborder="0" allowfullscreen></iframe>
						<div class="backtop"><a href="#top">Back to top</a></div>
						{/if}
					</div>
				</div>
			</div>
		</div>
		{if $category_data.category_video_1 neq  ""}
		<iframe width="560" height="315" src="http://www.youtube.com/embed/{$category_data.category_video_1}?wmode=opaque" frameborder="0" allowfullscreen></iframe>
		<div class="backtop"><a href="#top">Back to top</a></div>
		{/if}

		{if $category_data.category_video_2 neq ""}
		<iframe width="560" height="315" src="http://www.youtube.com/embed/{$category_data.category_video_2}?wmode=opaque" frameborder="0" allowfullscreen></iframe>
		<div class="backtop"><a href="#top">Back to top</a></div>
		{/if}
		{/foreach}
		{include file='bottom-boxes.tpl'}
</div>
{/block}