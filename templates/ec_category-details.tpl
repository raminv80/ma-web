{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}



<div id="">
  <div class="container" id="">
    <div class="row">
      <div class="col-md-3">
        <!-- COLLECTIONS -->
        <h3>Collections</h3>
        {foreach $collections as $c}
          <div class="">
            <a href="/products/{$c.listing_url}" title="Click to view collection">{$c.listing_name}</a> ({$c.cnt} item{if $c.cnt gt 1}s{/if})
          </div>
        {/foreach}
        
        <!-- FILTERS -->
        <h3>Narrow your results</h3>
        <h4>Type</h4>
        {foreach $ptypes as $k => $v}
          {if $v.cnt gt 0} {/if}
          <div class="">
            <input type="checkbox" id="ptype{$k}" name="ptype[]" value="{$k}"> <label for="ptype{$k}">{$v.name}</label> ({$v.cnt} item{if $v.cnt gt 1}s{/if})
          </div>
          
        {/foreach}
      </div>
      
      <div class="col-md-9">
        <!-- CATEGORY HEADER -->
 	    <div class="row" id="">
          <div class="col-sm-4">
            <h1>{$listing_name}</h1>
            <p>{$listing_content1}</p>
          </div>
          <div class="col-sm-8">
            <img src="{$listing_image}" alt="{$listing_name} banner" />
          </div>
        </div>
  
        <!-- PRODUCTS -->
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
        <h2>Recently view items</h2>
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
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#showall").click(function() {
      $(this).hide();
      $("#product-list #categorycontainer .prodcatout").css("display", "block !important");
    });
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

