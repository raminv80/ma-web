{block name=body}
<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<ul class="nav nav-tabs">
			  <li class="active"><a href="#meeting" data-toggle="tab">Meeting</a></li>
			  <li><a href="#races" data-toggle="tab">Races</a></li>
			  <!-- <a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>ADD NEW</a>  -->
			</ul>
			<input type="hidden" value="{$fields.meeting_id}" name="field[1][tbl_meeting][{$cnt}][meeting_id]" id="meeting_id" /> 
			<input type="hidden" value="10" name="field[1][tbl_meeting][{$cnt}][meeting_listing_id]" id="meeting_listing_id" /> 
			<input type="hidden" value="meeting_id" name="field[1][tbl_meeting][{$cnt}][id]" id="id" /> 
			<div class="tab-content">
 				<div class="tab-pane active" id="meeting">
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_title">Location Title</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.meeting_title}" name="field[1][tbl_meeting][{$cnt}][meeting_title]" id="meeting_title">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_date">Date</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.meeting_date}" name="field[1][tbl_meeting][{$cnt}][meeting_date]" class="value" id="meeting_date">
							<script type="text/javascript">
								$(function(){
									$('#meeting_date').datepicker({
										dateFormat: "yy-mm-dd"
									});
								});
							</script>
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_bet_now_url">Url</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.meeting_bet_now_url}" name="field[1][tbl_meeting][{$cnt}][meeting_bet_now_url]" id="meeting_bet_now_url" style="width: 80%;">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_published">published</label>
						</div>
						<div class="span9 controls">
							<input type="hidden" value="{if $fields.meeting_published eq 1}1{else}0{/if}" name="field[1][tbl_meeting][{$cnt}][meeting_published]" class="value"> 
							<input type="checkbox" {if $fields.meeting_published eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="meeting_published">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_street">Street Address</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.meeting_street}" name="field[1][tbl_meeting][{$cnt}][meeting_street]" id="meeting_street">
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3">
							<label class="control-label" for="meeting_suburb">Suburb</label>
						</div>
						<div class="span9 controls">
							<input type="text" value="{$fields.meeting_suburb}" id="meeting_Suburb" name="field[1][tbl_meeting][{$cnt}][meeting_suburb]" >
							<input type="hidden" value="{if $fields.meeting_state}{$fields.meeting_state}{else}SA{/if}" id="meeting_state" name="field[1][tbl_meeting][{$cnt}][meeting_state]" >
						</div>
					</div>
					<div class="row-fluid control-group">
						<div class="span3"><label class="control-label" for="search">Map Location</label></div>
						<div class="span9">
							<a href="javascript:void(0);" class="btn btn-info" onclick="searchAddress($('#meeting_street').val()+', '+$('#meeting_Suburb').val()+', '+$('#meeting_state').val());">Search current address</a>
							<div id="search-warning"></div>
							<input type="hidden" value="{$fields.meeting_latitude}" name="field[1][tbl_meeting][{$cnt}][meeting_latitude]" id="location_latitude">
							<input type="hidden" value="{$fields.meeting_longitude}" name="field[1][tbl_meeting][{$cnt}][meeting_longitude]" id="location_longitude">
							<div id="GmlMap" class="GmlMap">Loading Map....</div>
							<script type="text/javascript">
								jQuery(document).ready(function() {
									centerOn({$fields.meeting_latitude},{$fields.meeting_longitude});
								});
							</script>
							<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
							<script type="text/javascript" src="/admin/includes/google-api/gml-v3.js"></script>
							<link href='/admin/includes/google-api/gml-v3.css' rel='stylesheet' type='text/css' >
						</div>
					</div>
				</div>
	  			<div class="tab-pane" id="races">
					<div class="row-fluid control-group">
						<div class="span9 controls">
							<fieldset>
								<legend> Meeting Races </legend>
							</fieldset>
						</div>
						<div class="span3 controls">
							<a href="javascript:void(0);" class="btn btn-info del-btn" onclick="$('.races').hide();newRace()">Add New Race</a>
						</div>
					</div>
					<div id="races-wrapper">
					{assign var='raceno' value=0}
					{foreach $fields.race as $race}
						{assign var='raceno' value=$raceno+1}
						{include file='form_race.tpl'}
					{/foreach}
					</div>
					
					<div class="row-fluid">
						<input type="hidden" value="{$raceno}" id="raceno">
						<div class="span8 offset4">
							<input type="hidden" id="error" name="error" value="0" />
							<script type="text/javascript">
		
								$(document).ready(function(){
									$('.races').hide();
									$('.entrants').hide();
								});
							
								function newRace(){
									$('body').css('cursor','wait');
									var no = $('#raceno').val();
									no++;
									$('#raceno').val(no);
									$.ajax({
										type: "POST",
									    url: "/admin/includes/processes/load-template.php",
										cache: false,
										data: "template=form_race.tpl&raceno="+no+"&entrantno=0",
										dataType: "html",
									    success: function(data, textStatus) {
									    	try{
									    		$('#races-wrapper').append(data);
									    		$('body').css('cursor','pointer');
											}catch(err){ $('body').css('cursor','pointer'); }
									    }
									});
								}
		
								function toggleRace(ID){
									
									if ($('#race'+ID).is(':visible')){
										$('.races').hide();
									}else{
										$('.races').hide();
										$('#race'+ID).show();
										
									}
									
								}
			
								function newEntrant(race_no){
									$('body').css('cursor','wait');
									var race = new String(race_no);
									var no = $('#entrantno' + race).val();
									no++;
									$('#entrantno' + race).val(no);
									
									$.ajax({
										type: "POST",
									    url: "/admin/includes/processes/load-template.php",
										cache: false,
										data: "template=form_entrant.tpl&raceno="+race_no+"&entrantno="+no,
										dataType: "html",
									    success: function(data, textStatus) {
									    	try{
									    		$('#entrant-wrapper'+race_no).append(data);
									    		$('body').css('cursor','pointer');
											}catch(err){ $('body').css('cursor','pointer'); }
									    }
									});
								}
								function deleteRace(rid){
									if (ConfirmDelete()) {
										var ID = 'race_wrapper'+rid;
										var count = $('#'+ID).attr('rel');
										var today = new Date();
										var dd = today.getDate();
										var mm = today.getMonth()+1;//January is 0!
										var yyyy = today.getFullYear(); 
										var hh = today.getHours();
										var MM = today.getMinutes();
										var ss = today.getSeconds();
										
										html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[3][tbl_race]['+count+'][race_deleted]" />';
										$('#'+ID).append(html);
										var entno = $('.race_entrants'+rid).length;
										
										for ( var i=1; i<=entno;i++){
											var elem = new String(i);
											var del = 'entrant_wrapper' + rid + '-' + elem;
											deleteEntrant(del);
										}
										
										$('#'+ID).css('display','none');
										$('#'+ID).removeClass('races');
									}else{ 
										return false;
									}
									
								}
								function deleteEntrant(ID){
									if (ConfirmDelete()) {
										var count = $('#'+ID).attr('rel');
										var today = new Date();
										var dd = today.getDate();
										var mm = today.getMonth()+1;//January is 0!
										var yyyy = today.getFullYear(); 
										var hh = today.getHours();
										var MM = today.getMinutes();
										var ss = today.getSeconds();
										
										html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[6][tbl_entrant]['+count+'][entrant_deleted]" />';
										$('#'+ID).append(html);
										$('#'+ID).css('display','none');
										$('#'+ID).removeClass('entrants');
		
									}else{ 
										return false;
									}
								}
							
								function validate(){
									$('body').css('cursor','wait');
									var pass = validateForm();
									if(!pass){
										$('body').css('cursor','pointer');
										return false;
									}else{
										$('#Edit_Record').submit();
									}
								}
							</script>
						</div>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<a href="javascript:void(0)" class="btn btn-primary" onClick="validate()">Submit</a>
			</div>
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</form>
	</div>
</div>
{/block}
