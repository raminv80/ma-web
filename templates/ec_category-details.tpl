{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}

<div id="reminder">
	<div class="container">
		<div class="row">
			<div class="col-md-10">
				Don't wait until it's too late. <a class="link" href="#">Renew your annual MedicAlert membership</a> today and stay protected in a medical emergency.
			</div>
			<div class="col-md-2">
				<a href="#" class="btn btn-red pull-right">Renew now</a>
			</div>
		</div>
	</div>
</div>

<div id="ad">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 visible-xs visible-sm">
				<a href="#" class="breadcrumbs1" title="Back to collections">< Back to collections</a>
			</div>
			<div class="col-sm-12">
				<a href="#">
				<img src="/images/prod-women-ad.jpg" class="img-responsive hidden-xs" />
				<img src="/images/prod-women-admob.jpg" class="img-responsive visible-xs" />
				</a>
			</div>
			<div class="col-sm-12 visible-xs visible-sm text-center" id="mobcont">
				<h1>Shop <span>{$listing_name}</span></h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="prodcatdet">
  <div class="container">
    <div class="row">
	  <div class="col-sm-12">
		  <a href="#" class="breadcrumbs hidden-xs hidden-sm" title="Back to collections">< Back to collections</a>
	  </div>
      <div class="col-md-3" id="collection-left">
        <!-- COLLECTIONS -->
		<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="accordion1">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collections" aria-expanded="true" aria-controls="collections">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title">Collections</div>
							</div>
						</a>
					</h4>
				</div>
				<div id="collections" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collections">
					<div class="panel-body">
						{foreach $collections as $c}
						<div class="">
						<a href="/products/{$c.listing_url}" title="Click to view collection">{$c.listing_name}</a> ({$c.cnt} item{if $c.cnt gt 1}s{/if})
						</div>
						{/foreach}
					</div>
				</div>
		</div>

        <!-- FILTERS -->
		<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="accordion2">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#narrow" aria-expanded="true" aria-controls="narrow">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title"><span class="visible-xs visible-sm">Filter 279 products</span><span class="hidden-xs hidden-sm">Narrow your results</span></div>
							</div>
						</a>
					</h4>
				</div>
				<div id="narrow" class="panel-collapse collapse" role="tabpanel" aria-labelledby="narrow">
					<div class="panel-body">

						<!---Type-->
						<div class="panel-heading subhead" role="tab" id="accordion3">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#type" aria-expanded="true" aria-controls="narrow">
									<i class="more-less glyphicon glyphicon-plus"></i>
									<div class="head-text">
										<div class="head-title">Type</div>
									</div>
								</a>
							</h4>
						</div>
						<div id="type" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="type">
							<div class="panel-body">
						        {foreach $ptypes as $k => $v}
						          {if $v.cnt gt 0} {/if}
						          <div class="">
						            <input type="checkbox" id="ptype{$k}" name="ptype[]" value="{$k}"> <label for="ptype{$k}">{$v.name}</label> ({$v.cnt} item{if $v.cnt gt 1}s{/if})
						          </div>
						        {/foreach}
							</div>
						</div>

            <!---Material-->
            <div class="panel-heading subhead" role="tab" id="accordion4">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#material" aria-expanded="true" aria-controls="narrow">
                  <i class="more-less glyphicon glyphicon-plus"></i>
                  <div class="head-text">
                    <div class="head-title">Material</div>
                  </div>
                </a>
              </h4>
            </div>
            <div id="material" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="material">
              <div class="panel-body">
                    {foreach $pmaterials as $k => $v}
                      {if $v.cnt gt 0} {/if}
                      <div class="">
                        <input type="checkbox" id="pmaterial{$k}" name="pmaterial[]" value="{$k}"> <label for="material{$k}">{$v.name}</label> ({$v.cnt} item{if $v.cnt gt 1}s{/if})
                      </div>
                    {/foreach}
              </div>
            </div>

            <!---Colour-->
            <div class="panel-heading subhead" role="tab" id="accordion5">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#colour" aria-expanded="true" aria-controls="narrow">
                  <i class="more-less glyphicon glyphicon-plus"></i>
                  <div class="head-text">
                    <div class="head-title">Colour</div>
                  </div>
                </a>
              </h4>
            </div>
            <div id="colour" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="colour">
              <div class="panel-body">
                    {foreach $attributes.2.values as $k => $v}
                      {if $v.cnt gt 0} {/if}
                      <div class="">
                        <input type="checkbox" id="colour{$k}" name="colour[]" value="{$k}"> <label for="colour{$k}">{$v.name}</label> ({$v.cnt} item{if $v.cnt gt 1}s{/if})
                      </div>
                    {/foreach}
              </div>
            </div>

            <!---Price-->
            <div class="panel-heading subhead" role="tab" id="accordion6">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#price" aria-expanded="true" aria-controls="narrow">
                  <i class="more-less glyphicon glyphicon-plus"></i>
                  <div class="head-text">
                    <div class="head-title">Price</div>
                  </div>
                </a>
              </h4>
            </div>
            <div id="price" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="price">
              <div class="panel-body">
                    {foreach $prices as $k => $v}
                      {if $v.cnt gt 0} {/if}
                      <div class="">
                        <input type="checkbox" id="price{$k}" name="price[]" value="{$v.value}"> <label for="price{$k}">{$v.name}</label> ({$v.cnt} item{if $v.cnt gt 1}s{/if})
                      </div>
                    {/foreach}
              </div>
            </div>
            
					</div>
				</div>
		</div>


        <h3></h3>

      </div>

      <div class="col-md-9">
        <!-- CATEGORY HEADER -->
 	    <div class="row hidden-xs hidden-sm">
          <div class="col-sm-4">
            <h1>Shop <span>{$listing_name}</span></h1>
            {$listing_content1}
          </div>
          <div class="col-sm-8">
            <img src="{$listing_image}" alt="{$listing_name} banner" class="img-responsive" />
          </div>
        </div>

        <!-- PRODUCTS -->
        <div class="row" id="products-head">
	        <div class="col-md-6 hidden-xs hidden-sm" id="prodcnt">
		        <span>98</span> Products
	        </div>
	        <div class="col-md-6 text-right">
		        <div class="sortlab">Sort by:</div>
		        <select id="sort">
			        <option value="">Please select</option>
			        <option value="price-low-high">Price (low to high)</option>
			        <option value="price-high-low">Price (hight to low)</option>
		        </select>
	        </div>
        </div>
        <div class="row" id="products-wrapper">
          {if count($products) > 0}
          {foreach $products as $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
          {else}
            <div class="col-sm-12">There are no products for this collection.</div>
          {/if}
        </div>
      </div>
    </div>
  </div>
</div>


<div id="recent">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Recently viewed items</h2>
      </div>
    </div>

    <div class="row">
      <div id="popslide" class="flexslider">
        <ul class="slides">
          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop2.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop3.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop4.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop2.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>

<div id="categ-bot" class="hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-sm-7">
        <h2>More than just medical identification jewellery</h2>
        <p>When you purchase a genuine MedicAlert medical ID, you also invest in a vital MedicAlert membership. This gives you access to a range of exclusive benefits.</p>
        <a href="#" class="btn btn-red">Learn more</a>
      </div>
    </div>
  </div>
</div>

<div id="categ-bot-mob" class="visible-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        <a href="#">More than just medical identification jewellery <span>+</span></a>
      </div>
    </div>
  </div>
</div>

{/block} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
	function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-collapse').on('hidden.bs.collapse', toggleIcon);
    $('.panel-collapse').on('shown.bs.collapse', toggleIcon);

  $(document).ready(function() {
    $("#showall").click(function() {
      $(this).hide();
      $("#product-list #categorycontainer .prodcatout").css("display", "block !important");
    });

    $("select").selectBoxIt();

    if($(window).width() >992){
		$('.collapse').each(function (index) {
		    $(this).collapse("toggle");
		});
	}
  });

  (function() {

    // store the slider in a local variable
    var $window = $(window), flexslider;

    // tiny helper function to add breakpoints
    function getGridSize() {
      return (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 4 : 6;
    }

    $(function() {
      SyntaxHighlighter.all();
    });


    $window.load(function() {
      $('.flexslider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 20,
        minItems: getGridSize(), // use function to pull in initial value
        maxItems: getGridSize()
      // use function to pull in initial value
      });
    });

    // check grid size on resize event
    $window.resize(function() {
      var gridSize = getGridSize();

      flexslider.vars.minItems = gridSize;
      flexslider.vars.maxItems = gridSize;
    });
  }());
</script>
{/block}

