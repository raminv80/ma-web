{block name=body}
<div id="banner">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_title}</h1>
				<a href="/benefits-of-membership" title="Learn more about MedicAlert membership" class="btn btn-red">Learn more</a>
			</div>
		</div>
	</div>
</div>

<div id="redbar">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				Our members wear the genuine MedicAlert ID
			</div>
			<div class="col-sm-3 col-md-3 col-sm-offset-0 col-md-offset-0 hidden-xs hidden-sm">
				<img src="/images/homeredbracelet.png" alt="Bracelet" class="img-responsive" />
			</div>
			<div class="col-sm-12 col-md-2">
				<a href="/products" class="btn btn-transp">Shop the range</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm text-center">
		<img src="/images/mob-home-bracelet.png" alt="Bracelet" class="img-responsive" />
	</div>
</div>

<div id="whiteblock1">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				{$listing_content1}
			</div>
		</div>
	</div>
</div>

<div id="greyblock1">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<img src="/images/homemembers.png" alt="Members" class="img-responsive" />
				<p class="bigtxt">MedicAlert membership is just {$CONFIG_VARS.membership_fee} a year</p>

				<p>MedicAlert membership gives you access to a range of exclusive benefits:</p>
				<br />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit1.png" alt="Protection for 12 months" class="img-responsive" />
				<p>Protection for 12 months</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit2.png" alt="24/7 emergency service access to your medical information" class="img-responsive" />
				<p>24/7 emergency service access to your medical information</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit3.png" alt="Exclusive member only offers" class="img-responsive" />
				<p>Exclusive member only offers</p>
			</div>
			<div class="col-sm-4 text-center benefits hidden-xs">
				<img src="/images/benefit4.png" alt="Unlimited wallet and fridge cards listing your details" class="img-responsive" />
				<p>Unlimited wallet and fridge cards listing your details</p>
			</div>
			<div class="col-sm-4 text-center benefits hidden-xs">
				<img src="/images/benefit5.png" alt="Secure online access to your electronic health record " class="img-responsive" />
				<p>Secure online access to your electronic health record </p>
			</div>
			<div class="col-sm-4 text-center benefits hidden-xs">
				<img src="/images/benefit6.png" alt="Support from our Membership Services team" class="img-responsive" />
				<p>Support from our Membership Services team</p>
			</div>

			<div class="col-sm-12 text-center">
				<a href="/benefits-of-membership" title="Learn more about MedicAlert membership" class="btn btn-red">Learn more</a>
			</div>
		</div>
	</div>
</div>

<div id="whiteblock2">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h2>Find a product that suits your style</h2>
      </div>
    </div>
    <div class="row">
      {if $collections}
      <div id="categorycontainer">
        {assign var='count' value=0}
        {foreach $collections as $item}
          {if $count lt 5}
            {if $count eq 0}<div class="col-sm-6">{elseif $count eq 1 || $count eq 3}<div class="col-sm-3">{/if}
              <div class="prodcat">
                <a href="/products/{$item.listing_url}" title="View {$item.value} Products">
                  <div class="imgcont">
                  <img src="{if $item.listing_image neq ''}{$item.listing_image}?width=800&height={if $count eq 0}440{else}360{/if}&crop=1{else}/images/no-image-available.png{/if}" alt="{$item.value}" title="{$item.value}" class="img-responsive">
                  </div>
                  <div class="prodcat-txt prodlt">
                    Shop <span>{$item.value}</span>
                  </div>
                </a>
              </div>
            {if $count eq 0 || $count eq 2 || $count eq 4}</div>{/if}
          {else}
            {break}
          {/if}
        {assign var='count' value=$count+1}
        {/foreach}
      </div>
      {/if}
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <a href="/products" class="btn btn-red">View all products</a>
      </div>
    </div>
  </div>
</div>


{if $testimonials}
  <div id="greyblock2">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <h2>MedicAlert speaks for its members in an emergency</h2>

          <div id="testimonials">
            <div id="testim1" class="flexslider">
              <ul class="slides">
                {foreach $testimonials as $t}
                <li>
                  <div>
                    <img src="/images/homequote.png" alt="" />
                  </div>
                  {$t.listing_content2|truncate:220:'...'} <a href="/testimonials/'|cat:$t.listing_url|cat:'">Read more</a>
                </li>
                {/foreach}
              </ul>
            </div>
            <div id="testim2" class="flexslider">
              <ul class="slides">
                {foreach $testimonials as $t}
                <li>
                  <img src="{if $t.listing_image}{$t.listing_image}{else}/images/testimonial-noimg.png{/if}?width=96&height=96&crop=1" alt="{$t.listing_name} photo" title="{$t.listing_name}">
                  <div>
                    {$t.listing_name}
                    <br />
                    {$t.listing_content1}
                  </div>
                </li>
                {/foreach}
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  {/if}
{/block}

{block name=tail}
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
$(window).load(function() {
  $('#testim2').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    itemMargin: 5,
    asNavFor: '#testim1',
    minItems: 3,
    maxItems: 3
  });

  $('#testim1').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: true,
    sync: "#testim2"
  });
});
</script>

{/block}