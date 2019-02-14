{block name=body}
<div class="row form">
  <h2>{$zone|upper}</h2>
  <form class="form-inline well" role="form" id="search-form">
    <div class="form-group">
      <label class="" for="from">Membership No. or discount code: </label>
      <input type="text" class="form-control" placeholder="" value="" name="search" id="search" required>
    </div>
    <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button> 
  </form>
  <form id="csv-form" action="/admin/includes/processes/get-orders-csv.php" method="post">
    <input type="hidden" value="" name="from" id="csv-from">
    <input type="hidden" value="" name="to" id="csv-to">
  </form>
</div>
<div class="row form" id="main-content" style="display:none">
  
  <table class="table table-bordered table-striped table-hover" id="vouchers-table">
    <thead>
      <tr>
        <th>Membership No.</th> 
        <th>Code</th>
        <th>Start date</th> 
        <th>End date</th>
        <th>Usage</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
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
      },
      submitHandler: function(form) {
        getVouchers();
      }
    });
    
  }

  $(document).ready(function() {
    $('#search-form').validate();
  });
  
  function getCSV() {
    $('#csv-from').val($('#from').val());
    $('#csv-to').val($('#to').val());
    $('#csv-form').submit();
  }

  function getVouchers() {
    $('body').css('cursor', 'wait');
    var datastring = $("#search-form").serialize();
    $.ajax({
      type: "POST",
      url: "/admin/includes/processes/load-vouchers.php",
      cache: false,
      data: datastring,
      dataType: "json",
      success: function(obj, textStatus) {
        try{
          $('#main-content').show();
          $('#vouchers-table tbody').html(obj.body);
          $('body').css('cursor', 'default');
          
        }catch(err){
          $('body').css('cursor', 'default');
          console.log('TRY-CATCH error');
        }
      },
      error: function() {
        $('body').css('cursor', 'default');
        console.log('AJAX error');
      }
    });
  }

  function checkVisible() {
    if($('.order:visible').length > 0){
      $('.no-orders').hide();
    }else{
      $('.no-orders').show();
    }
  }
</script>

{/block}
