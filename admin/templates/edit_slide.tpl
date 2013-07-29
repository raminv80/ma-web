{block name=body}<!-- fredys comment -->
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
				{if $fields.slide_id neq ""}Edit{else}New{/if} Slide
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>

				<input type="hidden" value="{$fields.slide_id}" name="field[tbl_slide][{$cnt}][slide_id]" id="slide_id" />
				<input type="hidden" value="slide_id" name="field[tbl_slide][{$cnt}][id]" id="id" />
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_text">{if $type neq "1"}Title {/if}Text</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.slide_text}" name="field[tbl_slide][{$cnt}][slide_text]" id="slide_text" class="tool-tip" data-toggle="tooltip" data-original-title="{if $type eq "1"}recommended image width 320px. height to depend on amount of text used, approx 22 characters per line of text{/if}" ></div>
		</div>

		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Image</label></div>
			<div class="span9 controls"><input type="hidden" value="{$fields.slide_image}" name="field[tbl_slide][{$cnt}][slide_image]" id="slide_image" class="fileinput" >
			<span class="file-view" id="slide_image_view" {if $fields.slide_image eq ""}style="display:none"{/if}>
				<a href="{$fields.slide_image}" target="_blank" id="slide_image_path" >{$fields.slide_image}</a> &nbsp;
			</span>
			<span class="file-view" id="listing_image_none" {if $fields.slide_image neq ""}style="display:none"{/if}>None</span>
			<a href="javascript:void(0);"  class="btn btn-info marg-5r" type="button" onclick="getFileType('slide_image');$('#slide_image_view').css('display','block');$('#slide_image_none').css('display','none');">Select File</a>
			<a href="javascript:void(0);" class="btn btn-info" onclick="$('#slide_image').val('');$('#slide_image_view').css('display','none');$('#slide_image_none').css('display','block');">Remove File</a>
			{if $type eq "1"}<br><small>Please use an image of 320px.</small>{else}<br><small>Please use an image of 740px wide by 321px high.</small>{/if}
		</div>
		{if $type eq "1"}
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_link">Link</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.slide_link}" name="field[tbl_slide][{$cnt}][slide_link]" id="slide_link" class="tool-tip" data-original-title="local link [do not include http://www.mcleay.com.au] "></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_special">Special Logo</label></div>
			<div class="span9 controls">
				<input type="checkbox"   name="slide_special_check" id="slide_special_check" class="" {if $fields.slide_special eq "1"}checked="checked"{/if}  >
				<input type="hidden" value="0"  name="field[tbl_slide][{$cnt}][slide_special]" id="slide_special" class="">
			</div>
		</div>
		{/if}
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_text">Order</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.slide_order}" name="field[tbl_slide][{$cnt}][slide_order]" id="slide_text" class=""></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span9 offset3">
				<script type="text/javascript">
				function validate(){
					if($('#slide_special_check').attr('checked') == "checked"){
						$('#slide_special').val('1');
					}else{
						$('#slide_special').val('0');
					}

					$('#Edit_Record').submit();
				}

			</script>
			</div>

            <div class="form-actions">
                <button class="btn btn-primary" onClick="validate()" type="submit">Submit</button>
            </div>

			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			<input type="hidden" value="{$type}" name="field[tbl_slide][{$cnt}][slide_type_id]"  />
		</div>
	</form>
	</div>
</div>
{/block}