{block name=head}
<style type="text/css">
#headbanner {
    background: url("{if $listing_parent.listing_image}{$listing_parent.listing_image}{else}/images/header3.jpg{/if}") no-repeat scroll center top / cover rgba(0, 0, 0, 0);
    height: 345px;
    margin-bottom: 15px;
}
</style>
{/block}

{block name=body}
<div id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{if $listing_parent.listing_content3}
					{if $listing_parent.listing_content5}
						<a target="_blank" href="{$listing_parent.listing_content5}"><img src="{$listing_parent.listing_content3}" class="img-responsive ad-banner" alt="{$listing_parent.listing_title} ad-banner" /></a>
					{else}
						<img src="{$listing_parent.listing_content3}" class="img-responsive ad-banner" alt="{$listing_parent.listing_title} ad-banner" />
					{/if}
				{/if}
			</div>
		</div>
		<div class="row" id="blogcontent">
			<div id="blogmain" class="col-sm-9">
				<div class="row blogpost">
					<div class="col-sm-12">
						<div class="blogtop">
							<h3>{$listing_title}</h3>
							<div class="dateb">{$news_start_date|date_format:"%e %B %Y"}</div>
						</div>
						{if $listing_image}<img src="{$listing_image}" class="img-responsive wide" alt="{$listing_title} image" />{/if}
							{$listing_content1}
						<p><a href="/blog" title="Back"><button class="btn btnblue">< Back</button></a></p>
					</div>
				</div>
			</div>
			{include file='archive-menu.tpl'}
		</div>
	</div>
</div>

{/block}
