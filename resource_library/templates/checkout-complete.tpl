{block name=head}
<style type="text/css">
</style>
<script type="text/javascript">
{$ga_ecommerce}
</script>
{/block}

{block name=body}
<div id="maincont">
  <div class="container">
    <div class="row">
      <div class="col-sm-12" id="breadcrumbs">
        {include file='breadcrumbs.tpl'} 
      </div>
      <div class="col-sm-12">
        <h3>{$listing_name}</h3>
        <p>You're all done! Order number <span class="bold">{$orderNumber}</span> is being processed. An email confirmation and receipt has been generated and sent to your nominated email address.</p>
      </div>
      <div class="col-sm-12">
      {$listing_content1}
      </div>

    </div>
  </div>
</div>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}

{/block}

