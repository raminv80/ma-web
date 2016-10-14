{block name=body}

<div class="row">
  <div class="col-sm-12 edit-page-header">{$wishlist_user_id} - Wish list</div>
  <div class="col-sm-12">
    <table class="table table-bordered table-striped table-hover" style="margin-top: 20px;">
      <thead>
        <tr>
          <th>Date-time</th>
          <th>Product</th>
        </tr>
      </thead>
      <tbody>
        {foreach $products as $item}
        <tr>
          <td>{$item.wishlist_modified|date_format:"%d/%m/%Y %I:%M%p"}</td>
          <td><a href="/{$item.product_url}" target="_blank">{$item.product_name}</a></td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>
{/block}
