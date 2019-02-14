{block name=body}
<div class="row ">
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th colspan="5">{$zone|upper} <a href="/admin/edit/{$path}" class="btn btn-small btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add New</a></th>
      </tr>
    </thead>
    <tbody>
      {foreach $list as $item}
      <tr {if $item.product_published neq '1'}class="draft"{/if}>
        <td><b>{if $item.url}{$item.title}{else}{$item.title|upper}{/if} </b>{if $item.product_published neq '1'}<small>| draft</small>{/if}
        </td>
        <td>{foreach $item.variants as $va}<div><small>{$va.variant_uid}</small></div>{/foreach}
        </td>
        <td><a href="{if $item.product_published neq '1'}/draft{/if}/{$item.product_url}" target="_blank" class="btn btn-small btn-info pull-right"><span class="glyphicon glyphicon-eye-open"></span> View</a>
        </td>
        <td>{if $item.url} <a href="{$item.url}" class="btn btn-small btn-warning pull-right"><span class="glyphicon glyphicon-pencil"></span> Edit</a> {/if}
        </td>
        <td>{if $item.url_delete} <a href="{$item.url_delete}" onclick="return ConfirmDelete();" class="btn btn-small btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Delete</a> {/if}
        </td>
      </tr>
      {/foreach}
    </tbody>
  </table>
</div>
{/block}
