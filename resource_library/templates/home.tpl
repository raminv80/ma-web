{block name=head}
<style type="text/css">
#headbanner {
	background: url("{if $listing_image}{$listing_image}{else}/images/headerhome.jpg{/if}") no-repeat scroll center top / cover rgba(0, 0, 0, 0);
    height: 420px;
    margin-bottom: 30px;
}
</style>
{/block}

{block name=body}
<div id="main">
	<div id="mainin" class="container">
		<div class="row">
			<div class="col-sm-6">
			<h1>{$listing_name}</h1>
				<div class="row" id="upcoming">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-12">
								
								<div class="right"><a href="/punters-corner/meetings" title="See More">See More</a></div>
							</div>
						</div>
						<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						  <!-- Indicators -->
						  <ol class="carousel-indicators">
						    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
						    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
						  </ol>
						
						  <!-- Wrapper for slides -->
						  <div class="carousel-inner">
						    <div class="item active">
						      <div>
						        item1
						      </div>
						    </div>
						    <div class="item">
						      <div>
						        item2
						      </div>
						    </div>
						    ...
						  </div>
						
						  <!-- Controls -->
						  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
						    <span class="glyphicon glyphicon-chevron-left"></span>
						  </a>
						  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
						    <span class="glyphicon glyphicon-chevron-right"></span>
						  </a>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12 pagetxt">
			{$listing_content1}
			{if $listing_content2}
			<iframe class="visible-xs" id="video" width="507" height="380" src="{$listing_content2}" frameborder="0" allowfullscreen></iframe>
			{/if}
			</div>
		</div>
		
		<div class="row">
			<a href="/new-to-wagering/how-to-bet" title="Read More">
			<div id="newto" class="col-sm-12">
			New to wagering? Find out what you need to know
			
			<div class="readmore">Read more</div>
			</div>
			</a>
		</div>

		
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				{if $listing_content4}
					{if $listing_content5}
						<a target="_blank" href="{$listing_content5}"><img src="{$listing_content4}" class="img-responsive ad-banner" alt="Bottom ad-banner" /></a>
					{else}
						<img src="{$listing_content4}" class="img-responsive ad-banner" alt="Bottom ad-banner" />
					{/if}
				{/if}
			</div>
		</div>
		
	</div>
</div>
{/block}
