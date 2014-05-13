{block name=head}

<!-- for Facebook -->
<meta property="og:title" content="{$product_name}" />
<meta property="og:type" content="og:product" />
{if $gallery.0.gallery_link}<meta property="og:image" content="{$DOMAIN}{$gallery.0.gallery_link}">{/if}
<meta property="og:url" content="{$DOMAIN}{$REQUEST_URI}" />
<meta property="og:description" content="{$product_short_description|strip_tags:false}" />

<!-- for Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{$DOMAIN}{$REQUEST_URI}">
<meta name="twitter:title" content="{$product_name}">
<meta name="twitter:description" content="{$product_short_description|strip_tags:false}">
<meta name="twitter:creator" content="@THEMAdvertising">
{if $gallery.0.gallery_link}<meta name="twitter:image:src" content="{$DOMAIN}{$gallery.0.gallery_link}">{/if}
<meta name="twitter:domain" content="{$DOMAIN}">

<style type="text/css">
#bannerout {
  background: url("{if $listing_parent.listing_content4}{$listing_parent.listing_content4}{else}/images/slider1.jpg{/if}") no-repeat;
}
</style>
<link href="/includes/css/jquery.navgoco.css" rel="stylesheet">

<script src="/includes/js/jquery.navgoco.min.js"></script>
<script src="/includes/js/responsive-tabs.js"></script>
<script src="/includes/js/jquery.cycle2.min.js"></script>
<script src="/includes/js/jquery.cycle2.carousel.js"></script>
<script src="/includes/js/jquery.cycle2.swipe.min.js"></script>
{/block}

{block name="body"}
{function name=render_menu level=0 parenturl=''} 
  {foreach from=$items key=k item=it}
    <li {if $it.selected eq 1}class="{if count($it.listings) > 0}open{else}active{/if}"{/if}>{if count($it.listings) > 0}<a href="{$parenturl}/{$it.listing_url}" title="view {$it.listing_name} products" >{$it.listing_name}</a>{else}<a href="{$parenturl}/{$it.listing_url}" title="view {$it.listing_name} products">{$it.listing_name}</a>{/if}
    {if count($it.listings) > 0}
      <ul>
       {if count($it.listings) > 0}<li><a href="{$parenturl}/{$it.listing_url}" title="view all {$it.listing_name} products">VIEW ALL</a></li>{/if}
        {call name=render_menu items=$it.listings level=$level+1 parenturl=$parenturl|cat:"/"|cat:$it.listing_url menu=$menu}
      </ul>
    {/if}
  {/foreach}
{/function}

