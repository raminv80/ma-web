{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="heroimg">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 hidden-xs">
				<img src="/images/bupalogo.jpg" class="img-responsive" alt="Bupa">
				<div><h1><span class="highlight">Exclusive Bupa</span><br>
				MEMBER OFFER</h1>
				2 year MedicAlert<sup>&reg;</sup> membership + medical ID<br>
				of your choice* for just $125<br>
				<span class="highlight">SAVE UP TO 26%</span>
				</div>
				<a href="/benefits-of-membership" title="Benefits of membership" class="btn btn-red">View benefits</a>
			</div>
		</div>
	</div>
</div>
<div id="offer" class="visible-xs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<img src="/images/bupalogo.jpg" class="img-responsive" alt="Bupa">
				<div><h1><span class="highlight">Exclusive Bupa</span><br>
				Member Offer</h1>
				2 year MedicAlert<sup>&reg;</sup> membership + medical ID
				of your choice* for just $125<br>
				<span class="highlight">SAVE UP TO 26%</span>
				</div>
				<a href="/benefits-of-membership" title="Benefits of membership" class="btn btn-red">View benefits</a>
			</div>
		</div>
	</div>
</div>
<div id="benefits">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<p>If you're living with a medical condition, have an allergy, take regular medications or have an implant/device,
a MedicAlert membership could mean the difference between life and death.</p>
				<p>As Australia's most trusted and recognised provider of medical IDs, MedicAlert Foundation can provide you with full support in a medical emergency. From the moment you join MedicAlert, you'll gain access to a range of exclusive benefits.</p>
			</div>
		</div>
		<div class="row" id="uspbox">
			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit1.png" alt="" class="img-responsive">
				<h5>Protection for 12 months</h5>
			</div>
			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit2.png" alt="" class="img-responsive">
				<h5>24/7 emergency service access to your medical information</h5>
			</div>
			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit3.png" alt="" class="img-responsive">
				<h5>Exclusive member only offers</h5>
			</div>

			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit4.png" alt="" class="img-responsive">
				<h5>Unlimited wallet and fridge cards listing your details</h5>
			</div>
			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit5.png" alt="" class="img-responsive">
				<h5>Secure online access to your electronic health record</h5>
			</div>
			<div class="col-xs-6 col-md-4 text-center usp">
				<img src="/images/benefit6.png" alt="" class="img-responsive">
				<h5>Support from our Membership Services team</h5>
			</div>
		</div>
	</div>
</div>

<div id="redeem">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center" id="joinhead">
				A special offer for Bupa members:<br>
				<span class="highlight">2 year MedicAlert<sup>&reg;</sup> membership + medical ID of your choice*</span><br>
				<span>for just $125</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="onethird text-left" id="first">
					Redeem this<br> special offer in<br> 2 easy steps:
				</div>
				<div class="onethird text-center" id="second">
					<img src="/images/join1.png" alt="" class="img-responsive">
					<div><div class="num">1</div> <div class="text">Choose your medical ID from the range below</div></div>
				</div>
				<div class="onethird text-center" id="third">
					<img src="/images/join2.png" alt="" class="img-responsive">
					<div><div class="num">2</div> <div class="text">Fill out your details to complete registration</div></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="products">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>Select your medical ID</h2>
				Choose from our range of genuine MedicAlert IDs
			</div>
      </div>
      <div class="row" id="products-wrapper">
          {if $products}
          {foreach $products as $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
          {/if}
      </div>
      <div class="row">
			<div class="col-sm-12 text-center small">
				*Selected products only. Price includes standard postage and handling and a 12-month standard Australia MedicAlert Foundation product warranty.
			</div>
			<div class="col-sm-12 text-center" id="moreprods">
				<p>Can't find a product you like? See the <a href="/products?setdc=BUPA17" style="color:#e02445;" title="View our range">full product range here</a> or call <a href="tel:{$COMPANY.toll_free}" title="Give us a call" class="phone">{$COMPANY.toll_free}</a>.</p>
			</div>
		</div>
	</div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script src="/includes/js/isotope.pkgd.min.js"></script>
<script src="/includes/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">

  $(document).ready(function() {
    
   var $grid = $("#products-wrapper").isotope({
  	  itemSelector: '.prodout',
  	  layoutMode: 'fitRows'
     });
    
    $grid.on( 'arrangeComplete', function( event, filteredItems ) {
      $(window).trigger("scroll");
    });
    
 	$('img.prodimg').lazyload({
 		effect: "fadeIn",
       	failure_limit: Math.max($('img.prodimg').length - 1, 0),
       	event: "scroll click"
 	});
 	
     	
 });

  
//REFRESH ISOTOPE WHEN SCROLLING UP/DOWN
  var minLastView = $(document).height();
  var maxLastView = 0;
  $(window).scroll(function() {
    var curHeight = $(window).scrollTop() + $(window).height();
    if(curHeight < minLastView && Math.abs(curHeight - minLastView) > 500){
      minLastView = curHeight;
      $("#products-wrapper").isotope('layout');
    }
    if(curHeight > maxLastView && Math.abs(curHeight - maxLastView) > 500){
      maxLastView = curHeight;
      $("#products-wrapper").isotope('layout');
    }
 });
</script>
{/block}
