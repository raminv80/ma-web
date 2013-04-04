{block name=news_small_item}
 <div class="search-item">
            <div class="title">{$item.faq_question|strip_tags}</div>
            <div class="tags">TAGS:
	            {assign var=tags value=","|explode:$item.faq_tags}
	            {foreach $tags as $tag}
	            	{if $tag neq ""}
	            	 <a href="/search?search_text={$tag|trim|urlencode}">{$tag|trim}</a>&nbsp 
	            	{/if}
	            {/foreach}
		    </div>
            <div class="link"><a href="faq#faq-{$item.faq_id}">Read more</a></div>
        	<div class="clear"></div>
 </div><!-- end of news-item-wrapper -->
{/block}
