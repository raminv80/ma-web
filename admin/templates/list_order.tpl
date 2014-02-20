{block name=body}
 <div class="row form">
	<form class="form-inline" role="form" id="filter-form">
		<div class="form-group">
			<label class="" for="from">From:</label> 
			<input type="text" class="form-control dates" value="{'-1 month'|date_format:"%d/%m/%Y"}" name="from" id="from" required>
		</div>
		<div class="form-group">
			<label class="" for="to">To:</label> 
			<input type="text" class="form-control dates" value="{$smarty.now|date_format:"%d/%m/%Y"}" name="to" id="to" required>
		</div>
		<div class="form-group">
			<label class="" for="status">Status</label> 
			<select class="form-control" name="status" id="status">
				<option value="">ALL</option>
				{foreach $options.status as $opt}
						<option value="{$opt.id}"}>{$opt.value}</option>
				{/foreach} 
			</select>
		</div>
		<a href="javascript:void(0);" onClick="$('#filter-form').submit();" class="btn btn-info" style="margin-left: 10px;">Filter</a>
	</form>
</div> 
<div class="row form">
	<table class="table table-bordered table-striped table-hover" id="orders-table">
		<thead>
			<tr>
				<th colspan="4">{$zone|upper}</th>
			</tr>
		</thead>
		<tbody>
			{if list}
				{foreach $list as $item}
					<tr>
						<td><b>{$item.user.0.user_gname} {$item.user.0.user_surname}</b></td>
						<td><b>{$item.title|date_format:"%e %B %Y - %H:%M:%S"}</b></td>
						<td><b>{getvaluename id=$item.payment.0.order.0.order_status_id options=$options.status}</b></td>
						<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'>Edit</a> {/if}
						</td>
					</tr>
				{/foreach}
			{else}
				<tr>
					<td colspan="4"><b>No orders were found.</b></td>
				</tr>
			{/if}
		</tbody>
	</table>
</div>

<script src="/admin/includes/js/jquery.validate.min.js"></script>
<script>
	if (jQuery.validator) {
		jQuery.validator.setDefaults({
		    debug: false,
		    errorClass: 'has-error',
		    validClass: 'has-success',
		    ignore: "",
		    highlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		      $(element).closest('.form-group').find('.help-block').text('');
		    },
		    errorPlacement: function (error, element) {
		      $(element).closest('.form-group').find('.help-block').text(error.text());
		    },
		    submitHandler: function (form) {
		      if ($(form).valid()) {
		    	  getOrdersFiltered();
		    	  
		      } 
		    }
		});


		jQuery.validator.addClassRules({
			dates: {
				date: true
			}
		});
		
	}

	$(document).ready(function() {

		$("#to").datepicker({
			changeMonth : true,
			changeYear : true,
			dateFormat : "dd/mm/yy",
			maxDate : new Date(),
			onSelect : function(selectedDate) {
				$("#from").datepicker("option", "maxDate", selectedDate);
			}
		});

		$("#from").datepicker({
			changeMonth : true,
			changeYear : true,
			dateFormat : "dd/mm/yy",
			maxDate : new Date(),
			onSelect : function(selectedDate) {
				$("#to").datepicker("option", "minDate", selectedDate);
			}
		});

		$('#filter-form').validate();
	});

	function getOrdersFiltered () {
		$('body').css('cursor','wait');
		var datastring = $("#filter-form").serialize();
		$.ajax({
			type: "POST",
		    url: "/admin/includes/processes/load-orders.php",
			cache: false,
			data: datastring,
			dataType: "html",
		    success: function(data) {
		    	try{
		    		var obj = $.parseJSON(data);
				 	$('#orders-table tbody').html(obj.body);
   					$('body').css('cursor','default');
    			    
				}catch(err){
					$('body').css('cursor','default'); 
					console.log('TRY-CATCH error');
				}
		    },
			error: function(){
				$('body').css('cursor','default'); 
				console.log('AJAX error');
	         	}
		});
	}
</script>

{/block}
