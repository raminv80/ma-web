{block name=bulletinsSlider}
<div id="news-bar-wrapper">
	<div class="container_16">
		<div class="grid_15">
			{$n = 1}
			{foreach $bulletins as $bulletin}
			<div class="bulletin-{$n} bulletin-item" {if $n neq 1}style="display:none"{/if}>
			{include file='bulletins-item.tpl'}
			</div>
			{$n = $n+1}
		    {/foreach}	
	    </div>
    	<div class="news-next grid_1">
        	<a href="javascript:void(0);" onclick="NextBul();" ><img src="/images/template/news-right-arrow.png" border="0" /></a>
        </div>
    </div>
    <script>
    var timer = setInterval( "slideSwitch()", 4000 );
    function slideSwitch() {
		 var $active = $(".bulletin-item.active");
		 $active.hide();
		 $active.removeClass('active');
		var $next = $active.next().size() ? $active.next(): $(".bulletin-item").first();
		$next.show().addClass('active');
		
    } 
 	$(document).ready(function() {
 	 	
    	//var timer =	setTimeout(function() { slideSwitch() }, 4000);
	   
	});
 	function NextBul(){
		//window.clearTimeout(timer);
		window.clearInterval(timer);
		slideSwitch();
	}
    </script>
        
</div><!-- end of container_16 -->
{/block}