{block name=footer}
	<div id="footer-wrapper">
	<div class="container_16">
    	<div class="grid_3">
        	<ul>
        		{foreach $menuitems as $value}
				   <li>
				   		<a title="{$value.title}" href='/{$value.url}' {$value.rel}  >{$value.title}</a>
				   </li>
				{/foreach}
            </ul>
        </div><!-- end of grid_3 -->
    	<div class="grid_4">
        	<ul>
            	<li><a href="/bulletin">Pruducts</a></li>
            </ul>
        </div><!-- end of grid_4 -->
    	<div class="grid_4">
        	<p>Trading Hours<br>
            blah<br>
            P 08 8888 8888<br>
            E <a href="mailto:info@allfresh.com.au">info@allfresh.com.au</a></p>
        </div><!-- end of grid_4 -->
    	<div class="grid_5 testimonial">
        	<a href="/#" title="Watch All Fresh videos on YouTube"><img src="/images/template/yt.png" border="0" /></a>
        	<a href="/#" title="Follow All Fresh on Twitter"><img src="/images/template/tt.png" border="0" /></a> 
        	<a href="/#" title="Like All Fresh on Facebook"><img src="/images/template/fb.png" border="0" /></a> 
        </div><!-- end of grid_5 -->
        
		<div class="clear"></div><br>
        
        <div class="grid_7"><small>&copy; Copyright {'Y'|date} Hall Towbars</small></div>
        <div class="grid_9"><small>Made by <a href="http://www.them.com.au" target="_blank" title="Adelaide, South Australia Website Design and Development by THEM Advertising & Digital">THEM Advertising &amp; Digital</a></small></div>
    
	</div><!-- end of container_16 -->
    

</div>
{/block}