{block name=body}
<div id="breadcrumbs" class="pagetop">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{include file='breadcrumbs.tpl'}
			</div>
		</div>
	</div>
</div>

<div id="news">
	<div class="container">
		<div class="row">
			<div class="text-center">
				<h1 class="h3">{$listing_name}</h1>
				<p class="date">{$news_start_date|date_format:"%e %B %Y"}</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{$listing_content2}
			</div>
		</div>
	</div>
</div>
{/block}