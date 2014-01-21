{block name=body}
<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row-fluid">
				<div class="span12">
					<fieldset>
						<legend>
							{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							{if $fields.listing_id neq ""} 
								<a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>ADD NEW</a> 
							{/if}
						</legend>
					</fieldset>
					<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;" /> <input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_type_id"> <input type="hidden"
						value="1" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_parent_flag">Is Parent?</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_parent_flag eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" class="value"> <input type="checkbox" {if $fields.listing_parent_flag eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_parent_flag">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_display_menu">Display in Menu?</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_display_menu eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" class="value"> <input type="checkbox" {if $fields.listing_display_menu eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_display_menu">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_name">Name</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturl(this.value);">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_title">Title</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_title}" name="field[1][tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_url">URL</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_parent">Parent</label>
				</div>
				<div class="span9 controls">
					<select name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="id_listing_parent">
						<option value="0">Select one</option> {foreach $fields.options.listing_parent_id as $opt}
						<option value="{$opt.id}" {if $fields.listing_parent_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option> {/foreach}
					</select>

				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_seo_title">SEO Title</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_meta_description">Meta Description</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_meta_description}" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_meta_words">Meta Words</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_order">Order</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_order}" name="field[1][tbl_listing][{$cnt}][listing_order]" id="id_listing_order">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_published">Published</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_published eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_published]" class="value"> <input type="checkbox" {if $fields.listing_published eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_published">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="listing_image">Header Image</label><br />
					<label class="control-label small-txt">Size: 1960px Wide x 345px Tall <br>("None" for default image)</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{$fields.listing_image}" name="field[1][tbl_listing][{$cnt}][listing_image]" id="listing_image_link" class="fileinput"> <span class="file-view" id="listing_image_view" {if $fields.listing_image eq ""}style="display: none"{/if} > <a
						href="{$fields.listing_image}" target="_blank" id="listing_image_path">{$fields.listing_image}</a>
					</span> <span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display: none"{/if}>None</span> <a href="javascript:void(0);" class="btn btn-info marg-5r"
						onclick="
					getFileType('listing_image','','');
					$('#listing_image_view').css('display','block');
					$('#listing_image_none').css('display','none');
					">Select File</a> <a href="javascript:void(0);" class="btn btn-info"
						onclick="
				$('#listing_image_link').val('');
				$('#listing_image_view').css('display','none');
				$('#listing_image_none').css('display','block');
				">Remove File</a>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="listing_content3">Top Ad-Banner</label><br />
					<label class="control-label small-txt">Size: 780px Wide x 90px Tall</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{$fields.listing_content3}" name="field[1][tbl_listing][{$cnt}][listing_content3]" id="listing_content3_link" class="fileinput"> 
					<span class="file-view" id="listing_content3_view" {if $fields.listing_content3 eq ""}style="display: none"{/if} > 
						<a href="{$fields.listing_content3}" target="_blank" id="listing_content3_path">{$fields.listing_content3}</a>
					</span> 
					<span class="file-view" id="listing_content3_none" {if $fields.listing_content3 neq ""}style="display: none"{/if}>None</span> 
					<a href="javascript:void(0);" class="btn btn-info marg-5r"	
						onclick="getFileType('listing_content3','','');
						$('#listing_content3_view').css('display','block');
						$('#listing_content3_none').css('display','none');
						">Select File</a> 
					<a href="javascript:void(0);" class="btn btn-info" 
						onclick="$('#listing_content3_link').val('');
						$('#listing_content3_view').css('display','none');
						$('#listing_content3_none').css('display','block');
						">Remove File</a>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content5">Ad-Banner Link</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_content5}" name="field[1][tbl_listing][{$cnt}][listing_content5]" id="id_listing_content5" style="width: 80%;">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content1">Content</label><br />
				</div>
				<div class="span9 controls">
					<textarea name="field[1][tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content2">YouTube Video Link</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_content2}" name="field[1][tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" style="width: 80%;">
				</div>
			</div>
			
		<!--  DRIVERS -->
			 <div class="row-fluid control-group drivers">
				<div class="span3">
					<label class="control-label" for="drivers-content">Drivers</label>
				</div>
				<div class="span9 controls" id="drivers-content">
					{counter start=0 skip=1 assign="count"}
					{foreach $fields.drivers as $item}
						{counter}
						<div class="row-fluid driver_item" rel="{$count}" id="driver_container{$count}" >
							<input type="hidden" value="driver_id" name="field[2][tbl_driver][{$count}][id]" id="id" />
							<input type="hidden" value="{$item.driver_id}" name="field[2][tbl_driver][{$count}][driver_id]" id="driver_id{$count}">
							<input type="hidden" value="11" name="field[2][tbl_driver][{$count}][driver_listing_id]" id="driver_listing_{$count}" >
							<div class="row-fluid control-group">
								<div class="span2"><label for="driver_name{$count}">Name</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_name}" name="field[2][tbl_driver][{$count}][driver_name]" class="value" id="driver_name{$count}">
								</div>
								<div class="span2"><a href="javascript:void(0);" id="dbutton{$count}" class="btn btn-warning driver-btn"	
									onclick="
										if($('#dbutton{$count}').html() == 'Show'){
											$('.d{$count}').show();
											$('#dbutton{$count}').html('Hide');
										}else{
											$('.d{$count}').hide();
											$('#dbutton{$count}').html('Show');
										}">Show</a> 
								</div>
								<div class="span3">
									<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deletedriver('driver_container{$count}')">Delete</a>
								</div>
							</div>
							
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_races{$count}">Races</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_races}" name="field[2][tbl_driver][{$count}][driver_races]" class="value" id="driver_races{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_1sts{$count}">1sts</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_1sts}" name="field[2][tbl_driver][{$count}][driver_1sts]" class="value" id="driver_1sts{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_1sts{$count}">2nds</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_2nds}" name="field[2][tbl_driver][{$count}][driver_2nds]" class="value" id="driver_2nds{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_3rds{$count}">3rds</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_3rds}" name="field[2][tbl_driver][{$count}][driver_3rds]" class="value" id="driver_3rds{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_4ths{$count}">4ths</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_4ths}" name="field[2][tbl_driver][{$count}][driver_4ths]" class="value" id="driver_4ths{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2"><label for="driver_stakes{$count}">Stakes</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.driver_stakes}" name="field[2][tbl_driver][{$count}][driver_stakes]" class="value" id="driver_stakes{$count}" style="width: 120px;">
								</div>
							</div>
							<div class="row-fluid control-group driver d{$count}">
								<div class="span2">
									<label for="driver_photo{$count}">Photo</label>
									<label class="control-label small-txt">Size: 110px Wide x 135px Tall</label>
								</div>
								<div class="span10 controls">
									<input type="hidden" value="{$item.driver_photo}" name="field[2][tbl_driver][{$count}][driver_photo]" id="driver_photo{$count}_link" class="fileinput"> 
									<span class="file-view" id="driver_photo{$count}_view" {if $item.driver_photo eq ""}style="display: none"{/if} > 
										<a href="{$item.driver_photo}" target="_blank" id="driver_photo{$count}_path">{$item.driver_photo}</a>
									</span> 
									<span class="file-view" id="driver_photo{$count}_none" {if $item.driver_photo neq ""}style="display: none"{/if}>None</span> 
									<a href="javascript:void(0);" class="btn btn-info marg-5r"	
										onclick="getFileType('driver_photo{$count}','','');
										$('#driver_photo{$count}_view').css('display','block');
										$('#driver_photo{$count}_none').css('display','none');
										">Select File</a> 
									<a href="javascript:void(0);" class="btn btn-info" 
										onclick="$('#driver_photo{$count}_link').val('');
										$('#driver_photo{$count}_view').css('display','none');
										$('#driver_photo{$count}_none').css('display','block');
										">Remove File</a>
								</div>
							</div>
						</div>
					{/foreach}
					<input type="hidden" value="{$count}" name="driver" id="driver_no" />
				</div>
			</div>
			 <div class="row-fluid control-group">
				<div class="span3"></div>
				<div class="span9 controls">
					<div class="row-fluid">
						<div class="span12">
							<a id="add-driver-btn" href="javascript:void(0);" class="btn btn-info" onclick="addDriver();">Add New driver</a>
							<span id="drivers-full" class="btn btn-danger del-btn"  style="display:none;">DRIVERS FULL</span>
						</div>
					</div>
				</div>
			</div>
		<!-- DRIVERS -->
			
		<!--  TRAINERS -->
			 <div class="row-fluid control-group trainers">
				<div class="span3">
					<label class="control-label" for="trainers-content">Trainers</label>
				</div>
				<div class="span9 controls" id="trainers-content">
					{counter start=0 skip=1 assign="count"}
					{foreach $fields.trainers as $item}
						{counter}
						<div class="row-fluid trainer_item" rel="{$count}" id="trainer_container{$count}" >
							<input type="hidden" value="trainer_id" name="field[1][tbl_trainer][{$count}][id]" id="id" />
							<input type="hidden" value="{$item.trainer_id}" name="field[1][tbl_trainer][{$count}][trainer_id]" id="trainer_id{$count}">
							<input type="hidden" value="11" name="field[1][tbl_trainer][{$count}][trainer_listing_id]" id="trainer_listing_{$count}" >
							<div class="row-fluid control-group">
								<div class="span2"><label for="trainer_name{$count}">Name</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_name}" name="field[1][tbl_trainer][{$count}][trainer_name]" class="value" id="trainer_name{$count}">
								</div>
								<div class="span2"><a href="javascript:void(0);" id="tbutton{$count}" class="btn btn-warning trainer-btn"	
									onclick="
										if($('#tbutton{$count}').html() == 'Show'){
											$('.t{$count}').show();
											$('#tbutton{$count}').html('Hide');
										}else{
											$('.t{$count}').hide();
											$('#tbutton{$count}').html('Show');
										}">Show</a> 
								</div>
								<div class="span3">
									<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deletetrainer('trainer_container{$count}')">Delete</a>
								</div>
							</div>
							
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_races{$count}">Races</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_races}" name="field[1][tbl_trainer][{$count}][trainer_races]" class="value" id="trainer_races{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_1sts{$count}">1sts</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_1sts}" name="field[1][tbl_trainer][{$count}][trainer_1sts]" class="value" id="trainer_1sts{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_1sts{$count}">2nds</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_2nds}" name="field[1][tbl_trainer][{$count}][trainer_2nds]" class="value" id="trainer_2nds{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_3rds{$count}">3rds</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_3rds}" name="field[1][tbl_trainer][{$count}][trainer_3rds]" class="value" id="trainer_3rds{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_4ths{$count}">4ths</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_4ths}" name="field[1][tbl_trainer][{$count}][trainer_4ths]" class="value" id="trainer_4ths{$count}" style="width: 80px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2"><label for="trainer_stakes{$count}">Stakes</label></div>
								<div class="span5 controls">
									<input type="text" value="{$item.trainer_stakes}" name="field[1][tbl_trainer][{$count}][trainer_stakes]" class="value" id="trainer_stakes{$count}" style="width: 120px;">
								</div>
							</div>
							<div class="row-fluid control-group trainer t{$count}">
								<div class="span2">
									<label for="trainer_photo{$count}">Photo</label>
									<label class="control-label small-txt">Size: 110px Wide x 135px Tall</label>
								</div>
								<div class="span10 controls">
									<input type="hidden" value="{$item.trainer_photo}" name="field[1][tbl_trainer][{$count}][trainer_photo]" id="trainer_photo{$count}_link" class="fileinput"> 
									<span class="file-view" id="trainer_photo{$count}_view" {if $item.trainer_photo eq ""}style="display: none"{/if} > 
										<a href="{$item.trainer_photo}" target="_blank" id="trainer_photo{$count}_path">{$item.trainer_photo}</a>
									</span> 
									<span class="file-view" id="trainer_photo{$count}_none" {if $item.trainer_photo neq ""}style="display: none"{/if}>None</span> 
									<a href="javascript:void(0);" class="btn btn-info marg-5r"	
										onclick="getFileType('trainer_photo{$count}','','');
										$('#trainer_photo{$count}_view').css('display','block');
										$('#trainer_photo{$count}_none').css('display','none');
										">Select File</a> 
									<a href="javascript:void(0);" class="btn btn-info" 
										onclick="$('#trainer_photo{$count}_link').val('');
										$('#trainer_photo{$count}_view').css('display','none');
										$('#trainer_photo{$count}_none').css('display','block');
										">Remove File</a>
								</div>
							</div>
						</div>
					{/foreach}
					<input type="hidden" value="{$count}" name="trainer" id="trainer_no" />
				</div>
			</div>
			 <div class="row-fluid control-group">
				<div class="span3"></div>
				<div class="span9 controls">
					<div class="row-fluid">
						<div class="span12">
							<a id="add-trainer-btn" href="javascript:void(0);" class="btn btn-info" onclick="addTrainer();">Add New trainer</a>
							<span id="trainers-full" class="btn btn-danger del-btn"  style="display:none;">TRAINERS FULL</span>
						</div>
					</div>
				</div>
			</div>
		
	<!-- TRAINERS -->
			
			<div class="row-fluid control-group">
				<div class="form-actions">
					<button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button>
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>
		</form>
	</div>
</div>
{literal}
<script>

$(document).ready(function(){
	$('.driver').hide();
	checkDriver();
	$('.trainer').hide();
	checkTrainer();
});

function checkDriver(){
	var no = $('#driver_no').val();
	if($('.driver_item').length >= 10){
		$('#add-driver-btn').hide();
		$('#drivers-full').show();
		return;
	}
}
function checkTrainer(){
	var no = $('#trainer_no').val();
	if($('.trainer_item').length >= 10){
		$('#add-trainer-btn').hide();
		$('#trainers-full').show();
		return;
	}	
}
					

function addTrainer(){
	var no = $('#trainer_no').val();
	if($('.trainer_item').length >= 10){
		$('#add-trainer-btn').hide();
		$('#trainers-full').show();
		return;
	}
	no++;
	$('#trainer_no').val(no);
	
	var buf = '<div class="row-fluid trainer_item" rel="'+no+'" id="trainer_container'+no+'">';
	buf += '<input type="hidden" value="trainer_id" name="field[1][tbl_trainer]['+no+'][id]" id="id" />';
	buf += '<input type="hidden" value="" name="field[1][tbl_trainer]['+no+'][trainer_id]" id="trainer_id'+no+'">';
	buf += '<input type="hidden" value="11" name="field[1][tbl_trainer]['+no+'][trainer_listing_id]" id="trainer_listing_'+no+'" >';
	buf += '<div class="row-fluid control-group">';
	buf += '	<div class="span2"><label for="trainer_name'+no+'">Name</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="" name="field[1][tbl_trainer]['+no+'][trainer_name]" id="trainer_name'+no+'">';
	buf += '	</div>';
	buf += '		<div class="span2"><a href="javascript:void(0);" id="tbutton'+no+'" class="btn btn-warning trainer-btn" ';
	buf += '			onclick="if($(\'#tbutton'+no+'\').html() == \'Show\'){$(\'.t'+no+'\').show();	$(\'#tbutton'+no+'\').html(\'Hide\');';
	buf += '				}else{$(\'.t'+no+'\').hide();$(\'#tbutton'+no+'\').html(\'Show\');}">Hide</a></div>';
	buf += '		<div class="span3"><a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deletetrainer(\'trainer_container'+no+'\')">Delete</a></div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_races'+no+'">Races</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_races]" id="trainer_races'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_1sts'+no+'">1sts</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_1sts]" id="trainer_1sts'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_2nds'+no+'">2nds</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_2nds]" id="trainer_2nds'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_3rds'+no+'">3rds</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_3rds]" id="trainer_3rds'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_4ths'+no+'">4ths</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_4ths]" id="trainer_4ths'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';
	buf += '	<div class="span2"><label for="trainer_stakes'+no+'">Stakes</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_trainer]['+no+'][trainer_stakes]" id="trainer_stakes'+no+'" style="width: 120px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group trainer t'+no+'">';	
	buf += '	<div class="span2">';	
	buf += '		<label for="trainer_photo'+no+'">Photo</label>';	
	buf += '		<label class="control-label small-txt">Size: 110px Wide x 135px Tall</label>';	
	buf += '	</div>';	
	buf += '	<div class="span10 controls">';	
	buf += '		<input type="hidden" value="" name="field[1][tbl_trainer]['+no+'][trainer_photo]" id="trainer_photo'+no+'_link" class="fileinput"> ';	
	buf += '		<span class="file-view" id="trainer_photo'+no+'_view" style="display: none" > ';	
	buf += '			<a href="{$item.trainer_photo}" target="_blank" id="trainer_photo'+no+'_path">{$item.trainer_photo}</a>';	
	buf += '		</span> ';	
	buf += '		<span class="file-view" id="trainer_photo'+no+'_none">None</span> ';	
	buf += '		<a href="javascript:void(0);" class="btn btn-info marg-5r"	';	
	buf += '			onclick="getFileType(\'trainer_photo'+no+'\',\'\',\'\');';	
	buf += '			$(\'#trainer_photo'+no+'_view\').css(\'display\',\'block\');';	
	buf += '			$(\'#trainer_photo'+no+'_none\').css(\'display\',\'none\');';	
	buf += '			">Select File</a> ';	
	buf += '		<a href="javascript:void(0);" class="btn btn-info" ';	
	buf += '			onclick="$(\'#trainer_photo'+no+'_link\').val(\'\');';	
	buf += '			$(\'#trainer_photo'+no+'_view\').css(\'display\',\'none\');';	
	buf += '			$(\'#trainer_photo'+no+'_none\').css(\'display\',\'block\');';	
	buf += '			">Remove File</a>';	
	buf += '	</div>';	
	buf += '</div>';	
	buf += '</div>';

	$('.trainer').hide();
	$('.trainer-btn').html('Show'); 
	$('#trainers-content').append(buf);
	if($('.trainer_item').length >= 10){
		$('#add-trainer-btn').hide();
		$('#trainers-full').show();
		return;
	}
}

