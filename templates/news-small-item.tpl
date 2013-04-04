{block name=news_small_item}
 <div class="news-item-wrapper">
     <div class="news-item-img"><img src="{if $news_item.news_image neq ""}{$news_item.news_image}{else}{/if}" /></div>
     <div class="news-item-date"><small>{msyql_to_aus_date datetime=$news_item.news_date}</small></div>
     <h2>{$news_item.news_title|strip_tags}</h2>
     <p>{$news_item.news_short_content|strip_tags}<br/> <a href="/news/{urlencode data=$news_item.news_title}">Read more</a></p>
     <div class="clear"></div>
 </div><!-- end of news-item-wrapper -->
{/block}
