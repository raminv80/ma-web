{block name=body}
<div class="sliderout1">
<div id="myCarousel" class="container carousel slide productslide">
  <!-- Carousel items -->
  <div class="carousel-inner">
  {assign var=val value=0}
    {foreach $slider as $slide name=foo_1}
	    {if $slide.slide_type_id eq "6"	 }
	     {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if} ">
				 {if $slide.slide_image neq ""}
				<img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190" }" />
				{/if}
		    </div>
	 	 {/if}
    {/foreach}
  </div>
	<div class="slidecont1 right">
		{trimchars data=$listing_short_description maxchars="150"}
	</div>

	 <ol class="carousel-indicators">
		 {assign var=val value=-1}
	    {foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "6"}
	     {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val}"   class="{if $val eq "0" }active{/if}" ></li>
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
		<div id="bulk-store"></div>
		<div id="showroom" itemscope itemtype="http://schema.org/LocalBusiness" >
			<div class="row mapbox">
				<div class="span5">
					<div class="addblock " id="showr" map="sm" >
					<span class="adbold" itemprop="name">SHOWROOM</span><br/>
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" >{$data[0].listing_short_description}</div>
					</div>

					<div class="addblock" id="bulk" map="bm" >
					<span class="adbold" itemprop="name"  >BULK STORE</span><br/>
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" >{$data[1].listing_short_description}</div>
					</div>

				</div>
				<div class="span7 map1" id="sm"  itemprop="description"  itemscope itemtype="http://schema.org/Place"  itemprop="map">
					{$data[0].listing_long_description}
				</div>
				<div class="span7 map1" id="bm"  itemprop="description"   itemscope itemtype="http://schema.org/Place" itemprop="map" >
					{$data[1].listing_long_description}
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function(){
			if(window.location.hash == '#bulk-store') {
				$('#bulk').addClass('selected');
				$('#sm').addClass('hide');
				$('.nav-collapse  > ul > li:nth-child(7)').addClass('active');
				$('.nav-collapse  > ul > li:nth-child(5').css('border-right','3px solid #E23F2E');


			}else{
				$('#showr').addClass('selected');
				$('#bm').addClass('hide');
				if(window.location.hash == '#showroom'){
					$('.nav-collapse  > ul > li:nth-child(6)').addClass('active');
					$('.nav-collapse  > ul > li:nth-child(4)').css('border-right','3px solid #E23F2E');
					}else{
					$('.topnav li a:last').attr('style','color: #E23F2E;');
				}

			}
			$('li.active').prev().css('border-right','0');


		});

		//*
		$('.addblock').click(function(){
			$('.map1').hide();
			$('#'+$(this).attr('map')).show();
			$('.addblock').removeClass('selected');
			$(this).addClass('selected');

		});
		//*/

		window.addEventListener("hashchange", locationHashChanged, false);
		function locationHashChanged() {
			$('.topnav li a:last').attr('style','color: #000;');
		    if (location.hash === "#showroom") {

		    	$('#bulk').removeClass('selected');
				$('#sm').removeClass('hide');

				 var i = $('#sm').find('iframe');
				 var is = $(i).attr('src');
				 i.delay(250).attr('src', is);
				console.log( is);
				$('.nav-collapse  > ul > li:nth-child(7)').removeClass('active');



				$('#showr').addClass('selected');
				$('#bm').addClass('hide');
		    	$('.nav-collapse  > ul > li:nth-child(6)').addClass('active');
		    	$('.nav-collapse  > ul > li:nth-child(4)').css('border-right','3px solid #E23F2E');
		    	$('li.active').prev().css('border-right','0');


			}
		    if(location.hash === "#bulk-store"){




		    	$('#showr').removeClass('selected');
				$('#bm').removeClass('hide');

				var i = $('#bm').find('iframe');
			    var is = $(i).attr('src');
				i.delay(250).attr('src', is);
				console.log( is);
		    	$('.nav-collapse  > ul > li:nth-child(6)').removeClass('active');

		    	$('#bulk').addClass('selected');
				$('#sm').addClass('hide');
				$('.nav-collapse  > ul > li:nth-child(7)').addClass('active');
				$('.nav-collapse  > ul > li:nth-child(5)').css('border-right','3px solid #E23F2E');
				$('li.active').prev().css('border-right','0');



		    }
		}



		</script>
		<style>
		.addblock.selected:hover{
			/*background:#fff ;*/
			/*color: #E23F2E;*/
		}
		.addblock{
			cursor:pointer;
		}

		</style>

		{include file='bottom-boxes.tpl'}
</div>



{/block}