{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="text-center">
				<h1>{$listing_title}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="faqs">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
      {$addcats = []}{foreach $additionals as $ad}{if !$ad.additional_category|in_array:$addcats}{$addcats[] = $ad.additional_category}{/if}{/foreach}
	  {assign var='cnt' value=0}
      {foreach $addcats as $cat}
      {assign var='cnt' value=$cnt+1}

		<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="heading{$cnt}">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{$cnt}" aria-expanded="true" aria-controls="collapse{$cnt}">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title">{$cat}</div>
							</div>
						</a>
					</h4>
				</div>
				<div id="collapse{$cnt}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{$cnt}">
					<div class="panel-body">
			          {foreach $additionals as $ad}
			          {if $cat eq $ad.additional_category}
			            <div class="qa-wrapper">
			              <div class="question">{$ad.additional_name}</div>
			              <div class="answer">{$ad.additional_content1}</div>
			              <br>
			            </div>
			          {/if}
			          {/foreach}
					</div>
				</div>
		</div>
      {/foreach}
      </div>
    </div>
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript">
function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


</script>
{/block}
