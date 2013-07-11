{block name=body}
<div class="row-fluid ">
	<div class="span12">
		<table class="table table-bordered table-striped table-hover">
			    <thead>
			      <tr>
			        <th>Page</th>
			        <th colspan="3" ><a href="/admin/edit/page" class='btn btn-small btn-success right'><i class="icon-plus icon-white"></i> ADD NEW</a></th>
			      </tr>
			    </thead>
			    <tbody>
			    {counter start=0 skip=1 assign="count"}
			    {foreach item=li from=$list}
					{if	$li.category_id eq 0 or $li.category_id eq ""}
						{include file='list_page_recursive.tpl'}	
						{counter start=0 skip=1 assign="count"}
					{/if}
				{/foreach}
			    </tbody>
			  </table>
	</div>
</div>
{/block}