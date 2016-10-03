{block name=form_linking_input}
{********** THIS TEMPLATE/FUNCTION REQUIRES THE FOLLOWING VARIABLES **********
	{assign var='data' value=$fields.options.products}
	{assign var='proccess_order' value='5'}
	{assign var='table_primary_key_field' value='product_id'}
	{assign var='table_primary_key_value' value=$fields.product_id}
	{assign var='linking_table' value='tbl_productassoc'}
	{assign var='linking_table_primary_key_field' value='productassoc_id'}
	{assign var='linking_table_link_field' value='productassoc_product_id'}
	{assign var='linking_table_associated_field' value='productassoc_product_object_id'}
	{assign var='linking_table_deleted_field' value='productassoc_deleted'}
	{assign var='selected_array' value=$fields.productassoc}
	{assign var='ignore_value' value='0'}
********************************************************************************}

{function name=function_linking_list data=$data order=$proccess_order dvalue=$table_primary_key_field dvalue_val=$table_primary_key_value
			table=$linking_table pkey=$linking_table_primary_key_field dfield=$linking_table_link_field
			skey=$linking_table_associated_field dltfield=$linking_table_deleted_field
			existingArr= $selected_array ignoreId=$ignore_value}

	{foreach $data as $opt}
		{if $opt.id eq $ignoreId}{continue}{/if}
		{assign var='count' value=$count+1}
		{if $count eq "" }
	  	{if $level eq 0}<input type="hidden" value="{$dvalue}" name="default[{$dfield}]" />{/if}
	  	{assign var=count value=1}
	  {else}
	  	{assign var=count value=$count+1}
	  {/if}
		{assign var='exists' value=0}
		{foreach $existingArr as $l}
			{if $l.$skey eq $opt.id}
      	{if $exists eq 1}{assign var='count' value=$count+1}
      		<input type="hidden" value="{$pkey}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][id]" id="id_{$table}_{$levelcount*10}{$count}"/>
      		<input type="hidden" value="{$l.$pkey}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$pkey}]" class="key" >
      		<input type="hidden" value="{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dltfield}]" />
      	{/if}
       	{assign var='exists' value=1}
       	<div class="row form-group selwrapper active">
					<input type="hidden" value="{$pkey}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][id]" id="id_{$table}_{$levelcount*10}{$count}" class="publish delete"/>
					<input type="hidden" value="{$l.$pkey}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$pkey}]" class="key publish delete">
					<input type="hidden" value="{$opt.id}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$skey}]" class="publish"/>
					<input type="hidden" value="{$dvalue_val}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dfield}]" class="publish"/>
			    <div class="col-sm-{$level+1}">
						<input style="float:right;" class="chckbx" type="checkbox" checked="checked" onclick="if($(this).is(':checked')){ $(this).closest('.selwrapper').find('input.delete').prop('disabled','disabled');$(this).closest('.selwrapper').find('input.publish').prop('disabled',false); }else{ $(this).closest('.selwrapper').find('input.publish').prop('disabled','disabled'); $(this).closest('.selwrapper').find('input.delete').prop('disabled',false); }" id="id_{$table}_checkbox{$opt.id}">
					</div>
					<label style="text-align:left;" class="col-sm-8 control-label" for="id_{$table}_checkbox{$opt.id}">{$opt.value}</label>
					<!-- Delete object fields -->
			    <input type="hidden" value="{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dltfield}]" disabled="disabled" class="delete"/>
			    <input type="hidden" value="" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dltfield}]" disabled="disabled" class="publish"/>
				</div>
	  	{/if}
		{/foreach}
		{if $exists eq 0}
			<div class="row form-group selwrapper">
				<input type="hidden" value="{$pkey}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][id]" id="id_{$table}_{$levelcount*10}{$count}" disabled="disabled"class="publish delete"/>
				<input type="hidden" value="" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$pkey}]" disabled="disabled" class="key publish delete"/>
				<input type="hidden" value="{$opt.id}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$skey}]"  disabled="disabled" class="publish"/>
				<input type="hidden" value="{$dvalue_val}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dfield}]" disabled="disabled" class="publish"/>
				<div class="col-sm-{$level+1}">
					<input style="float:right;" class="chckbx" type="checkbox" onclick="if($(this).is(':checked')){ $(this).closest('.selwrapper').find('input.delete').prop('disabled','disabled');$(this).closest('.selwrapper').find('input.publish').prop('disabled',false); }else{ $(this).closest('.selwrapper').find('input.publish').prop('disabled','disabled'); $(this).closest('.selwrapper').find('input.delete').prop('disabled',false); }" id="id_{$table}_checkbox{$opt.id}">
				</div>
				<label style="text-align:left;" class="col-sm-8 control-label" for="id_{$table}_checkbox{$opt.id}">{$opt.value}</label>
				<!-- Delete object fields -->
			  <input type="hidden" value="{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dltfield}]" disabled="disabled" class="delete"/>
				<input type="hidden" value="" name="field[{$order}][{$table}][{$levelcount*10}{$count}][{$dltfield}]" disabled="disabled" class="publish"/>
			</div>
		{/if}
		{if count($opt.subs) > 0}
			{call name=function_linking_list data=$opt.subs level=$level+1 levelcount=$levelcount+$count }
		{/if}
	{/foreach}
{/function}

{call name=function_linking_list data=$data order=$proccess_order dvalue=$table_primary_key_field dvalue_val=$table_primary_key_value
			table=$linking_table pkey=$linking_table_primary_key_field dfield=$linking_table_link_field
			skey=$linking_table_associated_field dltfield=$linking_table_deleted_field
			existingArr= $selected_array ignoreId=$ignore_value}
{/block}
