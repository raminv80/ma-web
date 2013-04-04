{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Bulletins</small><br><br></div>
    <div class="grid_12 fresh-news">
		<h1>Bulletins{$title}</h1> 
		{foreach $data as $bulletin}
		{include file='bulletin-list-item.tpl'}
	    {/foreach}
        
    </div><!-- end of grid_12 -->
    {foreach $menus as $m}
    	{$m}
    {/foreach}
</div><!-- end of container_16 -->
{/block}