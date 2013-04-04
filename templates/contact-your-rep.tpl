{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | Contact your rep</small><br><br></div>
    <div class="grid_8">
		<h1>Contact your rep</h1> 
        <p><strong>{$rep_name}</strong><br>
        P {$rep_phone}<br>
        E <a href="mailto:{$rep_email}">{$rep_email}</a></p>
       
        
    </div><!-- end of grid_8 -->
    <div class="grid_8">
    	{$content}
    </div><!-- end of grid_8 -->
</div><!-- end of container_16 -->
{/block}