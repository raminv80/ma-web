
$('.modifier').change(function() {
  /* calculatePrice(); */
  calculateAllPrices();
});

$('#promo').bind('keyup', function(event) {
  if(event.keyCode == 13){
    $('#promo').closest('form').submit();
  }
});

$('.gt-zero').change(function() {
  if(parseInt($(this).val()) < 1 || $(this).val() == ''){
    $(this).val('1');
  }
  
});

$('.unsigned-int').keyup(function() {
  if(this.value != this.value.replace(/[^0-9]/g, '')){
    this.value = this.value.replace(/[^0-9]/g, '');
  }
});

function calculatePrice() {
  var price = parseFloat($('#cal-price').attr('value'));
  $('.modifier').each(function() {
    price = price + parseFloat($('option:selected', this).attr('price'));
    
  });
  $('#cal-price').html(price.formatMoney(2, '.', ','));
  $('#price').val(price.formatMoney(2, '.', ','));
}

function calculateAllPrices() {
  $('.product-form-class').each(function() {
    var ID = $(this).attr('data-product-id');
    var price = parseFloat($('#cal-price-' + ID).attr('value'));
    var oldprice = parseFloat($('#cal-oldprice-' + ID).attr('value'));
    $(this).find('.modifier').each(function() {
      price = price + parseFloat($('option:selected', this).attr('price'));
      oldprice = oldprice + parseFloat($('option:selected', this).attr('oldprice'));
    });
    $('#cal-price-' + ID).html(price.formatMoney(0, '.', ','));
    if(price == oldprice){
      $('#cal-oldprice-' + ID).closest('div').hide();
    }else{
      $('#cal-oldprice-' + ID).closest('div').show();
      $('#cal-oldprice-' + ID).html(oldprice.formatMoney(0, '.', ','));
    }
    $('#price-' + ID).val(price.formatMoney(0, '.', ','));
  });
}

var running = false;
function addCart(FORM, REFRESH) {
  if(running){
    return false;
  }
  running = true;
  $('body').css('cursor', 'wait');
  $('.btn-primary').addClass('disabled').attr('disabled', 'disabled');
  var datastring = $('#' + FORM).serialize();
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: datastring,
    dataType: "json",
    success: function(obj) {
      try{
        if(obj.error){
          $('#cart-notification').find('strong').html(obj.error);
          $('#cart-notification').fadeIn('slow');
          $("html, body").animate({
            scrollTop: $('#cart-notification').scrollTop()
          }, '1000', 'swing');
        }else{
         $('#cart-notification').find('strong').html('');
         $('#cart-notification').hide();
         if((obj.url && $(window).width() < 760) || REFRESH){
           window.location.href = obj.url;
         }else{
           $('.nav-itemNumber').html(obj.itemsCount);
           $('.nav-subtotal').html('$' + obj.subtotal);
           $('#shop-cart-btn').html(obj.popoverShopCart);
           $("html, body").animate({
             scrollTop: $('#shop-cart-btn').scrollTop()
           }, '1000', 'swing');
           
           $('#shop-cart-btn').fadeIn(200);
           setTimeout(function() {
             $('#shop-cart-btn').fadeOut(200);
           }, 4000);
         }
        }
      }catch(err){
        console.log('TRY-CATCH error');
      }
      $('body').css('cursor', 'default');
      $('.btn-primary').removeClass('disabled').removeAttr('disabled');
      running = false;
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
      $('.btn-primary').removeClass('disabled');
      running = false;
    }
  });
}

