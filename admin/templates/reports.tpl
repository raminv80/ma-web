{block name=body}
<div class="form well">
  <div class="row">
    <div class="col-sm-12">
      <form class="form-horizontal" role="form" id="report-form" action="" method="post">
        <div class="row form-group">
          <label class="col-sm-2 control-label" for="from">From:</label>
          <div class="col-sm-5">
            <input type="text" class="form-control dates" value="{'-1 month'|date_format:'%d/%m/%Y'}" name="start" id="from" required>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label" for="to">To:</label>
          <div class="col-sm-5">
            <input type="text" class="form-control dates" value="{$smarty.now|date_format:'%d/%m/%Y'}" name="end" id="to" required>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label" for="to">Report:</label>
          <div class="col-sm-5">
            <select class="form-control" name="report" id="report-name">
              <option value="get-autorenewal-csv">Auto renewal</option>
              <option value="get-gift-certificates-csv">Gift certificates</option>
              <option value="get-hae-csv">HAE members (Benevolent program)</option>
              <option value="get-ndis-csv">NDIS Submissions</option>
              <option value="get-order-resources-csv">Order resources</option>
              <option value="get-partial-registrations-csv">Partial registrations</option>
              <option value="get-survey-csv">Surveys</option>
            </select>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" value="submit" class="btn btn-success">Download</button>
          </div>
        </div> 
      </form>
    </div>
  </div>
</div>
<script src="/admin/includes/js/jquery.validate.min.js"></script>
<script>
  if(jQuery.validator){
    jQuery.validator.setDefaults({
      debug: false,
      errorClass: 'has-error',
      validClass: 'has-success',
      ignore: "",
      highlight: function(element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(element).closest('.form-group').find('.help-block').text('');
      },
      errorPlacement: function(error, element) {
        $(element).closest('.form-group').find('.help-block').text(error.text());
      }
      
    });
    
  }

  $(document).ready(function() {
    
    $("#to").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy",
      maxDate: new Date(),
      onSelect: function(selectedDate) {
        $("#from").datepicker("option", "maxDate", selectedDate);
      }
    });
    
    $("#from").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy",
      maxDate: new Date(),
      onSelect: function(selectedDate) {
        $("#to").datepicker("option", "minDate", selectedDate);
      }
    });
    
    $('#report-form').validate({
      submitHandler: function(form) {
        var actionPath =  '/admin/includes/processes/reports/' + $('#report-name').val() + '.php';
        $(form).attr('action', actionPath);
        form.submit();
      }
    });
    
    
  });

</script>

{/block}
