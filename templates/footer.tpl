{block name=footer}
{* Define the function *}
{function name=render_footer_list level=0 parenturl="" menu=0}
	{foreach $items as $item}
		{if $level lt 1 && $item.url eq ''}
			<li><a title="{$item.title}" href="/">{$item.title}</a>
			{call name=render_footer_list items=$item.subs level=$level+1 menu=$menu}
		{else}
			<li><a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}" >{$item.title}</a>
			{if count($item.subs) > 0}
				<ul>   
					{if $level lt 1}                             
        		{call name=render_footer_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
        	{/if}
				</ul>
			{/if}
			</li>
		{/if}
	{/foreach}	
{/function}

<footer>

<div id="foot1">
	<div class="container">
		<div class="col-sm-3">
			<ul>
				{call name=render_footer_list items=$menuitems}
			</ul>
		</div>
		<div class="col-sm-3">
			<ul>
				<li><a href="/members" title="Member{if $user.id}'s area{else} Login{/if}">Member{if $user.id}'s area{else} Login{/if}</a></li>
				<li><a href="/media" title="Media {if !$media_user}Login{/if}">Media {if !$media_user}Login{/if}</a></li>
				<li><a href="/privacy-policy" title="Privacy Policy">Privacy Policy</a></li>
				<li><a href="#">Follow us on Facebook <img src="/images/fb1.png" alt="Facebook icon" title="Follow us on Facebook" /></a></li>
			</ul>
		</div>
		<div class="col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3">
			<div class="row" id="share">
				<div class="col-sm-12" id="social">
	 				Share: <a target="_blank" href="http://www.facebook.com/sharer.php?u={$DOMAIN}{$REQUEST_URI}"><img src="/images/fb.png" alt="Facebook icon" title="Share this on Facebook"/></a>
					<a target="_blank" href="http://twitter.com/home?status={if $listing_share_text}{$listing_share_text}{else}Visit Berry Funeral Directors.{/if} {$DOMAIN}{$REQUEST_URI}"><img src="/images/twitter.png" alt="Twitter icon" title="Share this on Twitter" /></a>
					<a href="mailto:?subject=Visit Berry Funeral Directors Website&body={if $listing_share_text}{$listing_share_text}{else}Have a look at our website{/if} {$DOMAIN}{$REQUEST_URI}"><img src="/images/mail.png" alt="Mail icon" title="Share by email" /></a> 
	 			</div>
			</div>
			<div class="row" id="landcare">
				<div class="col-sm-12">
					<div id="landin"><img src="/images/landcare.png" alt="" /></div>
					Proudly supporting the activities of Landcare Australia
				</div>
			</div>
		</div>
	</div>
</div>

<div id="foot2">
	<div class="container">
		<div class="col-sm-7 col-md-6 text-left">
		Copyright {'Y'|date} Australian Home Heating Association Inc.
		</div>
		<div class="col-sm-5 col-md-6 text-right">
		Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them Advertising</a>
		</div>
	</div>
</div>

<a href="#" id="totop" style="display: inline;">Back to top</a>

</footer>
{/block}
