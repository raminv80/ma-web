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
				<div class="row" id="upcoming">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-12">
								<div class="uphead">Upcoming Meetings</div>
								<div class="right"><a href="/punters-corner/meetings" title="See More">See More</a></div>
							</div>
						</div>
						{foreach $meetings as $m}{if $m.meeting_id}
						<div class="row meet">
							<div class="col-sm-12">
								<div class="row">
									<div class="date col-xs-4 col-sm-3 col-md-3">
										<div class="top">{$m.meeting_date|date_format:"%a"}</div>
										<div class="bot">{$m.meeting_date|date_format:"%e %b"}</div>
									</div>
									<div class="col-xs-7 col-sm-4 col-md-5 meettext">
										<h5>{$m.meeting_title}</h5>
										First Race, {$m.race_start_time|date_format:"%l:%M %P"}<br/>
										<a href="/punters-corner/meetings/id{$m.meeting_id}">View Form</a>
										<a class="tip" data-toggle="tooltip" title="The form gives you all the information you need to know about planned races and projected outcomes"><img src="/images/question.png" alt="Question mark" /></a>
									</div>
									<div class="hidden-xs col-sm-5 col-md-4">
										{if $m.meeting_bet_now_url}
											<a target="_blank" href="{$m.meeting_bet_now_url}" title="Bet Now"><button class="btn btnblue betnow-btn">Bet now</button></a>
										{/if}
									</div>
								</div>
								<div class="row visible-xs">
									<div class="col-xs-12">
										{if $m.meeting_bet_now_url}
											<a target="_blank" href="{$m.meeting_bet_now_url}" title="Bet Now"><button class="btn btnblue betnow-btn">Bet now</button></a>
										{/if}
									</div>
								</div>
							</div>
						</div>
						{/if}{/foreach}
						
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				{if $listing_content2}
					<iframe class="hidden-xs" id="video" width="507" height="380" src="{$listing_content2}" frameborder="0" allowfullscreen></iframe>
				{/if}
				{if $listing_content3}
					{if $listing_content5}
						<a target="_blank" href="{$listing_content5}"><img id="topimg" src="{$listing_content3}" class="img-responsive ad-banner" alt="Top ad-banner" /></a>
					{else}
						<img id="topimg" src="{$listing_content3}" class="img-responsive ad-banner" alt="Top ad-banner" />
					{/if}
				{/if}
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
		{if count($blog) > 0}
		<div class="row" id="latest">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="uphead">Latest Blog Entries</div>
						<div class="right"><a href="/blog" title="See More">See More</a></div>	
					</div>
				</div>
				<div class="row entries">
					{foreach $blog as $b}
					<div class="col-sm-3">
						{if $b.listing_image}<img src="{$b.listing_image}" class="img-responsive" alt="{$b.listing_title} image" />{/if}
						<p class="bold">{$b.listing_title}</p>
						<p>{trimwords data=$b.listing_content1|strip_tags maxwords=25 maxchars=70}<a href="/blog/{$b.listing_url}" title="Read full story"> Read full story</a></p>
					</div>
					{/foreach}
				</div>		
			</div>
		</div>
		{/if}
		
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
