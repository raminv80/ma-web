{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | <a href="/news">Fresh news</a> | {$data[0].news_title}</small><br><br></div>
    <div class="grid_12 fresh-news">
		<h1>{$data[0].news_title}</h1> 
        <div class="news-item-wrapper">
        	<div class="news-item-img">
        	{if $data[0].news_image neq ""}<img alt="{$data[0].news_title}" src="{$data[0].news_image}" ><br/><br/>{/if}
        	</div>
            <div class="news-item-date"><small>{msyql_to_aus_date datetime=$data[0].news_date}<br><br></small></div>
            {$data[0].news_content}
            <br>
            <div class="clear"></div>
        </div><!-- end of news-item-wrapper -->
     </div><!-- end of grid_12 -->
    {foreach $menus as $m}
    	{$m}
    {/foreach}
</div>
{/block}