function updateCart() {
  $('body').css('cursor', 'wait');
  var datastring = $("#shopping-cart-form").serialize();
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: 'action=updateCart&' + datastring,
    dataType: "json",
    success: function(obj) {
      try{
        var priceunits = obj.priceunits;
        var pricemodifier = obj.pricemodifier;
        var subtotals = obj.subtotals;
        var totals = obj.totals;
        $('.nav-itemNumber').html(obj.itemsCount);
        $('#shop-cart-btn').html(obj.popoverShopCart);
        /* $('.nav-subtotal').html('$'+obj.totals['subtotal']); */
        if(subtotals){
          $.each(pricemodifier, function(id, value) {
            if(value === "0%"){
              $('#qty-discount-' + id).html('');
            }else{
              if(value)
                $('#qty-discount-' + id).html('(-' + value + ')');
            }
          });
          $.each(priceunits, function(id, value) {
            amount = parseFloat(value);
            $('#priceunit-' + id).html('$' + amount.formatMoney(2, '.', ','));
          });
          $.each(subtotals, function(id, value) {
            amount = parseFloat(value);
            $('#subtotal-' + id).html('$' + amount.formatMoney(2, '.', ','));
          });
          $.each(totals, function(id, value) {
            amount = parseFloat(value);
            if(id == 'subtotal' || id == 'discount'){
              $('#' + id).attr('data-value', amount);
              $('#' + id).html( (id == 'discount' ? '-' : '') + '$' + amount.formatMoney(2, '.', ','));
              if(amount){
                $('#' + id).closest('.row').show(); 
              }
            }
          });
          // renderShippingMethods(obj.shippingMethods);
          updateShipping();
          calculateTotal();
        }else{
          alert('Error: Cannot be updated');
        }
        $('body').css('cursor', 'default');
      }catch(err){
        console.log('TRY-CATCH error - ' + err);
        $('body').css('cursor', 'default');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
    }
  });
}

function deleteItem(ID) {
  $('body').css('cursor', 'wait');
  $('#' + ID).fadeTo("fast", 0.5);
  var frmTkn = $("#formToken").val();
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: 'action=DeleteItem&cartitem_id=' + ID + '&formToken=' + frmTkn,
    dataType: "json",
    success: function(obj) {
      try{
        var response = obj.response;
        var totals = obj.totals;
        $('.nav-itemNumber').html(obj.itemsCount);
        $('.nav-subtotal').html('$' + obj.totals['subtotal']);
        $('#shop-cart-btn').html(obj.popoverShopCart);
        if(response){
          /*
           * ga('ec:addProduct', { 'id': obj.product.id, 'name':
           * obj.product.name, 'category': obj.product.category, 'brand':
           * obj.product.brand, 'variant': obj.product.variant, 'price':
           * obj.product.price, 'quantity': obj.product.quantity });
           * ga('ec:setAction', 'remove');
           * 
           * var fullname = obj.product.name; if(obj.product.variant){ fullname += ' | ' +
           * obj.product.variant } ga('send', 'event', 'Remove from Cart',
           * 'click', fullname);
           */

          if(parseInt(obj.itemsCount) > 0){
            $('#' + ID).hide('slow');
            $.each(totals, function(id, value) {
              amount = parseFloat(value);
              $('#' + id).html('$' + amount.formatMoney(2, '.', ','));
            });
          }else{
            location.reload();
          }
        }else{
          $('#' + ID).fadeTo("fast", 1);
          alert('Item cannot be deleted');
        }
      }catch(err){
        $('#' + ID).fadeTo("slow", 1);
        console.log('TRY-CATCH error');
      }
      $('body').css('cursor', 'default');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('#' + ID).fadeTo("slow", 1);
      console.log('AJAX error:' + errorThrown);
      $('body').css('cursor', 'default');
    }
  });
}

function checkout2(form) {
  $('body').css('cursor', 'wait');
  var datastring = $("#" + form).serialize();
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: datastring,
    dataType: "json",
    success: function(obj) {
      try{
        if(obj.response){
          $('.checkout2').hide();
          $('.checkout3').show();
          $('#billing-summary').html(obj.billing);
          $('#shipping-summary').html(obj.shipping);
          setCCRequired(true);
          scrolltodiv('#checkout3-form');
          
          var shippingOption = 'Standard';
          /*
           * ga('ec:setAction', 'checkout_option', { 'step': 1, 'option':
           * shippingOption }); ga('send', 'event', 'Checkout', 'Shipping',
           * shippingOption); ga('ec:setAction','checkout', { 'step': 2 });
           * ga('send', 'pageview');
           */
        }
      }catch(err){
        console.log('TRY-CATCH error');
      }
      $('body').css('cursor', 'default');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
    }
  });
  
}

// function calculateTotal(str) {
// var value = parseFloat(str);
// $('#shipping-fee-value').html('$'+ value.formatMoney(2, '.', ','));
// var total = parseFloat($('#total').attr('amount')) + value ;
// $('.total-amount').html('$'+ total.formatMoney(2, '.', ','));
// var gst = (parseFloat($('#gst').attr('amount')) + value ) /10 ;
// $('#gst').html('$'+ gst.formatMoney(2, '.', ','));
// }

