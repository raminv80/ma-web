{block name=newsarchivelist}
<div id="news-year-list" class="news-year-list">
	<div class="clist-title"><h2>News Archives</h2></div>
	<ul>
		<li><a href="{$currentnewslink}">Current News</a></li>
	</ul>
	<p>View previous news articles by year:</p>
	<ul>
		{foreach $archiveyears as $archiveyear}
			<li><a href="{$archiveyear.link}">{$archiveyear.year}</a></li>
		{/foreach}
	</ul>
</div>
{/block}