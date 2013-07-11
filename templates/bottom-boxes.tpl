<div class="row-fluid homeboxes">
			<div class="span12">
				<p class="hometitle">Find what you're looking for:</p>
				<div class="row-fluid">
					<div class="span7 boxes">
					{if $category_name neq "carpets and flooring"}
						<div class="span2">
							<a href="/products/carpets-and-flooring">
							<div class="homebox">
								<img src="/images/carpetthumb.jpg" alt="carpets" />
							</div>
							<div class="homeboxt">Carpets &amp; Flooring</div>
							</a>
						</div>
					{/if}

					{if $category_name neq "curtains and blinds"}
					<div class="span2">
						<a href="/products/curtains-and-blinds">
						<div class="homebox">
							<img src="/images/blindsthumb.jpg" alt="carpets" />
						</div>
						<div class="homeboxt">Curtains &amp; Blinds</div>
						</a>
					</div>
					{/if}
					{if $listing_id neq 22}
					<div class="span2">
						<a href="/specials">
						<div class="homebox">
							<img src="/images/special1.jpg" alt="carpets" />
						</div>
						<div class="homeboxt">Specials</div>
						</a>
					</div>
					{/if}
					{if $category_name eq "curtains and blinds" or $category_name eq "carpets and flooring"	or  $listing_id eq 22	}
					<div class="span2">
						<a href="/contact-us">
						<div class="homebox">
							<img src="/images/speakthumb.jpg" alt="carpets" />
						</div>
						<div class="homeboxt">Speak to a consultant</div>
						</a>
						</div>
					{/if}
						<div class="span2">
							<a href="/product-care">
							<div class="homebox">
								<img src="/images/productthumb.jpg" alt="carpets" />
							</div>
							<div class="homeboxt">Product Care</div>
							</a>
						</div>
						<div class="span2">
							<a href="#vid1" id="vid1">
							<div class="homebox">
								<img src="/images/videothumb.jpg" alt="carpets" />
							</div>
							<div class="homeboxt">Videos</div>
							</a>
						</div>
					</div>
					<div class="span5">
					  <div class="videos">
					  	<div id="youtube-channel"></div>
					</div>
				</div>
			</div>
	</div>
</div>