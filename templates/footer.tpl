{block name=footer}

<div id="footer">
	<div class="container">
			<div class="row-fluid">
			<div class="span4">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">About us</a></li>
					<li><a href="#">Retailers</a></li>
					<li><a href="#">Contact us</a></li>
				</ul>
			</div>
			<div class="span4">
				<ul>
					<li><a href="#">Products</a></li>
					<li><a href="#">Support</a></li>
					<li><a href="#">Warranty and registration</a></li>
					<li><a href="#">Interactive</a></li>
				</ul>
			</div>
			<div class="span4">
				<p>Stay connected with us</p>
				<a href="#"><img src="/images/facebook.png" alt="facebook" /></a>
				<a href="#"><img src="/images/twitter.png" alt="twitter" /></a>
				<a href="#"><img src="/images/youtube.png" alt="youtube" /></a>
			</div>
			<div class="span4">
			address details - # street <br />
			go here - suburb state pc<br />
			P 08 #### ####<br />
			F 08 #### ####		
			</div>
			</div>
	</div>
</div>    

<div id="footer2">
	<div class="container">
		<div class="span16">
			<div class="row-fluid">

			<div class="span4">
				<p>&copy; COPYRIGHT 2013 CLI~MATE</p>
			</div>
			<div class="span4 offset8">
				<p>MADE BY <a href="http://them.com.au">THEM ADVERTISING & DIGITAL</a></p>
			</div>

			</div>
		</div>
	</div>
</div>
    
    
<!-- Placed at the end of the document so the pages load faster -->
   
    <script src="/includes/js/bootstrap.min.js"></script>
    <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".featured-container .span5").hover(function(){
			jQuery(this).find(".btn").css('visibility','visible');
		},function(){
			jQuery(this).find(".btn").css('visibility','hidden');
		});

		jQuery(".nav li.dropdown").hover(function(){
			jQuery(this).find('ul.dropdown-menu').css('display','block');
		},function(){
			jQuery(this).find('ul.dropdown-menu').css('display','none');
		});

	});
    </script>

<script type="text/javascript">
jQuery(document).ready(function($){

	$('#top-menu').prepend('<div id="menu-icon">Menu</div>');


	/* toggle nav */
	$("#menu-icon").on("click", function(){
		$(".nav1").slideToggle();
		$(this).toggleClass("active");
	});

});
</script>
{/block}