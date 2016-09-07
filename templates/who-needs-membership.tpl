{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="text-center">
				<h1>{$listing_title}</h1>
				<p>{$listing_content1}</p>
			</div>
		</div>
	</div>
</div>


<div id="who-needs">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
	  	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  	  {assign var='cnt' value=0}
          {foreach $additionals as $ad}
          {assign var='cnt' value=$cnt+1}

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="heading{$cnt}">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{$cnt}" aria-expanded="true" aria-controls="collapse{$cnt}">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-img">
							<img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-who-needs-icon.png{/if}" alt="{$ad.additional_name} icon">
							</div>
							<div class="head-text">
								<div class="head-title">{$ad.additional_name}</div>
								<div class="shortdesc">{$ad.additional_description}</div>
							</div>
						</a>
					</h4>
				</div>
				<div id="collapse{$cnt}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{$cnt}">
					<div class="panel-body">
						  {$ad.additional_content1}
					</div>
				</div>
			</div>
          {/foreach}
      </div>
    </div>
  </div>

    <div class="row">
	    <div class="col-sm-12 text-center">
		    <br /><br />
			{$listing_content2}
			<br />
			<a href="#" class="btn btn-red">Join now</a>
	    </div>
    </div>
  </div>
</div>

<div id="testimonial-list" class="who-needs">
  <div class="container">
    <div class="row" id="testcont">
	  <div class="text-center">
	  <h3>The voice for people living with medical conditions</h3>
	  <p>MedicAlert membership has given thousands of Australians peace of mind knowing that they’re protected in a medical emergency.<br />
		  <a href="/testimonials">View our members’ stories here ></a></p>
	  </div>

	  <div id="tests" class="hidden-xs">
      {foreach $testimonials as $da}
      <div class="col-sm-6 col-md-4 testresout">
        <div class="testres text-center">
          <a href="/testimonials/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/testimonial-noimg.png{/if}?width=96&height=96&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
          </a>
          <div class="testrestext">
            <div class="h5">{$da.listing_name}</div>
            <div class="testloc">{$da.listing_content1}</div>
            <div class="testtext">{$da.listing_content2|truncate:90:"..."}</div>
            <a href="/testimonials/{$da.listing_url}" class="readart"> Read my story ></a>
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
