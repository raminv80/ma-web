{block name=body}
{* Define the function *} {function name=options_list level=0} 
	{foreach $opts as $opt}
		{if $fields.listing_id neq $opt.id}
			<option value="{$opt.id}" {if $fields.listing_parent_id eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1} {/if} 
		{/if}
	{/foreach} 
{/function}

<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend>
							{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							{if $fields.listing_id neq ""} 
								<a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>Add New</a> 
							{/if}
						</legend>
					</fieldset>
					<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id"/> 
					<input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_id">
					<input type="hidden" value="{$typeID}" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id"> 
					<input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" id="listing_parent_flag"> 
					<input type="hidden" value="0" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" id="listing_display_menu"> 
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_name">Name</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" required onchange="seturl(this.value);">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_title">Title</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_title}" name="field[1][tbl_listing][{$cnt}][listing_title]" id="id_listing_title" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_url">URL</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_parent">Parent</label>
				<div class="col-sm-5">
					<select class="form-control" name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="id_listing_parent">
						<option value="{$rootParentID}">None</option> 
						{call name=options_list opts=$fields.options.listing_parent_id}
					</select>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_seo_title">SEO Title</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_meta_description">Meta Description</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_meta_description}" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_meta_words">Meta Words</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_order">Order</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_order}" name="field[1][tbl_listing][{$cnt}][listing_order]" id="id_listing_order">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_published">Published</label>
				<div class="col-sm-5">
					<input type="hidden" value="{if $fields.listing_published eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_published]" class="value"> <input type="checkbox" {if $fields.listing_published eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_published">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="listing_image">Image<br><small>Size: 480px Wide x 480px Tall</small></label>
				<div class="col-sm-9">
					<input type="hidden" value="{$fields.listing_image}" name="field[1][tbl_listing][{$cnt}][listing_image]" id="listing_image_link" class="fileinput"> <span class="file-view" id="listing_image_view" {if $fields.listing_image eq ""}style="display: none"{/if} > <a
						href="{$fields.listing_image}" target="_blank" id="listing_image_path">{$fields.listing_image}</a>
					</span> <span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display: none"{/if}>None</span> <a href="javascript:void(0);" class="btn btn-info marg-5r"
						onclick="
					getFileType('listing_image','','');
					$('#listing_image_view').css('display','block');
					$('#listing_image_none').css('display','none');
					">Select File</a> <a href="javascript:void(0);" class="btn btn-info"
						onclick="
				$('#listing_image').val('');
				$('#listing_image_view').css('display','none');
				$('#listing_image_none').css('display','block');
				">Remove File</a>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button>
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}
<script type="text/javascript">
function seturl(str){
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/urlencode.php",
		cache: false,
		data: "value="+encodeURIComponent(str),
		dataType: "json",
	    success: function(res, textStatus) {
	    	try{
	    		$('#id_listing_url').val(res.url);
	    	}catch(err){ }
	    }
	});
}

</script>
{/block}
