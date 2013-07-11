{block name=body}
<div class="sliderout">
<div id="myCarousel" class="container carousel slide home">
  <!-- Carousel items -->
  <div class="carousel-inner">
  {assign var=val value=0}
    {foreach $slider as $slide name=foo}
    {if $slide.slide_type_id eq "1"}
     {assign var=val value=$val+1}
    <div class=" {if $val eq "1" }active{/if} {$val}  item">
	    <img class="slidebg" src="/images/homeslide.png" alt="slide {$smarty.foreach.foo.iteration}" />
		{if $slide.slide_special neq "0"}
		<img class="slideimg" src="/images/specialscircle2.png" alt="slideimg" />
		{/if}
	    {if $slide.slide_image neq ""}
		<img class="slideimg center" src="{$slide.slide_image}" alt="slideimg" />
		{/if}


		<div class="slidecont">
			{if $slide.slide_image neq ""  }
				{trimchars data=$slide.slide_text maxchars="70" }
			{else}
				{if $slide.slide_special neq "0"}
				 {trimchars data=$slide.slide_text maxchars="130" }
				{else}
				{trimchars data=$slide.slide_text maxchars="190" }
				{/if}
			{/if}
		</div>
		<div class="hpmore" style="position: absolute; bottom: 6px;">
		  {if $slide.slide_link neq ""}
				<a href="{$slide.slide_link}" style="color:white"><img src="/images/magg.png" alt="Mc leay" title="Mc leay" />view more</a>
		 {/if}
		</div>
    </div>
    {/if}
    {/foreach}

  </div>

	<ol class="carousel-indicators">
	<!--  {counter start=-1} -->
	    {foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "1"}
	     <li data-target="#myCarousel" data-slide-to="{counter}"  {if $smarty.foreach.foo2.iteration eq 1}class="active"{/if} ></li>
	     {/if}
	     {/foreach}
	 </ol>
</div>
</div>

        <div class="container">
				<div class="row">
					<div class="span12">
						{$listing_long_description}
					</div>
				</div>
				{include file='bottom-boxes.tpl'}
		</div>
{/block}