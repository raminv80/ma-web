{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="maincont">
  <div class="container" id="prolist">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
	  		<h1>{spanify data=$listing_name}</h1>
      </div>
      <div class="col-sm-8 col-sm-offset-2 text-center" id="cattoptext">
	      {$listing_content1}
      </div>
		</div> 
	</div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">

</script>
{/block}
