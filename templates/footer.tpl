{block name=footer}
<footer>

<div id="foot1">
	<div class="container">
		<div class="row">
			<div id="footbuttop"></div>
			<div class="col-sm-1 hidden-xs">
				<div class="socout">
					<a target="_blank" href="http://www.facebook.com/sharer.php?u={$DOMAIN}{$REQUEST_URI}"><img src="/images/fb.png" alt="Facebook icon" title="Share this on Facebook"/></a>
				</div>
				<div class="socout">
					<a target="_blank" href="http://twitter.com/home?status={if $listing_share_text}{$listing_share_text}{else}We spend over 15 hours a day sitting down. Imagine what that does to your posture! Find ergonomic solutions here:{/if} {$DOMAIN}{$REQUEST_URI}"><img src="/images/twitter.png" alt="Twitter icon" title="Share this on Twitter" /></a>
				</div>
				<div class="socout">
					<a target="_blank" href="http://tumblr.com/widgets/share/tool?canonicalUrl={$DOMAIN}{$REQUEST_URI}"><img src="/images/tumblr.png" alt="Tumblr icon" title="Share this on Tumblr"/></a>
				</div>
				<div class="socout">
					<a href="mailto:?subject=Ergonomic solutions&body={if $listing_share_text}{$listing_share_text} {$DOMAIN}{$REQUEST_URI}{else}Hi%0A%0DI saw this site and thought of you: {$DOMAIN}{$REQUEST_URI}%0A%0DThe Ergo Centre has a huge range of ergonomic solutions that can help you at work and at home. From office seating and sit to stand desks, to accessories and beds, I think you'll find what you need.%0A%0DYou can shop online, or they have a store at Keswick.%0A%0DKind regards%0A%0D{/if} "><img src="/images/mail.png" alt="Mail icon" title="Share by email" /></a>
				</div>
			</div>
			<div class="col-sm-3">
				
			</div>
			<div class="col-sm-2">
				<a href="tel://08 8293 5503">08 8293 5503</a><br />
				<a href="/contact">Email us ></a>
			</div>
			<div id="footbut">
			<div class="col-sm-3">
				<a href="/free-quote" class="btn btn-white">Get a free quote></a>
			</div>
			<div class="col-sm-3">
				<a href="/contact" class="btn btn-white">Speak to the experts></a>
			</div>
			</div>
		</div>
	</div>
</div>

<div id="foot2">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-4">
				<a href="/delivery-policy">Delivery</a>  |  <a href="/refund-policy">Refund</a>  |   <a href="/warranty-policy">Warranty</a>  |  <a href="/privacy-policy">Privacy policy</a>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="text-center-xs">Site secured by <img height="25" src="/images/RapidSSL_SEAL-90x50.gif" alt="RapidSSL"></div>
			</div>
			<div class="col-sm-6 col-md-3">
				We accept &nbsp; &nbsp;<img src="/images/mastercard.png" alt="Mastercard" />
				<img src="/images/visa.png" alt="Visa" />
				<!-- <img src="/images/paypal.png" alt="Paypal" /> -->
			</div>
			<div class="col-sm-6 col-md-2 text-right">
				<div class="text-center-xs">All prices in AUD.</div>
			</div>
		</div>
	</div>
	<div id="mobbot">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					Copyright {"Y"|date} {$COMPANY.name}
				</div>
				<div class="col-sm-6 text-right">
					<div class="text-center-xs">Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them Advertising</a></div>
				</div>
			</div>
		</div>
	</div>
</div>

<a href="#" id="totop">Back to top</a>

</footer>
{/block}
