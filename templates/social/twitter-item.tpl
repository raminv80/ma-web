		<div class="twitter item" id="{$item.social_id}">
			<div class="trash">{if $admin neq ''}<img src="/images/trash.png" alt="trash" onclick="QRemove('{$item.social_id}')" />{/if}</div>
			<div class="source"><img src="/images/twitter1.png" alt="twitter" /></div>
			<div class="text">
			 <a rel="prettyPhoto[ajax]" href="/includes/processes/processes-general.php?action=getitem&itemid={$item.social_id}&ajax=true&width=520&height=550">
				{$item.social_content}
				</a>
			</div>
			<div class="share"><div style="float:left;">Share</div>
							<a href="javascript:void(0);" onclick="fbShare({$item.social_id}, 'Twitter-SocialWall', '{trimsocialcontent data=$item.social_content}', 'http://socialwall.themserver.com/images/facebook.jpg');" ><div class="fbshare"></div></a>
							<a href="javascript:void(0);" onclick="TwShare({$item.social_id},'{trimsocialcontent data=$item.social_content}');"><div class="twshare"></div></a>
							<a href="javascript:void(0);" onclick="PiShare({$item.social_id},'','{trimsocialcontent data=$item.social_content}')"><div class="pinshare"></div></a>
							<a href="mailto:?subject=From%20The%20SocialWall&body=http://socialwall.themserver.com/item/{$item.social_id}"><div class="mailshare"></div></a>

			</div>
			<div class="person">
				<img src="{$item.social_profile_img}" alt="photo"  class="twitter-prof" />
				<div class="persondet">
					<div class="handle"><a href="http://www.twitter.com/{$item.social_profile}" target="_blank">@{$item.social_profile}</a></div>
				</div>
			</div>
		</div>
