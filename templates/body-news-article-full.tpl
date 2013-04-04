{block name=body}
<div class="alert">{$page_alert}</div>
<div class="newsarticle">
	<div class="news_title"><h1>{$news_data.news_title}</h1></div>
	<div class="news_date">{$news_data.news_start_date}</div>
	<div class="news_article_content">
		<div class="image"><img src="{$image}" /></div>
		<div class="news_description">{$news_data.news_long_description}</div>
	</div>
	<div class="clear"></div>
</div>
{/block}