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
		<a href="javascript:void(0);" onClick="$('#filter-form').submit();" class="btn btn-info" style="margin-left: 10px;">Filter</a>
		<a href="javascript:void(0);" onClick="getCSV();" class="btn btn-info" style="margin-left: 10px;">Get CSV file</a>
	</form>
	<form id="csv-form" action="/admin/includes/processes/get-orders-csv.php" method="post">
		<input type="hidden" value="" name="from" id="csv-from">
		<input type="hidden" value="" name="to" id="csv-to">
	</form>
</div> 
<div class="row form">
	<table class="table table-bordered table-striped table-hover" id="orders-table">
		<thead>
			<tr>
				<th colspan="3">{$zone|upper}</th>
				<th>
					<select class="form-control" name="status" id="status">
						<option value="0">All Status</option>
						{foreach $options.status as $opt}
								<option value="{$opt.id}"}>{$opt.value}</option>
						{/foreach} 
					</select>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{include file='orders.tpl'}
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
		    	  getOrdersFiltered();
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

		$('#status').change(function(){
			if ( $(this).val() == 0 ) {
				$('.order').show();
			} else {
				$('.order').hide();
				$('.'+ $(this).val() ).show();
			}
			checkVisible();
		});
		
	});

	function getCSV(){
		$('#csv-from').val( $('#from').val() );
		$('#csv-to').val( $('#to').val() );
		$('#csv-form').submit();
	}

	
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
		    		$('#status').val('0');
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

	function checkVisible() {
		if ( $('.order:visible').length > 0 ) {
			$('.no-orders').hide();
		} else {
			$('.no-orders').show();
		}
	}
</script>

{/block}
