{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="abouthead" class="insurance-grps" style="background: url({if $listing_image}{$listing_image}{else}/uploads/banners/banner-home.jpg{/if}) no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <div class="h1">
          {$listing_name}<br />{$listing_title}
        </div>
      </div>

    </div>
  </div>
</div>

<div id="aboutmain">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-3">
        <div class="h3 col-xs-12">Filters</div>
        {if $results.pages OR $results.admin}
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="filter btn btn-blue" data-filter="all" onclick="$('.filter.active').removeClass('active');$(this).addClass('active');$('#seemoresearch').hide();$('.result').hide();$('.result[data-'+$(this).data('filter')+'=1]').slideDown('slow');">All</div>
        </div>
        {append var='exists' value='all' index=0} {assign var=count value=1} {foreach $results.admin as $item} {if in_array('agent',$exists)}{continue}{else}{append 'exists' 'agent' index=$count}{assign var=count value=$count+1}{/if}
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="filter btn btn-blue" data-filter="agent" onclick="$('.filter.active').removeClass('active');$(this).addClass('active');$('#seemoresearch').hide();$('.result').hide();$('.result[data-'+$(this).data('filter')+'=1]').slideDown('slow');">Agent</div>
        </div>
        {/foreach} {foreach $results.pages as $item} {if in_array($item.type_name,$exists)}{continue}{else}{append 'exists' $item.type_name index=$count}{assign var=count value=$count+1}{/if}
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="filter btn btn-blue" data-filter="{urlencode data=$item.type_name}" onclick="$('.filter.active').removeClass('active');$(this).addClass('active');$('#seemoresearch').hide();$('.result').hide();$('.result[data-'+$(this).data('filter')+'=1]').slideDown('slow');">{$item.type_name}</div>
        </div>
        {/foreach} {/if}
      </div>
      <div class="col-xs-12 col-md-9 searchresults">
        <h1 class="h3 col-xs-12">
          Results for <strong>'{$term}'</strong>:
        </h1>
        {if $results.pages OR $results.products} {assign var=count value=0} {assign var=page value=1} {foreach $results.products as $item} {assign var=count value=$count+1}
        <div class="col-xs-12 result page" data-all="1" data-agent="1">
          <div><img class="img-responsive" src="{$item.gallery.0.gallery_link}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}"></div>
          <div class="resulttitle">
            <a href="/{$item.cache_url}"><h2>{if $item.product_seo_title}{$item.product_seo_title}{else}{$item.product_name}{/if}</h2></a>
          </div>
          <div class="resultlink" style="font-style: italic; font-weight: normal;">
            <a href="/{$item.cache_url}">View details</a>
          </div>
        </div>
        {/foreach} {foreach $results.pages as $item} {assign var=count value=$count+1}
        <div class="col-xs-12 result page" data-all="1" data-{urlencode data=$item.type_name}="1">
          <div class="resulttitle">
            <a href="/{$item.cache_url}"><h2>{if $item.listing_seo_title}{$item.listing_seo_title}{else}{$item.listing_name}{/if}</h2></a>
          </div>
          <div class="resulttype">{$item.type_name}</div>
          <div class="resultdescription">{if $item.listing_meta_description}{$item.listing_meta_description}{elseif $item.type_id eq 5 && $item.listing_content3}{$item.listing_content3}{else $item.listing_content1}{striptrimwords data=$item.listing_content1 maxwords=30}{/if}</div>
          <div class="resultlink">
            <a href="/{$item.cache_url}">View details</a>
          </div>
        </div>
        {/foreach} {/if}
      </div>
    </div>
    {if $count > 9}
    <div class="row" id="seemoresearch">
      <div class="col-xs-12 col-md-9 col-md-offset-3 text-center">
        <div id="showmoresearch">Load more</div>
        {/if}
      </div>
    </div>
  </div>
</div>




{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script type="text/javascript">
  $("#seemoresearch #showmoresearch").click(function() {
    $(this).closest('#seemoresearch').hide();
    $(".searchresults .result").slideDown('slow');
  });
</script>
{/block}
