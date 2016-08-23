{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_name}</h1>
				<p>{$listing_content1}</p>
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
				<div class="col-sm-12 newsout" data-sort="{$cnt}" data-{$da.listing_schedule_start_date|date_format:"%B-%Y"}="1" data-all="1" {foreach $da.linkedcats as $cat}data-{$cat.news_category_value}="1"{/foreach}>
					<div class="newsres">
						<div class="row">
							<div class="col-sm-6">
								<a href="{$REQUEST_URI}/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/news-default.jpg{/if}?width=368&height=200&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
								</a>
							</div>
							<div class="col-sm-6">
								<div class="newsrestext">
									<h3>{$da.listing_name}</h3>
									<div class="newsdate">{$da.listing_schedule_start|date_format:"%d %B %Y"}</div>
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
				{if $cnt > 4}
					<div class="row" id="seemore">
						<div class="col-sm-12 text-center" id="showmore">
						Show more
							<img src="/images/down-arrow.png" alt="">
						</div>
					</div>
				{/if}
			</div>
			<div class="col-sm-4 col-sm-offset-1" id="contright">
				<h3>Videos</h3>

				<div class="videobox">
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-1.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-2.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-xs-5 col-sm-5">
							<a href="#">
							<img src="/images/newsdet-3.jpg" class="img-responsive" alt="Video1" />
							</a>
						</div>
						<div class="col-xs-7 col-sm-7">
							<a href="#">
							<h5>Paramedic Morgan's Incredible MedicAlert Story</h5>
							</a>
							<div class="red">Medicalert</div>
							<div>12 March 2016</div>
						</div>
					</div>
					<div class="row vidb">
						<div class="col-sm-12">
							<a href="#" class="red">See all ></a>
						</div>
					</div>
				</div>

				<h3>Newsletters</h3>
				<div class="newslbox">
					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#"><h5>Newsletter name</h5></a>
							<div>14 August 2016</div>
						</div>
					</div>

					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#"><h5>Newsletter name</h5></a>
							<div>14 July 2016</div>
						</div>
					</div>

					<div class="row newsl">
						<div class="col-sm-12">
							<a href="#" class="red">See all ></a>
						</div>
					</div>
				</div>
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
