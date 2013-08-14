<div class="facebook item" id="{$item.social_id}">
			<div class="trash">{if $admin neq ''}<img src="/images/trash.png" alt="trash" onclick="QRemove('{$item.social_id}')"  />{/if}</div>
			<div class="source"><img src="/images/facebook-logo.png" alt="facebook" class="facebook-logo"/></div>
			<div class="image">
			 <a rel="prettyPhoto[ajax]" href="/includes/processes/processes-general.php?action=getitem&itemid={$item.social_id}&ajax=true&width=520&height=550">
			{if $item.social_image neq ''}<img src="{$item.social_image}"  style=" margin-left: 8%;" />{/if}
			</a>
			</div>
			<div class="text">
				 <a href="{$item.social_link}" target="_blank">{trimsocialcontent data=$item.social_content}</a>
			</div>
			<div class="share"><div style="float:left;">Share</div>
							<a href="javascript:void(0);" onclick="fbShare({$item.social_id}, 'title', 'Shared from social wall', '{$item.social_image}');"><div class="fbshare"></div></a>
							<a href="javascript:void(0);" onclick="TwShare({$item.social_id},'{trimsocialcontent data=$item.social_content}');"><div class="twshare"></div></a>
							<a href="javascript:void(0);" onclick="PiShare({$item.social_id},'{$item.social_image}','{trimsocialcontent data=$item.social_content}')"><div class="pinshare"></div></a>
							<a href="mailto:?subject=From%20The%20SocialWall&body=http://socialwall.themserver.com/item/{$item.social_id}"><div class="mailshare"></div></a>
			</div>
			<div class="person">

			</div>
</div>