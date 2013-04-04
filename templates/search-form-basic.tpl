{block name=searchformbasic}
<div id="search">
	<form id="search" name="search" method="post" action="{$searchformaction}">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><input id="search_text" class="textbox" type="text" placeholder="Search this site..." value="" name="search_text"></td>
				<td>
					{if $searchbuttontype eq 'image'}
						<input id="searchbtn" type="image" onclick="submit();" name="searchbtn" src="/images/go_button.gif">
					{else}
						<input id="searchbtn" type="submit" onclick="submit();" name="searchbtn" value="Search" class="searchbtn">
					{/if}
				</td>
			</tr>
		</table>
		<input id="Action_search" type="hidden" value="Search" name="Action">
	</form>
</div>
{/block}