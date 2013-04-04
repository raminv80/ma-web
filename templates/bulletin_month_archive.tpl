{block name=menu}
	<div class="grid_4">
    	<h1>Archive</h1>
        <div class="grid_4 alpha omega archive">
        	{assign "preYear" "0"}
        	{foreach $menuitem as $item}
        		{if $preYear neq $item.year}
        		{assign "preYear" $item.year}
	        	<h2>{$item.year}</h2>
	        	{/if}
	            <ul>
	            	<li><a href="/bulletin/{$item.year}-{$item.month|strtolower}">{$item.month} ({$item.num})</a></li>
	            </ul>
            {/foreach}
        </div>
    </div>
{/block}