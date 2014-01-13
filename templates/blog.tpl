{block name=head}
<style type="text/css">
#headbanner {
    background: url("{if $listing_image}{$listing_image}{else}/images/header3.jpg{/if}") no-repeat scroll center top / cover rgba(0, 0, 0, 0);
    height: 345px;
    margin-bottom: 15px;
}
</style>
{/block}

{block name=body}
{* Define the function *}
{function name=render_blogs parentclass="" parenturl="" count="0"}
		{foreach $items as $l}
           {assign var='count' value=$count+1}
          		<div class="row blogpost">
					<div class="col-sm-12">
						<div class="blogtop">
							<h3><a href="{$parenturl}/{$l.listing_url}">{$l.listing_title}</a></h3>
							<div class="dateb">{$l.news_start_date|date_format:"%e %B %Y"}</div>
						</div>
						{if $l.listing_image}<img src="{$l.listing_image}" class="img-responsive wide" alt="{$l.listing_title}" />{/if}
						<p>{trimwords data=$l.listing_content1|strip_tags maxwords=50}<a href="{$parenturl}/{$l.listing_url}" title="Read full story"> Read full story</a></p>
					</div>
				</div>
		{/foreach}
{/function}



<div id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{if $listing_content3}
					{if $listing_content5}
						<a target="_blank" href="{$listing_content5}"><img src="{$listing_content3}" class="img-responsive ad-banner" alt="{$listing_title} ad-banner" /></a>
					{else}
						<img src="{$listing_content3}" class="img-responsive ad-banner" alt="{$listing_title} ad-banner" />
					{/if}
				{/if}
			</div>
		</div>
		
		<div class="row" id="blogcontent">
			<div id="blogmain" class="col-sm-9">
				{call name=render_blogs items=$data parenturl="/"|cat:$listing_url}
			</div>
			
			{include file='archive-menu.tpl'}
			
		</div>

	</div>
</div>

{/block}
