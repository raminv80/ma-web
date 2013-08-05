<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="{$listing_meta_description}" />
	<meta name="keywords" content="{$listing_meta_words}" />
	<meta http-equiv="kontent-type" content="text/html;charset=UTF-8" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="Them Advertising" />
	{if $staging}
	<meta name="robots" content="noindex,nofollow" />
	{else}
	<meta name="robots" content="index,follow" />
	{/if}
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3, minimum-scale=1, user-scalable=yes">
	<title>{$category_name|replace:'and':'&'|ucfirst}  {$product_name|ucfirst} {$listing_seo_title}</title>
	<!-- <link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon"> -->

	<!-- Bootstrap -->
    <link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/includes/css/custom.css" rel="stylesheet" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
    
    <script src="/includes/js/jquery-1.9.1.min.js"></script>
    <script src="/includes/js/bootstrap.min.js"></script>
    <script src="/includes/js/custom.js"></script>

	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({ publisher: "ur-a4a1aae0-5741-1e4a-2a36-dd37824dcca", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>

</head>
<body>
	<div id="top"></div>
	<div id="top-menu" class="row-fluid">
 		<div id="menu-icon"><span></span><span></span><span></span></div>
			<ul id="menu-top">
				<li class="current"><a title="Our Menu" href="/our-menu">Our Menu</a>
					<ul>
 					<li><a title="" href="#">Customer Favourties</a></li>
 					<li><a title="" href="#">Cakes</a></li>
 					<!-- <li><a title="" href="#">Standard Cakes</a></li>
 					<li><a title="" href="#">Specialty Cakes</a></li>
 					<li><a title="" href="#">Caf&#233; Desserts</a></li>
 					<li><a title="" href="#">Handmade Truffles</a></li>
 					<li><a title="" href="#">Gelato</a></li>
 					<li><a title="" href="#">Sorbet</a></li>
 					<li><a title="" href="#">Chocolate Blocks</a></li>
 					<li><a title="" href="#">Savoury</a></li>
 					<li><a title="" href="#">Drinks</a></li>
 					<li><a title="" href="#">What's New</a></li>
 					<li><a title="" href="#">Gifts</a></li> -->
					</ul>
				</li>
				<li><a title="Our Locations" href="/locations">Our Locations</a>
					<ul>
						<li><a title="All" href="/locations#All">All</a></li>
 						<li><a title="NSW" href="/locations#NSW">NSW</a></li>
 						<li><a title="NT" href="/locations#NT">NT</a></li>
 						<li><a title="SA" href="/locations#SA">SA</a></li>
 						<li><a title="TAS" href="/locations#TAS">TAS</a></li>
 						<li><a title="QLD" href="/locations#QLD">QLD</a></li>		  						
 						<li><a title="VIC" href="/locations#VIC">VIC</a></li>
 						<li><a title="WA" href="/locations#WA">WA</a></li>
					</ul>		  				
				</li>
				<li><a title="Community" href="/community">Community</a>
					<ul>
						<li><a title="VIP Customer Program" href="/community/vip-customer-program">VIP Customer Program</a></li>
						<li><a title="News &amp; Media" href="/community/news">News &amp; Media</a></li>
						<li><a title="Careers" href="/community/careers">Careers</a></li>
					</ul>
				</li>
				<li><a title="Franchise Opportunities" href="/franchise-opportunities">Franchise Opportunities</a>
					<ul>
						<li><a title="Master Franchise Opportunities" href="/franchise-opportunities/master-franchise-opportunities">Master Franchise Opportunities</a></li>
						<li><a title="Becoming a Franchisee" href="/franchise-opportunities/becoming-a-franchisee">Becoming a Franchisee</a></li>
						<li><a title="FAQs" href="/franchise-opportunities/faqs">FAQs</a></li>
						
					</ul>
				</li>
				<li><a title="our-story" href="/our-story">Our Story</a></li>
			</ul>
	</div>
	<div id="headout2">
	  	<div class="container">
	  		<div class="row-fluid">
	  			<div id="logo"><a title="Cocolat" href=""><img src="/images/logo.png" alt="Cocolat Logo" /></a></div>

	  			<div id="menu1">
	  				<div id="find"><a title="Our Locations" href="/locations">Find your Nearest Location</a></div>
	  				<div id="search"><input id="searchbox" type="text" value="Search..." /></div>
	  				<div id="social">
	  					<a title="Facebook" href="#"><img src="images/facebook.png" alt="Facebook" /></a>
	  					<a title="Twitter" href="#"><img src="images/twitter.png" alt="Twitter" /></a>
	  					<a title="Instagram" href="#"><img src="images/instagram.png" alt="Instagram" /></a>
	  					<a title="4Square" href="#"><img src="images/4square.png" alt="4Square" /></a>
	  				</div>
	  				<!-- <div id="franchise">
	  					<a title="Franchise Login Section" href="/login"><img src="images/franchise.png" alt="Franchise" /></a>
	  				</div> -->
	  			</div>

	  			<div id="menu2">
		  			<ul>
		  				<li><a title="Our Menu" href="our-menu">Our Menu</a>
		  					<ul>
			  					<li><a title="" href="#">Customer Favourties</a></li>
			  					<li><a title="" href="#">Cakes</a></li>
			  					<li><a title="" href="#">Standard Cakes</a></li>
			  					<!-- <li><a title="" href="#">Specialty Cakes</a></li>
			  					<li><a title="" href="#">Caf&#233; Desserts</a></li>
			  					<li><a title="" href="#">Handmade Truffles</a></li>
			  					<li><a title="" href="#">Gelato</a></li>
			  					<li><a title="" href="#">Sorbet</a></li>
			  					<li><a title="" href="#">Chocolate Blocks</a></li>
			  					<li><a title="" href="#">Savoury</a></li>
			  					<li><a title="" href="#">Drinks</a></li>
			  					<li><a title="" href="#">What's New</a></li>
			  					<li><a title="" href="#">Gifts</a></li> -->
		  					</ul>
		  				</li>
		  				<li><a title="Our Locations" href="/locations">Our Locations</a>
		  					<ul>
		  						<li><a title="All" href="/locations#All">All</a></li>
		  						<li><a title="NSW" href="/locations#NSW">NSW</a></li>
		  						<li><a title="NT" href="/locations#NT">NT</a></li>
		  						<li><a title="SA" href="/locations#SA">SA</a></li>
		  						<li><a title="TAS" href="/locations#TAS">TAS</a></li>
		  						<li><a title="QLD" href="/locations#QLD">QLD</a></li>		  						
		  						<li><a title="VIC" href="/locations#VIC">VIC</a></li>
		  						<li><a title="WA" href="/locations#WA">WA</a></li>
		  					</ul>
		  				</li>
		  				<li><a title="Community" href="/community">Community</a>
		  					<ul>
		  						<li><a title="VIP Customer Program" href="/vip-customer-program">VIP Customer Program</a></li>
		  						<li><a title="News &amp; Media" href="/news">News &amp; Media</a></li>
		  						<li><a title="Careers" href="/careers">Careers</a></li>
		  					</ul>
		  				</li>
		  				<li><a title="Franchise Opportunities" href="franchise-opportunities">Franchise Opportunities</a>
		  					<ul>
		  						<li><a title="Master Franchise Opportunities" href="/master-franchise-opportunities">Master Franchise Opportunities</a></li>
		  						<li><a title="Becoming a Franchisee" href="/becoming-a-franchisee">Becoming a Franchisee</a></li>
		  						<li><a title="FAQs" href="/faq">FAQs</a></li>
		  					</ul>
		  				</li>
		  				<li><a title="Our Story" href="/our-story">Our Story</a></li>
		  			</ul>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	  
	  <!-- THIS IS THE AREA OF THE TEMPLATE WHERE CONTENT GOES -->
	  
	  
	  <div id="vipsignup">
	  	<div class="container">
	  		<div class="row-fluid">
	  			<div class="span5">
	  				<img src="/images/vip.png" alt="Two icecream waffle cones" />
	  			</div>
	  			<div class="span7 signupform">
	  				<div class="row-fluid">
	  						<h5>Sign up to become a VIP customer at Cocolat</h5>
	  						<div class="newsl"><a title="View previous newsletters" href="#">View previous newsletters</a></div>
	  				</div>
	  				<div class="row-fluid">
	  					<div class="span6">
	  						<div class="span4">Name:</div>
	  						<div class="span8"><input type="text" id="name1" /></div>
	  					</div>
	  					<div class="span6">
	  						<div class="span4">Email:</div>
	  						<div class="span8"><input type="text" id="email" /></div>	  								</div>
	  				</div>
	  				<div class="row-fluid">
	  					<div class="span6">
	  						<div class="span4">Age group:</div>
	  						<div class="span8"><select id="age">
	  							<option>Please select</option>
	  							<option value="Under 18">Under 18</option>
	  							<option value="18to25">18-25</option>
	  							<option value="26to40">26-40</option>
	  							<option value="Over 40">Over 40</option>	  													</select>
	  						</div>
	  					</div>
	  					<div class="span6">
	  						<div class="span4">Postcode:</div>
	  						<div class="span8"><input type="text" id="postcode" /></div>	  								</div>
	  				</div>
	  				<div class="row-fluid">
	  					<div class="span6">
		  					<div class="span4"></div>
		  					<div class="span8"><div class="button1">Submit</div></div>
	  					</div>
	  				</div>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	  <footer>
	  	<div id="foot">
	  		<div class="container">
	  			<div class="row-fluid">
	  			<div class="span5">
	  				<div class="row-fluid">
	  					<div class="span4">
	  						<ul>
	  							<li><a title="Home" href="/">Home</a></li>
	  							<li><a title="Our Menu" href="/our-menu">Our Menu</a></li>
	  							<li><a title="Our Locations" href="/locations">Our Locations</a></li>
	  							<li><a title="Community" href="/community">Community</a></li>
	  						</ul>
	  					</div>
	  					<div class="span4">
	  						<ul>
	  							<li><a title="Franchise Opportunities" href="/franchise-opportunities">Franchise Opportunities</a></li>
	  							<li><a title="Our Story" href="/our-story">Our Story</a></li>
	  							<li><a title="Our Locations" href="/locations">Contact Us</a></li>
	  							<li><a title="Community" href="/community">Social Media</a></li>
	  						</ul>
	  					</div>
	  					<div class="span4">
	  						<ul>
	  							<li><a title="Privacy Policy" href="/privacy-policy">Privacy Policy</a></li>
	  							<li><a title="Terms and Conditions" href="/terms-and-conditions">Terms and Conditions</a></li>
	  							<li><a title="Back to top^" href="#top">Back to top^</a></li>
	  						</ul>
	  					</div>
	  				</div>
	  			</div>
	  			<div class="span3 offset4">
	  				<div class="row-fluid">
	  					<img src="/images/logo.png" alt="Cocolat" />
	  				</div>
	  				<div class="row-fluid">
		  				<span class='st_sharethis_large' displayText='ShareThis'></span>
	  				</div>
	  				<div class="row-fluid">
	  					<div class="made">
	  					Made by <a title="THEM Advertising &amp; Digital" href="http://www.them.com.au">THEM Advertising &amp; Digital</a>
	  					</div>
	  				</div>
	  			</div>
	  			</div>
	  		</div>
	  	</div>
	  </footer>
</body>
</html>