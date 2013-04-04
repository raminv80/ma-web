{block name=body}
<div class="alert">{$page_alert}</div>
<div id="newsarticleslist">
	{foreach key=key  item=article from=$news_data}
		<div id="article" class="newsarticle">
			<div class="image"><img src="{$article.image}" /></div>
			<div class="content">
				<div id="date" class="news-date">{$article.news_start_date}</div>
				<div id="title" class="news-title">{$article.news_title}</div>
				<div id="short_description" class="news-short_description">
					{$article.news_short_description}
					<div class="short_readme">
						<a id="short_readme" class="short_readme" href="/news/{$article.news_url_title}">Read the full story</a>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	{/foreach}
</div>
{/block}