function addProductCart(ID, QTY, PRICE) {
  $('body').css('cursor', 'wait');
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: "action=ADDTOCART&product_id=" + ID + "&quantity=" + QTY + "&price=" + PRICE,
    dataType: "json",
    success: function(obj) {
      try{
        if(obj.url){
          window.location.href = obj.url;
        }
        
      }catch(err){
        console.log('TRY-CATCH error');
      }
      $('body').css('cursor', 'default');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
    }
  });
}

$('.quantity').change(function() {
  updateCart();
});

function renderShippingMethods(OPT) {
  if($('#shippingMethod').get(0).tagName == "select"){
    $('#shippingMethod').empty();
    $.each(OPT, function(id, value) {
      $('#shippingMethod').append($('<option>', {
        value: value,
        text: id
      }));
    });
  }
}

$('#shippingMethod').change(function() {
  var price = parseFloat($(this).val());
  // $('#shipping-fee').html('$'+ price.formatMoney(2, '.', ','));
  calculateTotal();
});

function updateShipping() {
  
  return true;
  //**************** THE BELOW IS NOT BEING USED! ****************
  var element = "#postcodesh";
  var postcode = $("#postcodesh").val();
  if($("#chksame:checked") && $("#chksame:checked").length == 1 || postcode == undefined){
    postcode = $("#postcode-field").val();
    element = "#postcode-field";
  }
  if(postcode.length >= 4){
    $('body').css('cursor', 'wait');
    
    var datastring = postcode;
    $.ajax({
      type: "POST",
      url: "/process/cart",
      cache: false,
      data: 'action=updatePostage&postcode=' + datastring,
      dataType: "json",
      success: function(obj) {
        try{
          var postagefee = obj.postagefee;
          if(isArray(postagefee)){
            // Iterate the array and do stuff
            // if (postagefee.length == 1) {
            var amount = parseFloat(postagefee[0].postage_price);
            $('#shippingMethod').val(amount);
            $('#shipMethod').val(postagefee[0].postage_name);
            $('#postageID').val(postagefee[0].postage_id);
            calculateTotal();
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(element).closest('.form-group').find('.help-block').html('');
            $('.process-cnt').show();
            $('.postcode-invalid').html("");
            // } else {
            // alert('Error: Cannot be updated');
            // }
          }else{
            if(postagefee){
              var amount = parseFloat(postagefee.postage_price);
              $('#shippingMethod').val(amount);
              $('#shipMethod').val(postagefee.postage_name);
              $('#postageID').val(postagefee.postage_id);
              $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
              $(element).closest('.form-group').find('.help-block').html('');
              $('.process-cnt').show();
              $('.postcode-invalid').html("");
            }else{
              $('#shippingMethod').val('');
              $('#shipMethod').val('');
              $('#postageID').val('');
              $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
              $(element).closest('.form-group').find('.help-block').html('Invalid postcode');
              $('.process-cnt').hide();
              $('.postcode-invalid').html("Sorry, we are not shipping to '" + postcode + "'<br> Please <a href='/contact-us'>contact us</a> for more information.");
            }
            calculateTotal();
          }
        }catch(err){
          console.log('TRY-CATCH error - ' + err);
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }
}

function calculateTotal() {
  var subtotal = parseFloat($('#subtotal').attr('data-value'));
  var fee = parseFloat($('#shippingMethod').attr('data-value'));
  var discount = parseFloat($('#discount').attr('data-value'));
  var total = subtotal + fee - discount;
  $('#total').html('$' + total.formatMoney(2, '.', ','));
  
  return true;
  //**************** THE BELOW IS NOT BEING USED! ****************
  var fee = $('#shippingMethod').val();
  if(fee){ 
    fee = parseFloat(fee); 
    if($('#shipMethod').val() && fee == 0){
      $('#shipping-fee').html('FREE'); }
    else{ 
      $('#shipping-fee').html('$'+fee.formatMoney(2, '.', ',')); }
  }else{ 
    fee = parseFloat(0);
    $('#shipping-fee').html('$'+ fee.formatMoney(2, '.', ',')); 
  }
   
  var total = subtotal + fee;
  $('#total').html('$' + total.formatMoney(2, '.', ','));
  $('#shipMethod').val($('#shippingMethod option:selected').text()); 
};

function scrolltodiv(id) {
  $('html,body').animate({
    scrollTop: $(id).offset().top
  });
}
function isArray(what) {
  return Object.prototype.toString.call(what) === '[object Array]';
}
Number.prototype.formatMoney = function(c, d, t) {
  var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "." : d, t = t == undefined ? "," : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};




/***************** WISH LIST  *******************/
$('.prodwishlist').click(function() {
  var ID = $(this).attr('data-pid'); 
  if($(this).hasClass('active')){
    deleteProductWishList(ID);
  }else{
    addProductWishList(ID);
  }
});

var RunningWishList = false;

function addProductWishList(ID) {
  if(RunningWishList || !ID){ return false; }
  RunningWishList = true;
  $('body').css('cursor', 'wait');
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: "action=addProductWishList&product_object_id=" + ID,
    dataType: "json",
    success: function(obj) {
      try{
        if(obj.url){
          window.location.href = obj.url;
        }else if(obj.success){
          $('.prodwishlist-'+ID).addClass('active');
          $('.prodwishlist-'+ID).find('img').attr('src','/images/prod-wishlist-selected.png');
        }else if(obj.error){
          
        }
        RunningWishList = false;
      }catch(err){
        console.log('TRY-CATCH error');
        RunningWishList = false;
      }
      $('body').css('cursor', 'default');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
    }
  });
}


function deleteProductWishList(ID) {
  if(RunningWishList || !ID){ return false; }
  RunningWishList = true;
  $('body').css('cursor', 'wait');
  $.ajax({
    type: "POST",
    url: "/process/cart",
    cache: false,
    data: "action=deleteProductWishList&product_object_id=" + ID,
    dataType: "json",
    success: function(obj) {
      try{
        if(obj.url){
          window.location.href = obj.url;
        }else if(obj.success){
          $('.prodwishlist-'+ID).removeClass('active');
          $('.prodwishlist-'+ID).find('img').attr('src','/images/prod-wishlist.png');
          //only on wish-list page
          if($('#wish-list').length){
            $('.prodwishlist-'+ID).closest('.prodout').remove();
            if($('#wish-list .prodout').length < 1){
              $('#noproducts').show();
            }
            try{
              $("#products-wrapper").isotope('reloadItems').isotope();
            }catch(err){}
          }
        }else if(obj.error){
          
        }
        RunningWishList = false;
      }catch(err){
        console.log('TRY-CATCH error');
        RunningWishList = false;
      }
      $('body').css('cursor', 'default');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $('body').css('cursor', 'default');
      console.log('AJAX error:' + errorThrown);
    }
  });
}


/******************* AUTOCOMPLETE SEARCH  *******************/
var ajaxtimestamp = null;
function RunAutocomplete(){
 $('body').css('cursor', 'wait');
 var query = $("#search").val();
 if(query.length > 3){
  ajaxtimestamp = new Date().getTime();
  $.ajax({
   type : "POST",
   url : "/process/autocomplete",
   cache : false,
   data : 'action=product&q='+query+'&timestamp='+ajaxtimestamp,
   dataType : "json",
   success : function(obj, textStatus) {
    try {
     if (obj.success && ajaxtimestamp == obj.timestamp){
       $('#autocomplete-search-results').html(obj.template);
      }
     if(!obj.success && obj.error){
      $('#autocomplete-search-results').html('<h4>'+obj.error+'</h4>');
     }
     $('#autocomplete-search-results').show();
    } catch (err) {}
    $('body').css('cursor', 'default');
   },
   error: function(jqXHR, textStatus, errorThrown){
    $('body').css('cursor','default'); 
    //alert(textStatus+': '+errorThrown);
    console.log('AJAX error:'+errorThrown);
    }
  });
 }else{
   $('#autocomplete-search-results').hide();
 }
}
var autocomplete;
function triggerAutocomplete() {
 clearTimeout(autocomplete);
 autocomplete = setTimeout(function(){ RunAutocomplete() }, 2000);
}
