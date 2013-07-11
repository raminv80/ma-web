{block name=body}
<div class="sliderout1">
<div id="myCarousel" class="container carousel slide productslide">
  <!-- Carousel items -->
  <div class="carousel-inner">
	{assign var=val value=0}
    {foreach $slider as $slide name=foo_1}
	    {if $slide.slide_type_id eq "7"	and $slide.slide_image neq "" }
	    {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if} {$val} ">
		    {assign var=val value=$val+1}
				<img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190"} " />

		    </div>
	 	 {/if}
    {/foreach}
  </div>
	<div class="slidecont1 right">
		{$listing_short_description}
	</div>
	 <ol class="carousel-indicators">
		{assign var=val value=-1}
	    {foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "7"}
	      {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val}"  {if $val eq "0" }class="active"{/if} ></li>
	     {/if}
	     {/foreach}
	 </ol>
</div>
</div>
<div class="container">
		<div class="row">
			<div class="span12">
				<div class="breadcrumbs">
					<a href="/">Home</a> > <a href="/{$listing_url}">{$listing_name}</a>
				</div>
				<div class="pagetitle">{$listing_name}</div>
					{$listing_long_description}
			</div>
		</div>
	{include file='bottom-boxes.tpl'}
</div>
<script>
$('#about').attr('style','color: #E23F2E;')
</script>


{/block}