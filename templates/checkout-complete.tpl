{block name=head}
<style type="text/css">
</style>
<script type="text/javascript">
{$ga_ecommerce}
</script>
{/block}

{block name=body}
<div id="maincont">
  <div class="container" id="prolist">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
	  		<h1>{spanify data=$listing_name}</h1>
      </div>
      <div class="col-sm-12 text-center" id="cattoptext">
	      <p>You're all done! Order number <span class="bold">{$orderNumber}</span> is being processed. An email confirmation and receipt has been generated and sent to your nominated email address.</p>
      	<br>
      	{$listing_content1}
      </div>
      <div class="col-sm-3 col-sm-offset-3 text-center">
				<button onclick="window.location.assign('/products')" style="width: 100%;" class="btn btn-blue" >View more products ></button>
			</div>
			<div class="col-sm-3 text-center">
				<button onclick="window.location.assign('/contact')" style="width: 100%;" class="btn btn-blue" >Contact us ></button>
			</div>
		</div> 
	</div>
</div>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}

{/block}

