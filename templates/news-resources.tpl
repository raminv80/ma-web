{block name=head}
{printfile file='/includes/css/ekko-lightbox.css' type='style'}
{/block}
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
<div id="newscont1">
	<div class="container">
  	  <div class="row" id="filter">
					<div class="col-sm-3 col-sm-offset-2 text-left">
						<label for="archive">Date</label>
						<select id="archive" name="archive">
						<option value="all">ALL</option>
            {assign var='cnt' value=0}{assign var='year' value=""}{assign var='yearname' value=""}
            {foreach $data.0 as $da}
							{if $year eq ""}{assign var='year' value=$da.news_start_date|date_format:"%Y"}{assign var='year' value=$da.news_start_date|date_format:"%Y"}{/if}
              {if $year neq $da.news_start_date|date_format:"%Y"}<option value="{$year}">{$year} ({$cnt})</option>
                {assign var='year' value=$da.news_start_date|date_format:"%Y"}{assign var='yearname' value=$da.news_start_date|date_format:"%Y"}{assign var='cnt' value=0}
              {/if}
              {assign var='cnt' value=$cnt+1}
						{/foreach}
						<option value="{$year}">{$year} ({$cnt})</option>
						</select>
					</div>
					<div class="col-sm-3 text-left">
						<label for="category">Category</label>
						<select id="category" name="category">
							<option value="all">ALL</option>
							{foreach $news_categories as $cat}<option value="{$cat.id}">{$cat.value}</option>{/foreach}
						</select>
					</div>
					<div class="col-sm-2">
  					<button class="btn btn-red" onclick="$('#newscont').find('.newsout').appendTo('#hidden-newscont');$('#hidden-newscont').find('.newsout[data-'+$('#archive').val()+'=1][data-cat-'+$('#category').val()+'=1]').sort(
function(o1,o2) {
  var contentA = parseInt( $(o1).attr('data-sort') );
  var contentB = parseInt( $(o2).attr('data-sort') );
  return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
}
).appendTo('#newscont');">Search</button>
					</div>
				</div>

    	<div class="row">
			<div class="col-sm-12" id="landing-newsart">
				<div class="row" id="newscont">
				{assign var='cnt' value=0}
				{foreach $data.0 as $da} {assign var='cnt' value=$cnt+1}
				  {if $da.listing_type_id == 2}
				    {assign var='url' value="{$REQUEST_URI}/{$da.listing_url}"}
				  {else}
				    {assign var='url' value="{$da.listing_content2}"}
				  {/if}
				<div class="col-sm-6 col-md-4 newsout" data-sort="{$cnt}" data-{$da.news_start_date|date_format:"%Y"}="1" data-all="1" data-cat-all="1" {foreach $da.linkedcats as $cat}data-cat-{$cat.newscatlink_cat_id}="1"{/foreach}>
          <div class="newsres">
            <a href="{$url}" {if $da.listing_type_id neq 2}data-toggle="lightbox"{/if}>
              <!--<img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">-->
              <img data-original="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth newsimg" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAXAAAADICAAAAADUnOVPAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAHdElNRQfiAR8UBBNZv/EmAAABxUlEQVR42u3QQREAMAjAMPxbnQlcZA8aBb3OCzW/A65pONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HGo41HGs41nCs4VjDsYZjDccajjUcazjWcKzhWMOxhmMNxxqONRxrONZwrOFYw7GGYw3HFpQ9+erKgZtxAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTAxLTMxVDIwOjA0OjE5LTA1OjAwuRoBNwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wMS0zMVQyMDowNDoxOS0wNTowMMhHuYsAAAAASUVORK5CYII=">
            </a>
            <div class="newsrestext">
              <div class="date">{$da.news_start_date|date_format:"%d %B %Y"}</div>
              <h3 style="min-height: 85px;">
                <a href="{$url}" {if $da.listing_type_id neq 2}data-toggle="lightbox"{/if}>{$da.listing_name}</a>
              </h3>
              <div class="newstext">
                <p>
                  {$da.listing_content1}
                </p>
              </div>
              <a href="{$url}" class="readart" {if $da.listing_type_id neq 2}data-toggle="lightbox"{/if}>{if $da.listing_type_id == 2}Read more{else}View now{/if}</a>
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
{printfile file='/includes/js/jquery-ui.min.js' type='script'}
{printfile file='/includes/js/jquery.selectBoxIt.min.js' type='script'}
{printfile file='/includes/js/ekko-lightbox.min.js' type='script'}

<script type="text/javascript">
$(document).ready(function(){
  $('img.newsimg').lazyload({
    effect: "fadeIn",
        failure_limit: Math.max($('img.newsimg').length - 1, 0),
        event: "scroll click"
  });
  $('select').selectBoxIt();
});
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
</script>
{/block}
