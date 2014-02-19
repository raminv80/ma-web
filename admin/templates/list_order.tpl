{block name=body}
<!-- <div class="row form">
	<label for="from">From</label>
	<input type="text" id="from" name="from">
	<label for="to">to</label>
	<input type="text" id="to" name="to">
</div> -->
<div class="row">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th colspan="3">{$zone|upper}</th>
			</tr>
		</thead>
		<tbody>
			{foreach $list as $item}
				<tr>
					<td><b>{$item.user.0.user_gname} {$item.user.0.user_surname}</b></td>
					<td><b>{$item.title|date_format:"%e %B %Y - %H:%M:%S"}</b></td>
					<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-info pull-right'>View</a> {/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>

<script>

$(document).ready(function(){
	
	
	$("#to").datepicker({
		changeMonth : true,
		dateFormat: "dd/mm/yy",
		setDate : new Date(),
		maxDate : new Date(),
		onClose : function(selectedDate) {
			$("#from").datepicker("option", "maxDate", selectedDate);
		}
	});


	$("#from").datepicker({
		changeMonth : true,
		dateFormat: "dd/mm/yy",
		maxDate : new Date(),
		onClose : function(selectedDate) {
			$("#to").datepicker("option", "minDate", selectedDate);
		}
	});
});

</script>

{/block}
