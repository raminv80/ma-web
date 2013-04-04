{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Videos</small><br><br></div>
      <div class="grid_16">
		<h1>Videos</h1> 
		<br/>{$content}
	</div>
	<div class="clear"></div>
    <div class="container_16">
    	{$count = 0}
	     {foreach $data as $video_item}
	     	{$count= $count+1}
	       <div class="video-wrapper grid_8 left" id="video-{$video_item.video_id}" name="video-{$video_item.video_id}"> 
	        	<h2>{$video_item.video_title}</h2>
	            {$video_item.video_description}
	        	<iframe height="259" frameborder="0" width="460" allowfullscreen="" src="http://www.youtube.com/embed/{$video_item.video_link}"></iframe>
			     {if $video_item.video_tags neq ""}
			       <br/><small>TAGS:
			            {assign var=tags value=","|explode:$video_item.video_tags}
			            {foreach $tags as $tag}
			            	{if $tag neq ""}
			            	 <a href="/search?search={$tag|trim|urlencode}">{$tag|trim}</a>&nbsp 
			            	{/if}
			            {/foreach}
			        </small><br/>
			     {/if}
	       </div><!-- end of video-wrapper -->
	      {if $count eq 2}<div class="clear"></div>{$count=0}{/if}
	      {/foreach}	
    </div><!-- end of grid_8 -->
</div>
{/block}