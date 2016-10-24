{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="search">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
      </div>
    </div>
  </div>
</div>

<div id="searchcont">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 searchresults">
	    <h1>Search</h1>
        <h3 class="h3">
          Search result for "{$term}"
        </h3>
      </div>
    </div>
    <div class="row">
	    <div class="col-xs-4 col-sm-9">
		    <div id="resno">
			    <span class="bold">{$count}</span> result{if $count gt 1}s{/if}
		    </div>
	    </div>
	    <div class="col-xs-8 col-sm-3 text-right">
		    <div>Filter:</div>
		    <select id="searchfilter" name="searchfilter">
			    <option value="result">All</option>
			    <option value="product">Products</option>
			    <option value="page">Pages</option>
		    </select>
	    </div>
    </div>
    <div class="row" id="resbox">
        {if $results.pages OR $results.products} {assign var=count value=0} {assign var=page value=1}
        {foreach $results.products as $item} {assign var=count value=$count+1}
        <div class="col-xs-12 result product" {if $count > 4}style="display:none"{/if} data-all="1" data-agent="1">
          <div><img class="img-responsive" src="{if $item.gallery.0.gallery_link}{$item.gallery.0.gallery_link}{else}/images/no-image-available.png{/if}?width=154&height=96&crop=1" alt="{$item.product_name}" title="{$item.product_name}"></div>
          <div class="resulttitle">
            <a href="/{$item.cache_url}"><h3>{$item.product_name}</h3></a>
          </div>
          <div class="resultlink">
            <a href="/{$item.cache_url}">View more</a>
          </div>
        </div>
        {/foreach}
		{if $count > 4}
	      <div class="col-xs-12 text-center result product">
	        <div class="showmoresearch"><a href="javascript:void(0)" data-type="product">Load more products</a></div>
	      </div>
	    {/if}
        {assign var=count value=0}
        {foreach $results.pages as $item} {assign var=count value=$count+1}
        <div class="col-xs-12 result page" {if $count > 4}style="display:none"{/if} data-all="1" data-{urlencode data=$item.type_name}="1">
          <div class="resulttitle">
            <a href="/{$item.cache_url}"><h3>{if $item.listing_seo_title}{$item.listing_seo_title}{else}{$item.listing_name}{/if}</h3></a>
          </div>
          <div class="resulttype">{$item.type_name}</div>
          <div class="resultdescription">{if $item.listing_meta_description}{$item.listing_meta_description}{elseif $item.type_id eq 5 && $item.listing_content3}{$item.listing_content3}{else $item.listing_content1}{striptrimwords data=$item.listing_content1 maxwords=30}{/if}</div>
          <div class="resultlink">
            <a href="/{$item.cache_url}">View more</a>
          </div>
        </div>
        {/foreach} {/if}
		{if $count > 4}
	      <div class="col-xs-12 text-center result page">
	        <div class="showmoresearch"><a href="javascript:void(0)" data-type="page">Load more pages</a></div>
	      </div>
	    {/if}
      </div>
  </div>
</div>




{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
	  $(".showmoresearch").click(function(){
	    $('.result.'+ $(this).find('a').attr('data-type') +':hidden').fadeIn();
	    $(this).closest('.result').hide();
	});

	$("select").selectBoxIt();

  	$( "select" ).change(function () {
			var str = "";
			str=$( "select option:selected" ).val();
			$("#resbox .result").hide();
			$("#resbox").find("."+str).show();
	});

</script>
{/block}
