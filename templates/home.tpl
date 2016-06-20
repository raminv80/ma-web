{block name=body}
<div id="main">
	<div id="mainin" class="container">
		<div class="row">
			<div class="col-sm-12 text-center" id="mostpop">
				<h3>Our</h3>
				<h2>most popular</h2>
				<h3>products</h3>
			</div>
			<div class="col-sm-12" id="homeprods">
				<div class="row">
					{foreach $popular as $item}
					<div class="product">
						{include file='product_list_struct.tpl'}
					</div>
          {/foreach}

				</div>
				<div class="col-sm-12 text-center" id="more">
					<a href="/products">See more ></a>
				</div>
			</div>
		</div>

	</div>
</div>

<div id="match">
	<form id="productfilter" action="/filter" method="get">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="circle">
					<h3>Find your</h3>
					<h2>match</h2>
					<p>Discover the perfect ergonomic product to suit your needs</p>
				</div>
			</div>
			<div class="col-sm-6">
				<ul class="symptoms">
			{foreach $symptom  as $symp}
					<li><input type="checkbox" name="q[]" value="{$symp.value}" id="symp{$symp.value}" /> <label for="symp{$symp.value}">{$symp.value}</label></li>
			{/foreach}
				</ul>
			</div>
			<div class="col-sm-3">
					<button class="btn btn-blue" id="matchsearch" type="submit">Search now</button>
			</div>
		</div>
	</div>
	</form>
</div>
{/block}
