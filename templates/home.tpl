{block name=body}
		<div class="row-fluid">
		<div class="span16">
			{carousel field=$banner}
		</div>
	</div>
      
      <div class="row-fluid">
        <div class="span12">
          <div class="content-left toptext">
            {$content}
            
            <div class="row-fluid">
             <div class="span16">
               <h2>Latest news</h2>
             </div>
            
            <div class="row-fluid index-latest-news">
	            {foreach $news as $news_item}
						{include file='news-small-item.tpl'}	
				{/foreach}	
            </div>
            
            </div><!-- /.row-fluid -->
         </div><!-- /.content-left -->
        </div><!-- /.span12 -->

	 <div class="span4">
        	{image field=$image}
	</div>
        
        
      
      </div><!-- /.row-fluid -->

	<div class="row-fluid">
		<div class="span16">
		<div class="row-fluid mainfeat">
			<div class="span4  lightb">
				<img alt="product" src="images/product.png">

				<h3><a href="/products">Find a product</a></h3>
			</div>
			<div class="span4 darkb">
				<img alt="product" src="images/find.png">

				<h3><a href="/retailers">Find a retailer</a></h3>
			</div>
			<div class="span4  lightb1">
				<img alt="product" src="images/register.png">
				<h3><a href="/warranty-and-registration">Register your product</a></h3>
			</div>
			<div class="span4  darkb">
				<img alt="product" src="images/promotions.png">
				<h3><a href="/promotions">Promotions</a></h3>
			</div>
		</div>
		</div>
	</div>
{/block}