<div id="maincont2">
	<div class="container">
		<div class="row">
		  <div class="col-sm-12 breadcrumbs">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
		<div class="row">
				<div class="col-sm-3 sidebar">
					<div class="row">
						<h4>Product navigation</h4>
						<div id="navop">click to view product categories</div>
						<ul id="prodnav">{call name=render_menu items=$categories parenturl="/diy/products"}
						</ul>
					</div>
					<div class="row hidden-xs">
						<div class="col-sm-12 menu2item">
							<a href="/diy/find-a-store" title="Click here to find a Steeline store near you"> <img src="/images/findstore.png" alt="Click here to find a Steeline store near you" title="Click here to find a Steeline store near you" /> Find a store
							</a>
						</div>
						<!-- <div class="col-sm-12 menu2item">
							<a href="/diy/recent-projects"> <img src="/images/recent.png" /> Recent projects
							</a>
						</div> -->
						<div class="col-sm-12 menu2item">
							<a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get a quote from a Steeline store near you"> <img src="/images/reqquotebig.png" title="Click here to get a quote from a Steeline store near you" alt="Click here to get a quote from a Steeline store near you" /> Get a quote
              </a>
						</div>
						<div class="col-sm-12 menu2item2">
							<a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get help from a Steeline store near you"> <img src="/images/needhelp.png" title="Click here to get help from a Steeline store near you" /> Need help?
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-9 mobdetail"></div>
				<div class="col-sm-9 detail">
					<div class="row" id="prodname1">
						<div class="col-sm-12">
							<h1>{$product_name}</h1>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div id="prodimg1">
								<div id="slideshow-1">
									{literal}<div id="cycle-1" class="cycle-slideshow" data-cycle-slides="> div" data-cycle-timeout="0" data-cycle-swipe=true data-cycle-prev="#slideshow-1 .cycle-prev" data-cycle-next="#slideshow-1 .cycle-next" data-cycle-caption="#slideshow-1 .custom-caption"	data-cycle-caption-template="Slide {{slideNum}} of {{slideCount}}" data-cycle-fx="tileBlind"> {/literal}
										<a href="#" class="cycle-prev"><img src="/images/catarrleft.png" alt=""></a> <a href="#" class="cycle-next"><img src="/images/catarrright.png" alt=""></a>
										{if count($gallery) > 0}
                    {foreach from=$gallery key=k item=gl}
                    <div>
                      <img src="{$gl.gallery_link}" alt="{$gl.gallery_alt_tag}" title="{$gl.gallery_title}">
                    </div>
                    {/foreach}
                    {else}
				            <div>
				              <img src="/images/no-image-available.png">
				            </div>
						        {/if}
									</div>
								</div>
								<div id="slideshow-2">
									{literal}<div id="cycle-2" class="cycle-slideshow" data-cycle-slides="> div" data-cycle-timeout="0" data-cycle-prev="#slideshow-2 .cycle-prev" data-cycle-next="#slideshow-2 .cycle-next" data-cycle-caption="#slideshow-2 .custom-caption" data-cycle-caption-template="Slide {{slideNum}} of {{slideCount}}" data-cycle-fx="carousel" data-cycle-carousel-visible="{/literal}{if count($gallery) < 4}{count($gallery)}{else}4{/if}{literal}" data-cycle-carousel-fluid=true data-allow-wrap="false">{/literal}
										{if count($gallery) > 0}
                    {foreach from=$gallery key=k item=gl}
                    <div>
                      <img src="{$gl.gallery_link}" alt="{$gl.gallery_alt_tag}" title="{$gl.gallery_title}">
                    </div>
                    {/foreach}
                    {else}
                    <div>
                      <img src="/images/no-image-available.png">
                    </div>
                    {/if}
									</div>
								</div>
							</div>

						</div>

					
						<div class="col-sm-5 col-sm-offset-1">
							<div id="movblk">
								<div class="view" id="addest">
								{if $productspec_estimate eq 1}
									<a href="javascript:void(0);" onclick="modalEstimate({$product_object_id},'estimate','addestimate');" title="Click here to add {$product_name} to your estimate">add to estimate +</a>
								{/if}
								</div>
								<div class="view" id="addfav">
								{if $productspec_favourite eq 1}
									<a href="javascript:void(0);" onclick="addFavourite({$product_object_id},'{$product_name}');" title="Click here to add {$product_name} to your favourites">add to favourites *</a>
								{/if}
								</div>
								<div class="clearfix"></div>
								<br />
								{if $productspec_estimate eq 1}
								<div class="bold" >Find a store/stockist <span id="postcode-help" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><img src="/images/questionmark.png" alt="?"  style="width:auto;height:15px"/></span></div>
								<div id="findstock">
									Enter your postcode: <input type="number" id="stockpc" name="stockpc" onkeyup="checkPostcodeAvailability(this);" /> <img src="/images/arrow.png" />
									<div id="availablilitylist"></div>
								</div>
								{/if}
								<div id="facebook-like">
									<iframe src="//www.facebook.com/plugins/like.php?href={$DOMAIN|escape:'url'}{$REQUEST_URI|escape:'url'}&amp;width=180&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21&amp;appId=212771495598580" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:21px;" allowTransparency="true"></iframe>
								</div>
								<!-- <div id="like">
									<a href="#"> <img src="/images/like.png" /> Like
									</a>
								</div>
								<div id="share">
									<a href="#"> <img src="/images/share.png" /> Share
									</a>
								</div> -->
							</div>
						</div>
					</div>
				
					<div class="row" id="prodspecs">
						<div class="col-sm-12">
							<div class="row">
							
							<div class="col-sm-12">
							<div id="desc">
								{if $product_description}{$product_description}{else}{$product_short_description}{/if}
							</div>
							{if $productspec_colorbond_roofing eq '1'}
							<div id="colorbond">
									<h3>
										Colorbond<sup>&reg;</sup> Roofing Colours
									</h3>
									<a href="/diy/colorbond#colorbondand-roofing-and-rainwater" title="COLORBOND&reg; colours">
										<img class="img-responsive" src="/images/colorbond.jpg" alt="colorbond" />
										Click here to see the full COLORBOND&reg; range.
									</a>
							</div>	
							{/if}	
							{if $productspec_colorbond_fencing eq '1'}
              <div id="colorbond">
                  <h3>
                    Colorbond<sup>&reg;</sup> Fencing Colours
                  </h3>
                  <a href="/diy/colorbond#colorbondand-fencing" title="COLORBOND&reg; colours">
                    <img class="img-responsive" src="/images/colorbond.jpg" alt="colorbond" />
                    Click here to see the full COLORBOND&reg; range.
                  </a>
              </div>  
              {/if} 				
							<br />
							{if ($productspec_estimate eq 1) && ($productspec_techinfo neq "" || $productspec_brochure neq "" || $productspec_cad neq "" || $productspec_warranty neq "" || $attribute )}
								<h3>Product specifications</h3>
							{/if}
							</div>

						</div>
					{if ($productspec_estimate eq 1) && ($productspec_techinfo neq "" || $productspec_brochure neq "" || $productspec_cad neq "" || $productspec_warranty neq "" || $attribute )}
					<div class="row">
						<div class="col-sm-12">
						  {assign var=firsttab value=1}
							<ul id="prodtabs" class="nav nav-tabs responsive">
								{if $productspec_techinfo neq ""}<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#tech-info" data-toggle="tab" title="Click here for {$product_name} technical information">Technical information</a></li>{/if}
								{if $productspec_brochure neq ""}<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#prod-brochure" data-toggle="tab" title="Click here for {$product_name} product brochure">Product brochure</a></li>{/if}
								{if $productspec_cad neq ""}<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#cad" data-toggle="tab" title="Click here to download the {$product_name} CAD file">Download CAD file</a></li>{/if}
								{if $productspec_warranty neq ""}<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#warranty" data-toggle="tab" title="Click here for {$product_name} warranty information">Warranty information</a></li>{/if}
								<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#availability" data-toggle="tab" title="Click here to see if {$product_name} is available at a store near you">Availability</a></li>
								{if $attribute}<li class="{if $firsttab eq 1}active{assign var=firsttab value=0}{/if}"><a href="#variants" data-toggle="tab" title="Click here for {$product_name} variants">Variants</a></li>{/if}
							</ul>

							<div id="prodtabsContent" class="tab-content responsive">
                {assign var=firsttab value=1}
								{if $productspec_techinfo neq ""}<div id="tech-info" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
									{$productspec_techinfo}
								</div>{/if}

								{if $productspec_brochure neq ""}<div id="prod-brochure" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
									{$productspec_brochure}
								</div>{/if}

								{if $productspec_cad neq ""}<div id="cad" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
									{$productspec_cad}
								</div>{/if}

								{if $productspec_warranty neq ""}<div id="warranty" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
									{$productspec_warranty}
								</div>{/if}

								<div id="availability" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
								  {if count($stores) > 0}
									{foreach $stores as $st}
									<div id="{$st.listing_object_id}" data-availability="{$st.availability_flag}" class="availability productstore"><span class="bold">{$st.listing_name}</span><br/>{if $st.location_street neq "" && $st.location_suburb neq ""}{$st.location_street}, {$st.location_suburb}<br/>{/if}{if $st.location_phone neq ""}<a href="tel:{$st.location_phone}">{$st.location_phone}</a><br/>{/if}{if $st.availability_flag eq 1}<span class="bold instock">PRODUCT AVAILABLE</span> {if $product_id}<a onclick="modalEstimate({$product_id},'estimate','addestimate');" href="javascript:void(0);">Add to estimate</a>{/if}{else}<span class="bold outstock">PRODUCT NOT AVAILABLE</span> {if $product_id}<a data-target="#needhelp" data-toggle="modal" href="#needhelp">Request product</a>{/if}{/if}</div>
									{/foreach}
									{else}
									<div class="bold">No store information available for this product</div>
									{/if}
								</div>
								{if $attribute}<div id="variants" class="tab-pane {if $firsttab eq 1}active{assign var=firsttab value=0}{/if}">
									{foreach $attribute as $att}
										<b>{$att.attribute_name}:</b>
										<ul>
										{foreach $att.attr_value as $av}
											<li>{$av.attr_value_name}</li>
										{/foreach}
										</ul>
									{/foreach}
								</div>{/if}
							</div>
						</div>
					</div>
					{/if}
				</div>
			</div>
			
				</div>
	</div>
	{if $productspec_associate1 OR $productspec_associate2 OR $productspec_associate3 OR $productspec_video1 neq "" OR $productspec_video2 neq "" OR $productspec_video3 neq ""}
	<div id="related">
          <div class="container">
            {if $associate1 OR $associate2 OR $associate3}
            <div class="row">
              <div class="col-sm-12">
                <h1>Related products and services</h1>
              </div>
              {if $associate1}
              <div class="col-sm-4">
                {if $associate1.gallery_link}<img src="{$associate1.gallery_link}" alt="{$associate1.gallery_alt_tag}" title="{$associate1.gallery_title}" class="img-responsive" />{/if}
                <h5>{$associate1.product_name}</h5>
                {$associate1.product_short_description}
                <div class="view">
                  <a href="/diy/{$associate1.cache_url}" title="Click here to view {$associate1.product_name}">view product ></a>
                </div>
              </div>
							{/if}
              {if $associate2}
              <div class="col-sm-4">
                {if $associate2.gallery_link}<img src="{$associate2.gallery_link}" alt="{$associate2.gallery_alt_tag}" title="{$associate2.gallery_title}" class="img-responsive" />{/if}
                <h5>{$associate2.product_name}</h5>
                {$associate2.product_short_description}
                <div class="view">
                  <a href="/diy/{$associate2.cache_url}" title="Click here to view {$associate2.product_name}">view product ></a>
                </div>
              </div>
							{/if}
							{if $associate3}
              <div class="col-sm-4">
                {if $associate3.gallery_link}<img src="{$associate3.gallery_link}" alt="{$associate3.gallery_alt_tag}" title="{$associate3.gallery_title}" class="img-responsive" />{/if}
                <h5>{$associate3.product_name}</h5>
                {$associate3.product_short_description}
                <div class="view">
                  <a href="/diy/{$associate3.cache_url}" title="Click here to view {$associate3.product_name}">view product ></a>
                </div>
              </div>
							{/if}
            </div>
            {/if}

            {if $productspec_video1 neq "" OR $productspec_video2 neq "" OR $productspec_video3 neq ""}
            <div class="row">
              <div class="col-sm-12">
                <h1 class="latestvid hidden-xs">How to videos</h1>
                <!-- <div class="smalltxt">
                  <a href="#">view YouTube channel > </a>
                </div> -->
              </div>
              {if $productspec_video1 neq ""}
              <div class="col-sm-4 video">
              <iframe class="hidden-xs" id="video" width="100%" min-height="208px" height="100%" src="//www.youtube.com/embed/{$productspec_video1}?rel=0&vmode=transparent&html5=1" frameborder="0" allowfullscreen></iframe>
              </div>
              {/if}
              {if $productspec_video2 neq ""}
              <div class="col-sm-4 video">
              <iframe class="hidden-xs" id="video" width="100%" min-height="208px" height="100%" src="//www.youtube.com/embed/{$productspec_video2}?rel=0&vmode=transparent&html5=1" frameborder="0" allowfullscreen></iframe>
              </div>
              {/if}
              {if $productspec_video3 neq ""}
              <div class="col-sm-4 video">
              <iframe class="hidden-xs" id="video" width="100%" min-height="208px" height="100%" src="//www.youtube.com/embed/{$productspec_video3}?rel=0&vmode=transparent&html5=1" frameborder="0" allowfullscreen></iframe>
              </div>
              {/if}
            </div>
            {/if}
          </div>
        </div>
        {/if}
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){ $('#postcode-help').tooltip(); });
function checkPostcodeAvailability(obj){
  $('#availablilitylist').html("");
  $.ajax({
     type: "POST",
     url: "/diy/process/neareststockist",
     cache: false,
     data: 'action=neareststockistlist&postcode='+ $(obj).val()+'&formToken={$token}&product_id={$product_id}&product_object_id={$product_object_id}',
     dataType: "json",
     success: function(data) {
       try{
         if(data.template != "" && data.error != 1){
          $('#availablilitylist').html(data.template);
          ga('send', 'event', 'product availability by postcode', '{$product_name}', $(obj).val());
         }
       }catch(err){}
     },
     error: function(){
     }
   });
}
</script>

{/block}