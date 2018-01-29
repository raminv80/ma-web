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
			<div class="col-sm-7" id="newscontleft">
				<div class="row">
				{assign var='cnt' value=0}
				{foreach $data.2 as $da} {assign var='cnt' value=$cnt+1}
				<div class="col-sm-12 newsout"{if $cnt > 5} style="display:none;"{/if} data-sort="{$cnt}" data-{$da.listing_schedule_start_date|date_format:"%B-%Y"}="1" data-all="1" {foreach $da.linkedcats as $cat}data-{$cat.news_category_value}="1"{/foreach}>
					<div class="newsres">
						<div class="row">
							<div class="col-sm-6">
								<a href="{$REQUEST_URI}/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
								</a>
							</div>
							<div class="col-sm-6">
								<div class="newsrestext">
									<h3>{$da.listing_name}</h3>
									<div class="newsdate">{$da.news_start_date|date_format:"%d %B %Y"}</div>
									<div class="newstext">{$da.listing_content1}</div>
									<a href="{$REQUEST_URI}/{$da.listing_url}" class="readart">Read more ></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				{/foreach}
				</div>

				<div class="row" id="hidden-newscont" style="display: none;">
				</div>
				{if $cnt > 5}
					<div class="row" id="seemore">
						<div class="col-sm-12 text-center" id="showmore">
						<a href="javascript:void(0)" onclick="$(this).hide();$('.newsout:hidden').fadeIn('slow');" class="btn btn-red">
						Show more
						</a>
						</div>
					</div>
				{/if}
			</div>
			<div class="col-sm-4 col-sm-offset-1" id="contright">
				{if $data.5}
                <h3>Videos</h3>
				<div class="videobox">
					{$cnt = 0}
                    {foreach $data.5 as $vid}
                    <div class="row vidb{if $cnt gt 3} video-hidden{/if}" style="{if $cnt gt 3}display:none;{/if}">
						<div class="col-xs-5 col-sm-5">
							<a href="{$vid.listing_content1}" target="_blank" title="Click to view video">
							<img src="{$vid.listing_image}" class="img-responsive" alt="{$vid.listing_name} thumbnail" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="{$vid.listing_content1}" target="_blank" title="Click to view video">
							<h5>{$vid.listing_name}</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>{$vid.news_start_date|date_format:"%e %B %Y"}</div>
						</div>
					</div>
                    {$cnt = $cnt+1}
                    {/foreach}
                    {if $cnt gt 3}
					<div class="row vidb">
						<div class="col-sm-12">
							<a href="javascript:void(0)" onclick="$('.video-hidden').show('slow');$(this).hide();" class="red">See all ></a>
						</div>
					</div>
                    {/if}
				</div>
                {/if}
				{if $data.3}
				<h3>Newsletters</h3>
				<div class="newslbox">
                    {$cnt = 0}
					{foreach $data.3 as $nws}
                    <div class="row newsl{if $cnt gt 3} newsletter-hidden{/if}" style="{if $cnt gt 3}display:none;{/if}">
						<div class="col-sm-12">
							<a href="/{$listing_url}/{$nws.listing_url}" title="Click to read more"><h5>{$nws.listing_name}</h5></a>
							<div>{$nws.news_start_date|date_format:"%e %B %Y"}</div>
						</div>
					</div>
                    {$cnt = $cnt+1}
                    {/foreach}
                    {if $cnt gt 3}
                    <div class="row vidb">
                      <div class="col-sm-12">
                        <a href="javascript:void(0)" onclick="$('.newsletter-hidden').show('slow');$(this).hide();" class="red">See all ></a>
                      </div>
                    </div>
                    {/if}
				</div>
                {/if}
			</div>
		</div>
	</div>
</div>
{/block}
{block name=tail}
<script type="text/javascript">
$(document).ready(function(){
});
</script>
{/block}
