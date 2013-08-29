{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
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
			<div class="clear"></div>
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
				<a href="/our-locations" class="button">View Locations</a>
			</div>
			<div class="span4">
				<img src="/images/gift.png" alt="" />
				<div class="quicktitle">Gift Ideas</div>
				<div class="quicktext">{$listing_content3}</div>
				<a href="/our-menu#gifts" class="button">View Gift Ideas</a>
			</div>
		</div>
	</div>



	<div id="whitebox">
		<div class="container instahome">
			<div class="row-fluid">
				<h3 class="title">What our customers are enjoying</h3>
			</div>
			<div class="row-fluid instafeed">
				<!-- This is where the instagram feed at the bottom of the page is displayed -->
				{if count($instagram) eq 10}
				<div class="row-fluid">
					<div class="instafeed-col small">
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[0].social_image}" alt="Instagram image by {$instagram[0].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[1].social_image}" alt="Instagram image by {$instagram[1].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[2].social_image}" alt="Instagram image by {$instagram[2].social_profile}" /></a></div>
					</div>
					<div class="instafeed-col medium">
						<div class="instafeed-medium"><a href="/community" title="Community"><img src="{$instagram[3].social_image}" alt="Instagram image by {$instagram[3].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[4].social_image}" alt="Instagram image by {$instagram[4].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[5].social_image}" alt="Instagram image by {$instagram[5].social_profile}" /></a></div>
					</div>
					<div class="instafeed-col large">
						<div class="instafeed-large"><a href="/community" title="Community"><img src="{$instagram[6].social_image}" alt="Instagram image by {$instagram[6].social_profile}" /></a></div>
					</div>
					<div class="instafeed-col  small">
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[7].social_image}" alt="Instagram image by {$instagram[7].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[8].social_image}" alt="Instagram image by {$instagram[8].social_profile}" /></a></div>
						<div class="instafeed-small"><a href="/community" title="Community"><img src="{$instagram[9].social_image}" alt="Instagram image by {$instagram[9].social_profile}" /></a></div>
					</div>
					<div class="clear"></div>
				</div>
				{else}
				<img src="/images/instaplaceholder.png" alt="Instagram Placeholser" />
				{/if}
			</div>
			<div class="row-fluid instatext">
				<div class="span2">
					<img src="/images/biginstagram.png" alt="Large version of the Instagram Icon" />
				</div>
				<div class="span10">
					<h3>Be a part of our website.</h3>
					<p>
						Tag <span>#cocolat</span> on your instagram snaps and your photo
						could appear on our site!
					</p>
					<p>
						View our Instagram web profile page <a href="#">here</a> or <a
							href="/community">see</a> what our valued customers have to say about
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
	
	{if $mobile neq true}
	<script type="text/javascript">
	$(document).ready(function() {
		
		//$('#bgvideo').videoBG({
		$('.headerbg').videoBG({
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
		   $('.videoBG').hide();
		}else{
			$('.videoBG').show();
		}
		
		window.onresize = function(event) {
			if ($(window).width() < 940) {
			   $('.videoBG').hide();
			}else{
				$('.videoBG').show();
			}
		}
		
		/*var bgVideo=document.getElementById("background-video"); 
		$(bgVideo).bind('ended', function(){
			this.play();
		});
		bgVideo.play();*/
	});
	</script>
	{/if}
{/block}
