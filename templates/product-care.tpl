{block name=body}
<div class="sliderout1">
<div id="myCarousel" class="container carousel slide productslide">
  <!-- Carousel items -->
  <div class="carousel-inner">
  	{assign var=val value=0}

    {foreach $slider as $slide name=foo_1}
	    {if $slide.slide_type_id eq "5"	 and $slide.slide_image neq ""}
	    {assign var=val value=$val+1}
		    <div class="item {if $val eq "1" }active{/if} {$val}">

				<div><img class="slideimg center" src="{$slide.slide_image}" alt="{trimchars data=$slide.slide_text maxchars="190" }" /> </div>

			  </div>
	 	 {/if}
    {/foreach}
  </div>
	<div class="slidecont1">
		{$listing_short_description}
	</div>
	 <ol class="carousel-indicators">
		{assign var=val value=-1}
	    {foreach $slider as $slide name=foo2}
	     {if $slide.slide_type_id eq "5"}
	     {assign var=val value=$val+1}
	     <li data-target="#myCarousel" data-slide-to="{$val}"   class="{if $val eq "0" }active{/if}"  ></li>
	     {/if}
	     {/foreach}
	 </ol>
</div>
</div>
 <div class="container">
		<div class="row">
			<div class="span12">
				<div class="breadcrumbs">
					<a href="/">Home</a> > <a href="/product-care">Product care</a>
				</div>
				<div class="pagetitle">Product care</div>
				{$listing_long_description}
			</div>

		</div>
		<div class="row-fluid prodboxes">
			<div class="span12">
				{counter start=1 skip=1 assign="count"}
				{foreach $data as $item}
					{if $count == 1}<div class="row-fluid"><div class="span9 prodboxes" >{/if}{counter}
								<div class="span4">
									<div class="span6">
									<a href="#item-{$item.listing_id}">
									<div class="prodbox"  itemscope itemtype="http://schema.org/Product" >
										{if $item.listing_title neq "" }<img src="{$item.listing_title}" alt="{$item.listing_name}" title="{$item.listing_name}" style="width: 134px;"/>{else}
																		<img src="/images/laminatethumb.jpg" alt="{$item.listing_name}" title="{$item.listing_name}" style="width: 134px;"/>{/if}
										<div class="hov"  itemprop="description" >{trimchars data=$item.listing_short_description maxchars="150"}</div>
									</div>
									</a>
									</div>
									<div class="span6">
									<div class="prodboxt" itemprop="name" >{$item.listing_name}<br />
										<a href="#item-{$item.listing_id}" onclick="$('#item-{$item.listing_id}').toggleClass('hide');">Read more</a>
									</div>
								</div>
					</div>
					{if $count == 4 or $smarty.foreach.posts.last }</div></div>{counter start=1 skip=1 assign="count"}{else}{/if}
				{/foreach}
				</div>
			</div>
		<div id="carpetcare" class="row-fluid hide">
			<div class="span12">
				<div class="pagetitle">Carpet care</div>
				<p>It is our aim to offer a comprehensive range of product, value for money, expert advice and quality tradesmanship.
				It is also important to us that the finished product meets with your expectations and is delivered within budget.It is our aim to offer a comprehensive range of product, value for money, expert advice and quality tradesmanship.
				It is also important to us that the finished product meets with your expectations and is delivered within budget.</p>

			</div>
		</div>
		{foreach $data as $item}
		<div id="item-{$item.listing_id}" class="row-fluid hide ">
			<div class="span12">
				<div class="pagetitle">Product care for {$item.listing_name}</div>
				{$item.listing_long_description}
			</div>
		</div>
		{/foreach}

		{include file='bottom-boxes.tpl'}
</div>
	<script type="text/javascript">
jQuery(window).load(function() {

	$('#topmenu').prepend('<a id="menu-icon"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>');
	$('.carousel').carousel();
	$('.readmore').click(function(){
		$(this).parent().find('.more').toggleClass('hide');
		if(!$(this).parent().find('.more').hasClass('hide')){
			$(this).html('Read less');
		}else{
			$(this).html('Read more');
		}
	});

	$('.prodbox').click(function(){
		var tag=$(this).parent().attr('href');
		if(!$(tag).hasClass('hide')){
			$(this).parent().parent().parent().find('.prodboxt a').html('Read more');
			$(this).find('.hov').removeClass('show');
			$(this).removeClass('redborder');
			$(tag).addClass('hide');
		}else{
			$('.productslist').addClass('hide');
			$('.prodbox').removeClass('redborder');
			$('.prodbox').find('.hov').removeClass('show');
			$('.prodboxt a').html('Read more');
			$(this).parent().parent().parent().find('.prodboxt a').html('Read less');
			$(this).find('.hov').addClass('show');
			$(this).addClass('redborder');
			$(tag).removeClass('hide');
		}
	});


	$('.prodboxt a').click(function(){
		var tag=$(this).attr('href');
		if(!$(tag).hasClass('hide')){
			$(this).html('Read more');
			$(this).parent().parent().parent().find('.hov').removeClass('show');
			$(this).parent().parent().parent().find('.prodbox').removeClass('redborder');
			$(tag).addClass('hide');
		}else{
			$('.productslist').addClass('hide');
			$('.prodbox').removeClass('redborder');
			$('.prodbox').find('.hov').removeClass('show');
			$('.prodboxt a').html('Read more');
			$(this).html('Read less');
			$(this).parent().parent().parent().find('.prodbox').addClass('redborder');
			$(this).parent().parent().parent().find('.hov').addClass('show');
			$(tag).removeClass('hide');
		}
	});

    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
	if (!agentID){

	$('.boxes a').hover(function(){
		$(this).find('.homebox').css('border','5px solid #E23F2E');
		$(this).css('color','#E23F2E');
	},function(){
		$(this).find('.homebox').css('border','5px solid #000');
		$(this).css('color','#000');
	});

	$('.prodboxes .span4').hover(function(){
		$(this).find('.prodbox').css('border','5px solid #E23F2E');
		$(this).find('.hov').show();
	},function(){
		$(this).find('.prodbox').css('border','5px solid #FFFFFF');
		$(this).find('.hov').hide();
	});
	}

	$('li.active').prev().css('border-right','0');


	/* toggle nav */
	$("#menu-icon").on("click", function(){
		$(".nav1").slideToggle();
		$(this).toggleClass("active");
	});


});

	</script>

{/block}