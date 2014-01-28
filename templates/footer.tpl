{block name=footer}
{* Define the function *}
{function name=render_footer_list level=0 parenturl="" menu=0}
	{assign var='count' value=0}
	{foreach $items as $item}
		<li ><a title="{$item.title}" href="{$parenturl}/{$item.url}" >{$item.title}</a></li>
	{/foreach}	
{/function}
<footer>
	<div id="footout">
		<div id="footin" class="container">
			<div class="row" id="newsletter" >
				<div class="visible-xs col-xs-12">
					<div class="row">
					<div class="col-sm-12">
						<h3>Sign up to our E-news</h3>
					</div>
					</div>
	
					<form id="enewsletter-m" action="http://themdigital.createsend.com/t/r/s/tyjiujk/" method="post" onsubmit="return validateForm('#enewsletter-m')">
					<div class="row">
						<div class="col-sm-4"><label for="fieldName">Name*</label></div>
						<div class="col-sm-8"><input type="text" name="cm-name" id="fieldName" class="req"></div>
					</div>
					<div class="row">
						<div class="col-sm-4"><label for="fieldEmail">Email*</label></div>
						<div class="col-sm-8"><input type="email" name="cm-tyjiujk-tyjiujk" id="fieldEmail" class="req email"></div>
					</div>
					<div class="row">
						<div class="col-sm-8 col-sm-offset-4"><input type="submit" value="Submit" title="Submit Newsletter Signup" ></div>
					</div>
					</form>
				</div>
				<div class="col-sm-3">
					<ul>
						{call name=render_list items=$menuitems}
						<li><a href="https://tatts.com/tattsbet" title="Bet Now" target="_blank">Bet Now</a></li>
					</ul>
				</div>
				<!-- <div class="hidden-xs col-sm-4 col-sm-offset-2 newsl">
					<div class="row">
					<div class="col-sm-12">
						<h3 class="hidden-sm">Sign up to our E-newsletter</h3>
						<h3 class="visible-sm">Sign up to our E-news</h3>
					</div>
					</div>
	
					<form id="enewsletter" action="http://themdigital.createsend.com/t/r/s/tyjiujk/" method="post" onsubmit="return validateForm('#enewsletter')">
					<div class="row">
						<div class="col-sm-3"><label for="fieldName">Name*</label></div>
						<div class="col-sm-9"><input type="text" name="cm-name" id="fieldName" class="req"></div>
					</div>
					<div class="row">
						<div class="col-sm-3"><label for="fieldEmail">Email*</label></div>
						<div class="col-sm-9"><input type="email" name="cm-tyjiujk-tyjiujk" id="fieldEmail" class="req email"></div>
					</div>
					<div class="row">
						<div class="col-sm-9 col-sm-offset-3"><input type="submit" value="Submit" title="Submit Newsletter Signup" ></div>
					</div>
					</form>
				</div> -->
				
				<div class="col-sm-3 share">
					<a target="_blank" href="http://www.saharnessracing.com/" title="SA Harness Racing's Website"></a>
					<div><a target="_blank" href="http://www.saharnessracing.com/" title="SA Harness Racing's Website">VISIT SA HARNESS RACING</a></div>
					<div>SHARE THIS <span class='st_sharethis_large' displayText='ShareThis'></span></div>
				</div>
			</div>
			<div id="foot2" class="row">
				<div class="col-sm-6">
				COPYRIGHT {'Y'|date} SA HARNESS | <a href="/privacy-policy" title="Privacy policy">Privacy policy</a> | <a href="/disclaimer" title="Disclaimer">Disclaimer</a>
				</div>
				<div id="them" class="col-sm-4 col-sm-offset-2">
					made by <a target="_blank" href="http://www.them.com.au" title="THEM Advertising & Digital">them advertising & digital</a>
				</div>
			</div>
		</div>
	</div>
</footer>
{/block}
