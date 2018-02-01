{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_name}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>
<div id="newscont">
	<div class="container">
    	<div class="row">
			<div class="col-sm-12" id="landing-newsart">
				<div class="row">
				{assign var='cnt' value=0}
				{foreach $data.0 as $da} {assign var='cnt' value=$cnt+1} 
				  {if $da.listing_type_id == 2}
				    {assign var='url' value="{$REQUEST_URI}/{$da.listing_url}"}
				  {else}
				    {assign var='url' value="{$da.listing_content2}"}
				  {/if}
				<div class="col-sm-6 col-md-4 newsout" data-sort="{$cnt}" data-{$da.listing_schedule_start_date|date_format:"%B-%Y"}="1" data-all="1" {foreach $da.linkedcats as $cat}data-{$cat.news_category_value}="1"{/foreach}>
          <div class="newsres">
            <a href="{$url}">
              <!--<img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">-->
              <img data-original="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth newsimg" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAXAAAADICAAAAADUnOVPAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAHdElNRQfiAR8UBBNZv/EmAAABxUlEQVR42u3QQREAMAjAMPxbnQlcZA8aBb3OCzW/A65pONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HFpQ9+erKgZtxAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTAxLTMxVDIwOjA0OjE5LTA1OjAwuRoBNwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wMS0zMVQyMDowNDoxOS0wNTowMMhHuYsAAAAASUVORK5CYII=">
            </a>
            <div class="newsrestext">
              <div class="date">{$da.news_start_date|date_format:"%d %B %Y"}</div>
              <h3 style="min-height: 85px;">
                <a href="{$url}">{$da.listing_name}</a>
              </h3>
              <div class="newstext">
                <p>
                  {$da.listing_content1}
                </p>
              </div>
              <a href="{$url}" class="readart">{if $da.listing_type_id == 2}Read article{else}Watch video{/if}</a>
            </div>
          </div>
        </div>
				{/foreach}
				</div>

				<div class="row" id="hidden-newscont" style="display: none;">
				</div>
				
			</div>
		</div>
	</div>
</div>
{/block}
{block name=tail}
{printfile file='/includes/js/jquery.lazyload.min.js' type='script'}
<script type="text/javascript">
$(document).ready(function(){
  $('img.newsimg').lazyload({
    effect: "fadeIn",
        failure_limit: Math.max($('img.newsimg').length - 1, 0),
        event: "scroll click"
  });
});
</script>
{/block}
