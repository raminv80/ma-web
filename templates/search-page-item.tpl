{block name=search_small_item}
 <div class="search-item">
            <div class="title">{$item.page_title|strip_tags}</div>
            <div class="description">{$item.page_short_description|strip_tags}</div>
            <div class="tags">TAGS:
	            {assign var=tags value=","|explode:$item.page_tags}
	            {foreach $tags as $tag}
	            	{if $tag neq ""}
	            	 <a href="/search?search_text={$tag|trim|urlencode}">{$tag|trim}</a>&nbsp 
	            	{/if}
	            {/foreach}
		    </div>
            <div class="link"><a href="/{urlencode data=$item.page_url}">Read more</a></div>
        	<div class="clear"></div>
 </div><!-- end of news-item-wrapper -->
{/block}
