		<div id="race_wrapper{$raceno}" rel="{$raceno}" class="form-race">
			<div class="row-fluid form-race-title" >
				<div class="span8">
					<fieldset>
						<legend>Race #{$raceno}</legend>
					</fieldset>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleRace('{$raceno}')">Show / Hide</a>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteRace('{$raceno}')">Delete</a>
				</div>
			</div>
			<div class="row-fluid races" id="race{$raceno}">
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_title">Name</label>
						<input type="hidden" value="meeting_id" name="default[race_meeting_id]" />
						<input type="hidden" value="{$race.race_id}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_id]" id="race_id" /> 
						<input type="hidden" value="race_id" name="field[{$raceno+10}][tbl_race][{$raceno}][id]" id="id" />
						<input type="hidden" value="{$race.race_meeting_id}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_meeting_id]" id="race_meeting_id" />  
					</div>
					<div class="span9 controls">
						<input type="text" value="{$race.race_title}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_title]" id="race_title" >
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_distance">Distance</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$race.race_distance}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_distance]" id="race_distance" class="number">
					</div>
				</div>
				<!-- <div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_sprintlane">Sprint lane</label>
					</div>
					<div class="span9 controls">
						<input type="hidden" value="{if {$race.race_sprintlane} eq 1}1{else}0{/if}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_sprintlane]" class="value"> 
						<input type="checkbox" {if $race.race_sprintlane eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="race_sprintlane">
					</div>
				</div> -->
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_start_time{$raceno}">Start Time</label>
					</div>
					<div class="span9">
						<input type="text" value="{$race.race_start_time}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_start_time]" class="value" id="race_start_time{$raceno}">
						<script type="text/javascript">
							$(function(){
								$('#race_start_time{$raceno}').timepicker();
							});
						</script>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_prize">Prize money</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$race.race_prize}" name="field[{$raceno+10}][tbl_race][{$raceno}][race_prize]" id="race_prize" class="double">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="race_analysis">Analysis</label>
					</div>
					<div class="span9 controls">
						<textarea name="field[{$raceno+10}][tbl_race][{$raceno}][race_analysis]" id="race_analysis">{$race.race_analysis}</textarea>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span9 controls">
						<fieldset>
							<legend> Entrants </legend>
						</fieldset>
					</div>
					<div class="span3 controls">
						<a href="javascript:void(0);" class="btn del-btn" onclick="$('.entrants').hide();newEntrant({$raceno})">Add New Entrant</a>
						
					</div>
				</div>
				<div id="entrant-wrapper{$raceno}">
					{assign var='entrantno' value=0}
					{foreach $race.entrant as $entrant}
						{assign var='entrantno' value=$entrantno+1}
						{include file='form_entrant.tpl'}
					{/foreach}
					<input type="hidden" value="{$entrantno}" id="entrantno{$raceno}">
				</div>
			</div>
		</div>