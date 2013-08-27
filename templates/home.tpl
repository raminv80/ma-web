{block name=body}
	<header>
		<div id="headout" class="headerbg">
				
					{include file='desktopmenu.tpl'}
					<div id="videobox">
						<div class="container">
							<div class="row-fluid">
								<div class="span5">
									Creating great memories....<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...One moment at a time!
								</div>
								<div class="span7">
									<div id="bgvideo">
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
									{assign var=x value=0}
									{foreach $gallery as $item}
										<li data-target="#carousel-example-generic" data-slide-to="{$x}" {if $x lt 1}class="active"{/if}></li>
										{assign var=x value=$x+1} 
									{/foreach}
								</ol>
								<!-- Wrapper for slides -->
								<div class="carousel-inner">
									{assign var=x value=0}
									{foreach $gallery as $item}
									<div class="item{if $x lt 1} active{/if}">
										<img src="{$item.gallery_link}" alt="{$item.gallery_file}">
									</div>
									{assign var=x value=$x+1} 
									{/foreach}
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
				<div class="quicktext">{$listing_content1}</div>
				<a href="/our-menu" class="button">View Menu</a>
			</div>
			<div class="span4">
				<img src="/images/locations.png" alt="" />
				<div class="quicktitle">Locations</div>
				<div class="quicktext">{$listing_content2}</div>
				<a href="/our-locations" class="button">View Details</a>
			</div>
			<div class="span4">
				<img src="/images/gift.png" alt="" />
				<div class="quicktitle">Gift Ideas</div>
				<div class="quicktext">{$listing_content3}</div>
				<a href="/our-menu/gifts" class="button">View Details</a>
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
					{$listing_content4}
				</div>
				<div class="span5">
					<img src="/images/blurb.png" alt="Strawberry dipped in chocolate" />
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
		
		if ($(window).width() < 940) {
		   $('#bgvideo').hide();
		   $('#headout').addClass('headerbg');
		}else{
			$('#bgvideo').show();
			$('#headout').removeClass('headerbg');
		}
		
		window.onresize = function(event) {
			if ($(window).width() < 940) {
			   $('#bgvideo').hide();
			   $('#headout').addClass('headerbg');
			}else{
				$('#bgvideo').show();
				$('#headout').removeClass('headerbg');
			}
		}
		
		/*var bgVideo=document.getElementById("background-video"); 
		$(bgVideo).bind('ended', function(){
			this.play();
		});
		bgVideo.play();*/
	});
	</script>
{/block}
