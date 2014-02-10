{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend>
							{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone} {if $cnt eq ""}{assign var=cnt value=0}{/if} {if $fields.listing_id neq ""} <a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>ADD NEW
							</a> {/if}
						</legend>
					</fieldset>
					<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id"/> <input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_type_id"> <input type="hidden"
						value="1" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_listing_parent_flag">Is Parent?</label>
				<div class="col-sm-5">
					<input type="hidden" value="{if $fields.listing_parent_flag eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" class="value"> <input type="checkbox" {if $fields.listing_parent_flag eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_parent_flag">
				</div>
			</div>
			<div class="row form-group">
					<label class="col-sm-3 control-label" for="id_listing_display_menu">Display in Menu?</label>
				<div class="col-sm-5">
					<input type="hidden" value="{if $fields.listing_display_menu eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" class="value"> <input type="checkbox" {if $fields.listing_display_menu eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_display_menu">
				</div>
			</div>
			<div class="row form-group">
					<label class="col-sm-3 control-label" for="id_listing_name">Name</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturl(this.value);">
				</div>
			</div>
			<div class="row form-group">
					<label class="col-sm-3 control-label" for="id_listing_title">Title</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_title}" name="field[1][tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req">
				</div>
			</div>
			<div class="row form-group">
					<label class="col-sm-3 control-label" for="id_listing_url">URL</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req">
				</div>
			</div>
			<div class="row form-group">
				<div class="alert alert-info">
					This page is for website structural purposes only. 
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
<script>
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
