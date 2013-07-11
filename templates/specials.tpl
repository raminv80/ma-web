{block name=body}
<div class="sliderout1">
<div id="myCarousel" class="container carousel slide productslide">
  <!-- Carousel items -->
  <div class="carousel-inner">
  	{assign var=val value=0}
    {foreach $slider as $slide name=foo_1}
	    {if $slide.slide_type_id eq "4"	and $slide.slide_image neq "" }
	    {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if} {$val} ">

				<img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190"} " />

		    </div>
	 	 {/if}
    {/foreach}
  </div>
	<div class="slidecont1 right">
		{$listing_short_description}
	</div>
	 <ol class="carousel-indicators">
			{assign var=val value=0}
	    {foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "4"}
	     {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val}" class="{if $val eq "1" }active{/if}" ></li>
	     {/if}
	     {/foreach}
	 </ol>
</div>
</div>

<div class="container">
		<div class="row">
			<div class="span12">
				<div class="breadcrumbs">
					<a href="/">Home</a> > <a href="/specials">Specials</a>
				</div>
				<div class="pagetitle">Specials</div>

				{$listing_long_description}
				<div class="specials"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >


					{foreach $data as $item}
					<div class="media">
							{if $item.listing_image neq "" }
				              <a class="pull-left" href="javascript:void(0);">
				                <img class="media-object" alt="video" style="width: 148px; height: 148px;" src="{$item.listing_image}"  title="{$item.listing_name}" >
				              </a>
				            {else}
				            	<a class="pull-left" href="javascript:void(0);">
				                <img itemprop="image"  class="media-object" alt="video" title="{$item.listing_name}" style="width: 148px; height: 148px;" src="/images/dollar.png">
				              </a>
				            {/if}
				              <div class="media-body">
						   <div class="specialtitle"  itemprop="name" >{$item.listing_name}</div>
					          <p temprop="description" >{$item.listing_short_description}</p>
				              </div>
			        </div>
			        {/foreach}
				</div>


			</div>
	 </div>
	 <p><br/>*All products are subject to final sale. Offers are subject to change and available to personal shoppers only. (sqm = Square Metre)</p>
		{include file='bottom-boxes.tpl'}
</div>

{/block}