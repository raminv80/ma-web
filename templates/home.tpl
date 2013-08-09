{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout">
				<div id="bgvideo">
					{include file='desktopmenu.tpl'}
					<div id="videobox">
						<div class="container">
							<div class="row-fluid">
								<div class="span5">
									Creating great memories....<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...One moment at a time!
								</div>
								<div class="span7">
									<div class="videowrapper">			
										<!-- <video height="100%" width="100%" id="background-video" >
											<source src="videos/cocolate_fountain.mp4" type="video/mp4">
							 				<source src="videos/cocolate_fountain.ogg" type="video/ogg">
							 				<source src="videos/cocolate_fountain.webm" type="video/webm">
										</video> -->
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="slider">
						<div class="container">
							<div id="carousel-example-generic" class="carousel slide">
								<!-- Indicators -->
								<ol class="carousel-indicators">
									<li data-target="#carousel-example-generic" data-slide-to="0"
										class="active"></li>
									<li data-target="#carousel-example-generic" data-slide-to="1"></li>
								</ol>
								<!-- Wrapper for slides -->
								<div class="carousel-inner">
									<div class="item active">
										<img src="/images/slideshow1.png" alt="">
									</div>
									<div class="item">
										<img src="/images/slideshow1.png" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div id="orangebox">
		<div class="container">
			<div class="span4">
				<img src="/images/menu.png" alt="" />
				<div class="quicktitle">View Our Menu</div>
				<div class="quicktext">Waffles waffles waffles Lorem ipsum
					dolor sit amet, consectetur adipiscing elit. Pellentesque interdum
					mauris neque, at hendrerit eros. Nam consequat hendrerit cursus.
					Donec dignissim enim id massa gravida id egestas nunc euismod.
					Integer pellentesque porta adipiscing. Nullam</div>
				<a href="#"><div class="button">View Menu</div></a>
			</div>
			<div class="span4">
				<img src="/images/locations.png" alt="" />
				<div class="quicktitle">Locations</div>
				<div class="quicktext">Waffles waffles waffles Lorem ipsum
					dolor sit amet, consectetur adipiscing elit. Pellentesque interdum
					mauris neque, at hendrerit eros. Nam consequat hendrerit cursus.
					Donec dignissim enim id massa gravida id egestas nunc euismod.
					Integer pellentesque porta adipiscing. Nullam</div>
				<a href="#"><div class="button">View Details</div></a>
			</div>
			<div class="span4">
				<img src="/images/gift.png" alt="" />
				<div class="quicktitle">Gift Ideas</div>
				<div class="quicktext">Waffles waffles waffles Lorem ipsum
					dolor sit amet, consectetur adipiscing elit. Pellentesque interdum
					mauris neque, at hendrerit eros. Nam consequat hendrerit cursus.
					Donec dignissim enim id massa gravida id egestas nunc euismod.
					Integer pellentesque porta adipiscing. Nullam</div>
				<a href="#"><div class="button">View Details</div></a>
			</div>
		</div>
	</div>



	<div id="whitebox">
		<div class="container instahome">
			<div class="row-fluid">
				<h3 class="title">What our customers are enjoying</h3>
			</div>
			<div class="row-fluid instafeed">
				<img src="/images/instaplaceholder.png" alt="" />
			</div>
			<div class="row-fluid instatext">
				<div class="span2">
					<img src="/images/biginstagram.png" alt="" />
				</div>
				<div class="span10">
					<h3>Be a part of our website.</h3>
					<p>
						Tag <span>#cocolat</span> on your instagram snaps and your photo
						could appear on our site!
					</p>
					<p>
						View our Instagram web profile page <a href="#">here</a> or <a
							href="#">see</a> what our valued customers have to say about
						Cocolat.
					</p>
				</div>
			</div>
		</div>
	</div>

	<div id="brownbox">
		<div class="container">
			<div class="row-fluid">
				<div class="span7">
					<p>Specialising in the art of chocolate indulgences and wicked
						desserts, all of our products are hand-made from the finest
						ingredients in our Balhannah kitchen based in the beautiful
						Adelaide Hills.</p>

					<p>At Cocolat, we strive to create great memories for our
						customers, with a exceptional service and finest quality products.</p>
				</div>
				<div class="span5">
					<img src="/images/blurb.png" alt="" />
				</div>
			</div>
		</div>
	</div>

	{include file='signup.tpl'} {include file='footer.tpl'}
	
	<script type="text/javascript">
	$(document).ready(function() {
		 $('#bgvideo').videoBG({
			mp4:'videos/cocolate_fountain.mp4',
			ogv:'videos/cocolate_fountain.ogv',
			webm:'videos/cocolate_fountain.webm',
			poster:'videos/cocolate_fountain.png',
			scale:true,
			width: '100%',
			height: '100%',
			zIndex:0
		}); 
		/*var bgVideo=document.getElementById("background-video"); 
		$(bgVideo).bind('ended', function(){
			this.play();
		});
		bgVideo.play();*/
	});
	</script>
{/block}
