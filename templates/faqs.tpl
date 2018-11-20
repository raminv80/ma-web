{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
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
	  <div id="accordion">
	  {$addcats = ['Membership and service questions', 'Product questions', 'Medical questions', 'Other questions']}  
      {assign var='cnt' value=0}
      {foreach $addcats as $cat}
      {assign var='cnt' value=$cnt+1}
			<h3>
				<div class="head-text">
					<div class="head-title">{$cat}</div>
				</div>
			</h3>
			<div>
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
      {/foreach}
	  </div>
      </div>
    </div>
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript" src="/node_modules/jquery-ui-dist/jquery-ui.min.js"></script>
<script>
  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true,
      activate: function( event, ui ) {
        if(!$.isEmptyObject(ui.newHeader.offset()) && !isScrolledIntoView(ui.newHeader)) {
            $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top }, 'slow');
        }
    }
    });
  } );
</script>
{/block}
