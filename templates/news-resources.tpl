{block name=body}

<div id="newsgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
       

        <div class="row" id="newscont">
          {assign var='cnt' value=0} {foreach $data.2 as $da} {assign var='cnt' value=$cnt+1}
          <div class="col-sm-6 col-md-4 newsout" data-sort="{$cnt}" data-{$da.listing_schedule_start_date|date_format:"%B-%Y"}="1" data-all="1" {foreach $da.linkedcats as $cat}data-{$cat.news_category_value}="1"{/foreach}>
            <div class="newsres">
              <a href="{$REQUEST_URI}/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
              </a>
              <div class="newsrestext">
                <div class="date">{$da.listing_schedule_start_date|date_format:"%d %B %Y"}</div>
                <div class="h5">{$da.listing_name}</div>
                <div class="newstext">{$da.listing_content1}</div>
                <a href="{$REQUEST_URI}/{$da.listing_url}" class="readart"> Read article </a>
              </div>
            </div>
          </div>
          {/foreach}
        </div>
        <div class="row" id="hidden-newscont" style="display: none;"></div>

        {if $cnt > 3}
        <div class="row" id="seemore">
          <div class="col-sm-12 text-center" id="showmore">
            Show more
            <div class="showarr">
              <img src="/images/down.png" alt="">
            </div>
          </div>
        </div>
        {/if}

      </div>
    </div>
  </div>
</div>

<div id="newsvideo">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2 class="h5">Video</h2>
      </div>
    </div>
    <div class="row" id="newsvidcont">
      {assign var='cnt' value=0} {foreach $data.3 as $da} {assign var='cnt' value=$cnt+1}
      <div class="col-sm-6 col-md-4 newsvidout">
        <div class="newsres">
          <a href="{$REQUEST_URI}/{$da.listing_url}">
            <div class="newsvid">
              <img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
            </div>
          </a>
          <div class="newsrestext">
            <div class="h5">{$da.listing_name}</div>
            <a href="{$REQUEST_URI}/{$da.listing_url}" class="readart"> Watch video </a>
          </div>
          {if $da.listing_flag2}
          <div class="featured-news"></div>
          {/if}
        </div>
      </div>
      {/foreach}

    </div>

    {if $cnt > 3}
    <div class="row" id="morevid">
      <div class="col-sm-12 text-center" id="showvid">
        Show more
        <div class="showarr">
          <img src="/images/down.png" alt="">
        </div>
      </div>
    </div>
    {/if}
  </div>
</div>
{/block} 
{block name=tail}
<script type="text/javascript">
$(document).ready(function(){
});
</script>
{/block}
