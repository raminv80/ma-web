{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Fresh news</small><br><br></div>
    <div class="grid_8 ">
		<h1>Latest news{$title}</h1> 
        {foreach $data as $news_item}
				{include file='news-small-item.tpl'}	
		{/foreach}	
    </div><!-- end of grid_12 -->
    {foreach $menus as $m}
    	{$m}
    {/foreach}
</div>
{/block}