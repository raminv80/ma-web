		<div id="entrant_wrapper{$raceno}-{$entrantno}" class="race-entrants race_entrants{$raceno}" rel="{$raceno}-{$entrantno}">
			<div class="row-fluid race-entrants-title">
				<div class="span8">
					<fieldset>
						<legend>R{$raceno}: Entrant #{$entrantno} - <span id="horse{$entrantno}">{if $entrant.entrant_horse_id}{$fields.options.entrant_horse_id[{$entrant.entrant_horse_id}].value}{else}NO HORSE{/if}</span></legend>
					</fieldset>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="$('#entrant{$raceno}-{$entrantno}').toggle()">Show / Hide</a>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteEntrant('entrant_wrapper{$raceno}-{$entrantno}')">Delete</a>
				</div>
			</div>
			<div class="row-fluid entrants" id="entrant{$raceno}-{$entrantno}">
				<div class="row-fluid control-group">
					<div class="span3">
						<input type="hidden" value="race_id" name="default[entrant_race_id]" />
						<input type="hidden" value="{$entrant.entrant_id}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_id]" id="entrant_id" /> 
						<input type="hidden" value="entrant_id" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][id]" id="id" />
						<input type="hidden" value="{$entrant.entrant_race_id}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_race_id]" id="entrant_race_id" />  
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_horse_id">Horse</label>
					</div>
					<div class="span9 controls">
						<select name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_horse_id]" id="entrant_horse_id" onchange="$('#horse{$entrantno}').html($(this).children('option:selected').html())">
						<option value="" >Select a horse</option>
						{foreach $fields.options.entrant_horse_id as $opt}
						<option value="{$opt.id}" {if $entrant.entrant_horse_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option> 
						{/foreach}
					</select>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_row">Row</label>
					</div>
					<div class="span9 controls">
						<select name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_row]" id="entrant_row">
							<option value="1"{if $entrant.entrant_row eq 1} selected="selected"{/if}>Front row</option>
							<option value="2"{if $entrant.entrant_row eq 2} selected="selected"{/if}>Back row</option>
						</select>
					</div>
				</div>
				
				<!-- <div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_number">Entrant Number</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_number}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_number]" id="entrant_number">
					</div>
				</div> -->
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_scratch">Scratched</label>
					</div>
					<div class="span9 controls">
						<input type="hidden" value="{if $entrant.entrant_scratch eq 1}1{else}0{/if}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_scratch]" class="value"> 
						<input type="checkbox" {if $entrant.entrant_scratch eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="entrant_scratch">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_trainer">Trainer</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_trainer}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_trainer]" id="entrant_trainer">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_driver">Driver</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_driver}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_driver]" id="entrant_driver">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="listing_content3">Driver jersey</label><br />
						<label class="control-label small-txt">Size: 25px Wide x 25px Tall</label>
					</div>
					<div class="span9 controls">
						<input type="hidden" value="{$entrant.entrant_driver_file}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_driver_file]" id="entrant_driver_file{$raceno}{$entrantno}_link" class="fileinput">
						<span class="file-view" id="entrant_driver_file{$raceno}{$entrantno}_view" {if $entrant.entrant_driver_file eq ""}style="display: none"{/if} >
							<a href="{$fields.listing_content3}" target="_blank" id="entrant_driver_file{$raceno}{$entrantno}_path">{$entrant.entrant_driver_file}</a>
						</span> 
						<span class="file-view" id="entrant_driver_file{$raceno}{$entrantno}_none" {if $entrant.entrant_driver_file neq ""}style="display: none"{/if}>None</span> 
						<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="
							getFileType('entrant_driver_file{$raceno}{$entrantno}','','');
							$('#entrant_driver_file{$raceno}{$entrantno}_view').css('display','block');
							$('#entrant_driver_file{$raceno}{$entrantno}_none').css('display','none');
							">Select File</a> 
						<a href="javascript:void(0);" class="btn btn-info" onclick="
							$('#entrant_driver_file{$raceno}{$entrantno}').val('');
							$('#entrant_driver_file{$raceno}{$entrantno}_view').css('display','none');
							$('#entrant_driver_file{$raceno}{$entrantno}_none').css('display','block');
							">Remove File</a>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_class">Class</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_class}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_class]" id="entrant_class">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_early_pace">Early pace</label>
					</div>
					<div class="span9 controls">
						<!-- <input type="text" value="{$entrant.entrant_early_pace}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_early_pace]" id="entrant_early_pace"> -->
						<select name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_early_pace]" id="entrant_early_pace">
							<option value="3"{if $entrant.entrant_early_pace eq 3} selected="selected"{/if}>Slow</option>
							<option value="2"{if $entrant.entrant_early_pace eq 2} selected="selected"{/if}>Average</option>
							<option value="1"{if $entrant.entrant_early_pace eq 1} selected="selected"{/if}>Fast</option>
						</select>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_early_pace">Position after 400m</label>
					</div>
					<div class="span9 controls">
						<!-- <input type="text" value="{$entrant.entrant_early_pace}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_early_pace]" id="entrant_early_pace"> -->
						<select name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_400_position]" id="entrant_400_position">
							<option value="1"{if $entrant.entrant_400_position eq 1} selected="selected"{/if}>1st</option>
							<option value="2"{if $entrant.entrant_400_position eq 2} selected="selected"{/if}>2nd</option>
							<option value="3"{if $entrant.entrant_400_position eq 3} selected="selected"{/if}>3rd</option>
							<option value="4"{if $entrant.entrant_400_position eq 4} selected="selected"{/if}>4th</option>
							<option value="5"{if $entrant.entrant_400_position eq 5} selected="selected"{/if}>5th</option>
							<option value="6"{if $entrant.entrant_400_position eq 6} selected="selected"{/if}>6th</option>
							<option value="7"{if $entrant.entrant_400_position eq 7} selected="selected"{/if}>7th</option>
							<option value="8"{if $entrant.entrant_400_position eq 8} selected="selected"{/if}>8th</option>
							<option value="9"{if $entrant.entrant_400_position eq 9} selected="selected"{/if}>9th</option>
						</select>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_racing_pattern">Racing Pattern</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_racing_pattern}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_racing_pattern]" id="entrant_racing_pattern">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_racing_odds">Odds</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_odds}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_odds]" id="entrant_odds">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_form_career">Form Career</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_form_career}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_form_career]" id="entrant_form_career">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_form_lts">Form LTS</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_form_lts}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_form_lts]" id="entrant_form_lts">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_form_bmr">Form BMR</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_form_bmr}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_form_bmr]" id="entrant_form_bmr">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_form_ts">Form TS</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_form_ts}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_form_ts]" id="entrant_form_ts">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_form_ls">Form LS</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$entrant.entrant_form_ls}" name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_form_ls]" id="entrant_form_ls">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="entrant_comments">Comments</label>
					</div>
					<div class="span9 controls">
						<textarea name="field[{$raceno+10}][tbl_entrant][{$raceno}-{$entrantno}][entrant_comments]" id="entrant_comments">{$entrant.entrant_comments}</textarea>
					</div>
				</div>
			</div>
		</div>