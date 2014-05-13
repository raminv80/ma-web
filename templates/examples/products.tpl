{block name=head}
<style type="text/css">
#bannerout {
  background: url("{if $listing_type_id eq 2 && $listing_content4}{$listing_content4}{elseif $listing_type_id neq 2 && $listing_image}{$listing_image}{else}/images/slider1.jpg{/if}") no-repeat;
}
</style>
<link href="/includes/css/jquery.navgoco.css" rel="stylesheet">
<link href="/includes/css/bootstrap-touch-carousel.css" rel="stylesheet">
<script src="/includes/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="/includes/js/jquery.navgoco.min.js"></script>
<script src="/includes/js/bootstrap-touch-carousel.js"></script>
{/block}

{block name="body"}
{function name=render_menu level=0 parenturl=''} 
  {foreach from=$items key=k item=it}
    <li {if count($it.listings) > 0 && $it.selected eq 1}class="open"{/if}>{if count($it.listings) > 0}<a href="{$parenturl}/{$it.listing_url}" title="view {$it.listing_name} products">{$it.listing_name}</a>{else}<a href="{$parenturl}/{$it.listing_url}" title="view {$it.listing_name} products">{$it.listing_name}</a>{/if}
    {if count($it.listings) > 0}
      <ul>
      {if count($it.listings) > 0}<li><a href="{$parenturl}/{$it.listing_url}" title="view all {$it.listing_name} products">VIEW ALL</a></li>{/if}
        {call name=render_menu items=$it.listings level=$level+1 parenturl=$parenturl|cat:"/"|cat:$it.listing_url menu=$menu}
      </ul>
    {/if}
  {/foreach}
{/function}

{function name=render_categories level=0 parenturl=''} 
  {foreach from=$items key=k item=it}
    <div class="stitem prod">
      <a href="{$parenturl}/{$it.listing_url}" title="Click here to view {$it.listing_name}">
      {if $it.listing_image}
      <img class="img-responsive" src="{$it.listing_image}"/>
      {else}
      <img class="img-responsive" src="/images/no-image-available.png">
      {/if}
      </a>
			<h3><a href="{$parenturl}/{$it.listing_url}" title="Click here to view {$it.listing_name}">{$it.listing_name}</a></h3>
			{$it.listing_content1}
			<div class="view">
				<a href="{$parenturl}/{$it.listing_url}" title="Click here to view {$it.listing_name}">view ></a>
			</div>
		</div> <!--end stitem --> 
	{/foreach}
{/function}
{function name=render_products level=0 parenturl=''} 
  {foreach from=$items key=k item=it}
    <div class="stitem prod">
      <div id="prod{$it.listing_id}" class="carousel slide" data-ride="carousel" data-interval="false">
        <div class="carousel-inner">
          {if count($it.gallery) > 0}
          {assign var='first' value=1}
          {foreach from=$it.gallery key=k item=gl}
          {if $gl.gallery_link neq ""}
          <div class="item{if $first eq 1} active{assign var='first' value=0}{/if}">
            <img src="{$gl.gallery_link}" alt="{$gl.gallery_alt_tag}" title="{$gl.gallery_title}">
          </div>
          {/if}
          {/foreach}
          {else}
            <div class="item active">
              <img src="/images/no-image-available.png">
            </div>
          {/if}
        </div>
  
        <!-- Controls -->
        <a class="left carousel-control" href="#prod1" data-slide="prev"> <img src="/images/catarrleft.png" align="Previous" /></a> 
        <a class="right carousel-control" href="#prod1" data-slide="next"> <img src="/images/catarrright.png" align="Next" /> </a>
      </div>
      <h3><a href="{$parenturl}/{$it.product_url}" title="Click here to view {$it.product_name}">{$it.product_name}</a></h3>
       {$it.product_short_description}
      
      {if $it.productspec_estimate eq 1 OR $it.productspec_favourite eq 1}<div class="view"><a href="{$parenturl}/{$it.product_url}" title="Click here to view {$it.product_name}">view product ></a></div>
      {if $it.productspec_estimate eq 1}
      	<div class="view"><a href="javascript:void(0);" onclick="modalEstimate({$it.product_object_id},'estimate','addestimate');" title="Click here to add {$it.product_name} to your estimate">add to estimate +</a></div>
      {/if}
      {if $it.productspec_favourite eq 1}
      	<div class="view"><a href="javascript:void(0);" onclick="addFavourite({$it.product_object_id},'{$it.product_name}');" title="Click here to add {$it.product_name} to your favourites">add to favourites *</a></div>
    	{/if}{/if}
    	
    </div><!--end stitem -->
  {/foreach}
{/function}

<div id="maincont2">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 breadcrumbs">
				{include file='breadcrumbs.tpl'}
			</div>
			<div class="col-sm-12">
				<div class="row">
					<h1 class="title">{$listing_name}</h1>
          {$listing_content1}
				</div>
			</div>

			<div class="col-sm-12 highlight">
				<a href="#needhelp" data-toggle="modal" data-target="#needhelp"> <img src="/images/flag.png" alt="" /> Need help with your next project? We're here to help!
				</a>
			</div>

			<div class="col-sm-3 sidebar">
				<div class="row">
					<h4>Product navigation</h4>
					<div id="navop">click to view product categories</div>
					<ul id="prodnav">
					  {call name=render_menu items=$categories parenturl="/diy/products"}
					</ul>
				</div>

				<div class="row hidden-xs">
					<div class="col-sm-12 menu2item">
						<a href="/diy/find-a-store" title="Click here to find a Steeline store near you"> <img src="/images/findstore.png"/ title="Click here to find a Steeline store near you" alt="Click here to find a Steeline store near you"> Find a store
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
						<a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get help from a Steeline store near you"> <img src="/images/needhelp.png" title="Click here to get help from a Steeline store near you" alt="Click here to get help from a Steeline store near you" /> Need help?
						</a>
					</div>
				</div>
			</div>
			<div class="col-sm-9" id="prodcont">
				<div class="row">
          {call name=render_categories items=$data.listings parenturl=$REQUEST_URI}
				</div>
				
				{foreach from=$data.listings  key=k item=it}
				{if count($it.products) > 0}
				<div class="row" id="prodcontprod">
          <div class="col-sm-12">
            <h2>{$listing_name} - <span>{$it.listing_name}</span></h2>
          </div>
        </div>
        
        <div class="row">
        {call name=render_products items=$it.products parenturl=$REQUEST_URI|cat:"/"|cat:$it.listing_url}
        </div>
        {/if}
        {/foreach}
        
        {if count($data.products) > 0}
        <div class="row" id="prodcontprod">
          <div class="col-sm-12">
            <h2>{$it.listing_name}</h2>
          </div>
        </div>
        
        <div class="row">
        {call name=render_products items=$data.products parenturl=$REQUEST_URI}
        </div>
        {/if}
				
			</div>
			<div class="col-sm-12 highlight">
				<a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get help from a Steeline store near you"> <img src="/images/flag.png" title="Click here to get help from a Steeline store near you" alt="Click here to get help from a Steeline store near you" /> Need help with your next project? We're here to help!
				</a>
			</div>
		</div>
	</div>
</div>
{/block}