function deletetrainer(ID){
	if (ConfirmDelete()) {
		var count = $('#'+ID).attr('rel');
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear(); 
		var hh = today.getHours();
		var MM = today.getMinutes();
		var ss = today.getSeconds();
		
		html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[1][tbl_trainer]['+count+'][trainer_deleted]" />';
		$('#'+ID).append(html);
		$('#'+ID).css('display','none');
		$('#'+ID).removeClass('trainer_item');
	
		var no = $('#trainer_no').val();
		if($('.trainer_item').length >= 10){
			$('#add-trainer-btn').hide();
			$('#trainers-full').show();
			return;
		}else{
			$('#add-trainer-btn').show();
			$('#trainers-full').hide();
		}
	}else{ 
		return false;
	}
}

function addDriver(){
	var no = $('#driver_no').val();
	if($('.driver_item').length >= 10){
		$('#add-driver-btn').hide();
		$('#drivers-full').show();
		return;
	}
	no++;
	$('#driver_no').val(no);
	
	var buf = '<div class="row-fluid driver_item" rel="'+no+'" id="driver_container'+no+'">';
	buf += '<input type="hidden" value="driver_id" name="field[1][tbl_driver]['+no+'][id]" id="id" />';
	buf += '<input type="hidden" value="" name="field[1][tbl_driver]['+no+'][driver_id]" id="driver_id'+no+'">';
	buf += '<input type="hidden" value="11" name="field[1][tbl_driver]['+no+'][driver_listing_id]" id="driver_listing_'+no+'" >';
	buf += '<div class="row-fluid control-group">';
	buf += '	<div class="span2"><label for="driver_name'+no+'">Name</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="" name="field[1][tbl_driver]['+no+'][driver_name]" id="driver_name'+no+'">';
	buf += '	</div>';
	buf += '		<div class="span2"><a href="javascript:void(0);" id="dbutton'+no+'" class="btn btn-warning driver-btn" ';
	buf += '			onclick="if($(\'#dbutton'+no+'\').html() == \'Show\'){$(\'.d'+no+'\').show();	$(\'#dbutton'+no+'\').html(\'Hide\');';
	buf += '				}else{$(\'.d'+no+'\').hide();$(\'#dbutton'+no+'\').html(\'Show\');}">Hide</a></div>';
	buf += '		<div class="span3"><a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deletedriver(\'driver_container'+no+'\')">Delete</a></div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_races'+no+'">Races</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_races]" id="driver_races'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_1sts'+no+'">1sts</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_1sts]" id="driver_1sts'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_2nds'+no+'">2nds</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_2nds]" id="driver_2nds'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_3rds'+no+'">3rds</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_3rds]" id="driver_3rds'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_4ths'+no+'">4ths</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_4ths]" id="driver_4ths'+no+'" style="width: 80px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';
	buf += '	<div class="span2"><label for="driver_stakes'+no+'">Stakes</label></div>';
	buf += '	<div class="span5 controls">';
	buf += '		<input type="text" value="0" name="field[1][tbl_driver]['+no+'][driver_stakes]" id="driver_stakes'+no+'" style="width: 120px;"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid control-group driver d'+no+'">';	
	buf += '	<div class="span2">';	
	buf += '		<label for="driver_photo'+no+'">Photo</label>';	
	buf += '		<label class="control-label small-txt">Size: 110px Wide x 135px Tall</label>';	
	buf += '	</div>';	
	buf += '	<div class="span10 controls">';	
	buf += '		<input type="hidden" value="" name="field[1][tbl_driver]['+no+'][driver_photo]" id="driver_photo'+no+'_link" class="fileinput"> ';	
	buf += '		<span class="file-view" id="driver_photo'+no+'_view" style="display: none" > ';	
	buf += '			<a href="{$item.driver_photo}" target="_blank" id="driver_photo'+no+'_path">{$item.driver_photo}</a>';	
	buf += '		</span> ';	
	buf += '		<span class="file-view" id="driver_photo'+no+'_none">None</span> ';	
	buf += '		<a href="javascript:void(0);" class="btn btn-info marg-5r"	';	
	buf += '			onclick="getFileType(\'driver_photo'+no+'\',\'\',\'\');';	
	buf += '			$(\'#driver_photo'+no+'_view\').css(\'display\',\'block\');';	
	buf += '			$(\'#driver_photo'+no+'_none\').css(\'display\',\'none\');';	
	buf += '			">Select File</a> ';	
	buf += '		<a href="javascript:void(0);" class="btn btn-info" ';	
	buf += '			onclick="$(\'#driver_photo'+no+'_link\').val(\'\');';	
	buf += '			$(\'#driver_photo'+no+'_view\').css(\'display\',\'none\');';	
	buf += '			$(\'#driver_photo'+no+'_none\').css(\'display\',\'block\');';	
	buf += '			">Remove File</a>';	
	buf += '	</div>';	
	buf += '</div>';	
	buf += '</div>';

	$('.driver').hide();
	$('.driver-btn').html('Show'); 
	$('#drivers-content').append(buf);
	if($('.driver_item').length >= 10){
		$('#add-driver-btn').hide();
		$('#drivers-full').show();
		return;
	}
}

function deletedriver(ID){
	if (ConfirmDelete()) {
		var count = $('#'+ID).attr('rel');
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear(); 
		var hh = today.getHours();
		var MM = today.getMinutes();
		var ss = today.getSeconds();
		
		html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[2][tbl_driver]['+count+'][driver_deleted]" />';
		$('#'+ID).append(html);
		$('#'+ID).css('display','none');
		$('#'+ID).removeClass('driver_item');
	
		var no = $('#driver_no').val();
		if($('.driver_item').length >= 10){
			$('#add-driver-btn').hide();
			$('#drivers-full').show();
			return;
		}else{
			$('#add-driver-btn').show();
			$('#drivers-full').hide();
		}
	}else{ 
		return false;
	}
}

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
{/literal}
{/block}
