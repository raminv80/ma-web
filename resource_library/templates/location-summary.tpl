{block name=location_summary}

<div class="row">
   	<div class="col-md-12">
    	<h3>METROPOLITAN</h3>
    </div>
     {assign var='empty' value=1}
     {foreach $data as $d}
     	{if $d.location_metro eq '1'}
     	{assign var='empty' value=0}
     <div class="col-md-3 col-sm-4 location-summary loc" latitude="{$d.location_latitude}" longitude="{$d.location_longitude}" title="{$d.listing_name}" pin="{$d.location_pin}">
			<div>
				<a href="/{$listing_url}/{$d.listing_url}"><span class="location-suburb">{$d.listing_name|upper}</span></a> <span class="green">{if $d.listing_flag1}7 DAYS{/if} {if $d.listing_flag2}Day/Night{/if}</span><br>
				 {if $d.listing_flag3}<span class="compound">(Compounding Pharmacy)</span><br>
				 {/if} {$d.location_street}<br>
				{$d.location_state} {$d.location_postcode}<br> 
				<span class="location-phone">P <a href="tel:{$d.location_phone}">{$d.location_phone}</a></span><br>
				 E {obfuscate email=$d.location_email}<br>
				  {if $d.location_url}
				  	<img src="images/template/locations-fb.png" /> <a href="{$d.location_url}" target="_blank">Find us on Facebok</a><br>
				  {/if} 
				  <a href="/{$listing_url}/{$d.listing_url}">View more details</a>
			</div>
		</div>
      {/if}
     {/foreach}
     {if $empty eq 1} <div class="nofound">No pharmacies were found.</div>{/if}
    </div>
   
 <div class="row">
   	<div class="col-md-12">
     <h3>REGIONAL</h3>
    </div>
     {assign var='empty' value=1}
     {foreach $data as $d}
     	{if $d.location_regional eq '1'}
     	{assign var='empty' value=0}
      	<div class="col-md-3 col-sm-4 location-summary loc" latitude="{$d.location_latitude}" longitude="{$d.location_longitude}" title="{$d.listing_name}" pin="{$d.location_pin}">
			<div>
				<a href="/{$listing_url}/{$d.listing_url}"><span class="location-suburb">{$d.listing_name|upper}</span></a> <span class="green">{if $d.listing_flag1}7 DAYS{/if} {if $d.listing_flag2}Day/Night{/if}</span><br>
				 {if $d.listing_flag3}<span class="compound">(Compounding Pharmacy)</span><br>
				 {/if} {$d.location_street}<br>
				{$d.location_state} {$d.location_postcode}<br> 
				<span class="location-phone">P <a href="tel:{$d.location_phone}">{$d.location_phone}</a></span><br>
				 E {obfuscate email=$d.location_email}<br>
				  {if $d.location_url}
				  	<img src="images/template/locations-fb.png" /> <a href="{$d.location_url}" target="_blank">Find us on Facebok</a><br>
				  {/if} 
				  <a href="/{$listing_url}/{$d.listing_url}">View more details</a>
			</div>
		</div>
      {/if}
     {/foreach}
     {if $empty eq 1} <div class="nofound">No pharmacies were found.</div>{/if}
 </div>


{/block}