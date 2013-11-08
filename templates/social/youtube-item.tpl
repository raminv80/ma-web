<div class="item video youtube" id="{$item.social_id}">
			<div class="trash">{if $admin neq ''}<img src="/images/socialwall/trash.png" alt="trash"  onclick="QRemove('{$item.social_id}')"  />{/if}</div>
			<div class="source"><img src="/images/socialwall/youtube.jpg" alt="youtube" /></div>
			<div class="video1">
			 <a rel="prettyPhoto[ajax]" href="/includes/social/processes-general.php?action=getitem&itemid={$item.social_id}&ajax=true&width=520&height=550">
			<img src="{$item.social_image}" alt="video" class="youtubethum"/></a></div>
			<div class="text">
				<a target="_blank" href="{$item.social_link}">{$item.social_content}</a>
			</div>
			<div class="category">Fun</div>
			{if $item.social_link neq ""}
			<div class="share">
				<div style="float:left;">Share</div>
				<a href="javascript:void(0);" class="fbshare btn_share" onclick="openshare('http://www.facebook.com/sharer.php?s=100&p[title]=Cocolat%20Community&p[url]={$item.social_link}&p[images][0]=' + encodeURIComponent('{$item.social_image}'));" ></a>
				<a href="javascript:void(0);" class="twshare btn_share" onclick="openshare('https://twitter.com/intent/tweet?original_referer=http://www.cocolat.com.au/community&url={$item.social_link}');" ></a>
				<a href="javascript:void(0);" class="pinshare btn_share" onclick="openshare('http://pinterest.com/pin/create/button/?url={$item.social_link}&media='+encodeURIComponent('{$item.social_image}'));" > </a>
				<a href="mailto:?subject=From%20Cocolat%20Community&body={$item.social_link}" class="mailshare btn_share" > </a>
			</div>
			{/if}
			<div class="person">
				<div class="persondet">
					<div class="handle"><a href="http://www.youtube.com/user/{$item.social_profile}" target="_blank">@{$item.social_profile}</a></div>
				</div>
			</div>
		</div>