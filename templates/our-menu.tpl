{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout" class="headerbg">
				{include file='desktopmenu.tpl'}
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_content1}
					  			</div>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div id="potm">
	  	<div class="container">
		  <div class="row-fluid">
		  	<div class="span8">
		  		<h3 class="potm">Product Of The Month</h3>
		  		{$listing_content2}
		  	</div>
		  	<div class="span4">
				{foreach $gallery as $item}
					<img src="{$item.gallery_link}" alt="{$item.gallery_file}">
				{/foreach}
		  	</div>
		  </div>
	  	</div>
	</div>
	<div id="potm2">
	  	<div class="container">
	  		<div class="row-fluid">
		  		<div id="potmbutton"><img src="/images/productmonth.png" alt="View product of the month" /></div>
	  		</div>
	  	</div>
	</div>
	<!-- Product List Section -->
	<div id="orangebox">
	  	<div class="container" id="menumainbox">
		  	<div class="row-fluid" id="menulist">
		  		<a data-option-value=".favourites" id="favourites1" class="button1 selected">Customer Favourites</a>
		  		<a data-option-value=".cakes" id="cakes1" class="button1">Cakes</a>
		  		<a data-option-value=".standard-cakes" id="standard-cakes1" class="button1">Standard Cakes</a>
		  		<a data-option-value=".specialty-cakes" id="specialty-cakes1" class="button1">Specialty Cakes</a>
		  		<a data-option-value=".cafe-desserts"id="cafe-desserts1" class="button1">Cafe Desserts</a>
		  		<a data-option-value=".truffles" id="truffles1" class="button1">Handmade Truffles</a>
		  		<a data-option-value=".gelato" id="gelato1" class="button1">Gelato</a>
		  		<a data-option-value=".sorbet" id="sorbet1" class="button1">Sorbet</a>
		  		<a data-option-value=".chocolate-blocks" id="chocolate-blocks1" class="button1">Chocolate Blocks</a>
		  		<a data-option-value=".savoury" id="savoury1" class="button1">Savoury</a>
		  		<a data-option-value=".drinks" id="drinks1" class="button1">Drinks</a>
		  		<a data-option-value=".whats-new"id="whats-new1" class="button1">What's New</a>
		  		<a data-option-value=".gifts" id="gifts1" class="button1">Gifts</a>
	  		</div>
	  		<div class="row-fluid" id="menulistmobile">
	  			<select id="moblist">
					<option value="">Select a category</option>
  					<option value="favourites">Customer Favourties</option>
  					<option value="cakes">Cakes</option>
  					<option value="standard-cakes">Standard Cakes</option>
  					<option value="specialty-cakes">Specialty Cakes</option>
  					<option value="cafe-desserts">Caf&#233; Desserts</option>
  					<option value="truffles">Handmade Truffles</option>
  					<option value="gelato">Gelato</option>
  					<option value="sorbet">Sorbet</option>
  					<option value="chocolate-blocks">Chocolate Blocks</option>
  					<option value="savoury">Savoury</option>
  					<option value="drinks">Drinks</option>
  					<option value="whats-new">What's New</option>
  					<option value="gifts">Gifts</option>
	  			</select>
	  		</div>
	  		
	  		<div class="row-fluid" id="menubox">
		  		<div class="row-fluid">
		  			<div class="span12">
		  				<div id="up1"><img src="/images/up.png" alt="up" /></div>
		  			</div>
		  		</div>
		  		<div class="row-fluid">
		  			<div class="span12" id="count">
		  			</div>
		  		</div>
	  			  		
		  		<div id="menuout">
		  			<div id="menucontainer">
		  				<div class="span12">
			  				<div id="portfolio-wrapper">
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/banana.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Banana</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/bloodorange.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Blood Orange</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/blueberry.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Blueberry</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>
				  				<div class="portfolio-item cakes favourites">
					  				<a href="#"><img src="/images/cake.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Chocolate Delight</a></div>
					  					<div class="mitemcat">Cakes</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item drinks favourites">
					  				<a href="#"><img src="/images/chocmilkshake.png" alt="" /></a>
					  				<div class="tags">
						  				<div title="New" class="new2"></div>
						  				<div title="Customer Favourite" class="fav2"></div>
					  				</div>			  				
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Chocolate Milkshake</a></div>
					  					<div class="mitemcat">Drinks</div>
					  				</div>
				  				</div>		
				  				<div class="portfolio-item chocolate-blocks favourites">
					  				<a href="#"><img src="/images/chocblocks.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Dark Chocolate</a></div>
					  					<div class="mitemcat">Chocolate Blocks</div>
					  				</div>
				  				</div>		
				  				<div class="portfolio-item whats-new favourites">
					  				<a href="#"><img src="/images/chocolate.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">80% Dark Chocolate</a></div>
					  					<div class="mitemcat">What's New</div>
					  				</div>
				  				</div>		
				  				<div class="portfolio-item gelato favourites">
					  				<a href="#"><img src="/images/chocomint.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Choc Mint</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>		
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/coconut.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Coconut</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>				  						  						  						  	<div class="portfolio-item cafe-desserts favourites">
					  				<a href="#"><img src="/images/fondue.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Fondue</a></div>
					  					<div class="mitemcat">Cafe Desserts</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/gelatostrawberry.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Strawberry and Choc</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item drinks favourites">
					  				<a href="#"><img src="/images/hotchilichoco.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Hot Chilli Chocolate</a></div>
					  					<div class="mitemcat">Drinks</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/lemon.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Lemon Lime Citrus</div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item gifts favourites">
					  				<a href="#"><img src="/images/minttears.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Mint Chocolate Tear Drops</a></div>
					  					<div class="mitemcat">Gifts</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item truffles favourites">
					  				<a href="#"><img src="/images/truffles.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Choc Macadamias</a></div>
					  					<div class="mitemcat">Handmade Truffles</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item gelato favourites">
					  				<a href="#"><img src="/images/pistachio.png" alt="" /></a>
					  				<div class="tags">
						  				<div title="Award Winning" class="award2"></div>
					  				</div>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Pistachio</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>	
				  				<div class="portfolio-item gelato">
					  				<a href="#"><img src="/images/pinkgrapefruit.png" alt="" /></a>
					  				<div class="mitemtop">
					  					<div class="mitemtitle"><a href="#">Pink Grapefruit</a></div>
					  					<div class="mitemcat">Gelato</div>
					  				</div>
				  				</div>			  						  						  						  						  						  						  								  				
			  				</div>
		  				</div>
		  			</div><!--newscontainer-->
		  		</div><!--newsbox-->
		  		<div class="row-fluid">
		  			<div class="span12">
		  			<div id="down1"><img src="/images/down.png" alt="down" /></div>
		  			</div>
		  		</div>
	  		</div>
  		</div>
 	</div>
	  	
	<script src="/includes/js/jquery.isotope.min.js"></script>
    <script src="/includes/js/jquery.ba-bbq.min.js"></script>    
    <script src="/includes/js/jquery.tipTip.minified.js"></script>  
    <script src="/includes/js/menu.js"></script>  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
