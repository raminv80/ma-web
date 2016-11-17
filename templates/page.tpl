<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
<meta name="description" content="{$product_meta_description} {$listing_meta_description}" />
<meta name="keywords" content="{$product_meta_words} {$listing_meta_words}" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=11; IE=edge">
<meta name="distribution" content="Global" />
<meta name="author" content="{if $COMPANY.name}{$COMPANY.name}{else}Them Advertising{/if}" />
{if $staging || $listing_noindex eq 1}
<meta name="robots" content="noindex,nofollow" />
{else}
<meta name="robots" content="index,follow" />
{/if}
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<title>{$product_seo_title}{$listing_seo_title}</title>
<link href="/images/favicon.ico" type="image/png" rel="shortcut icon">

<!-- for Facebook -->
<meta property="og:title" content="{if $listing_share_title || $product_share_title}{$listing_share_title}{$product_share_title}{else}{$product_seo_title}{$listing_seo_title}{/if}" />
<meta property="og:type" content="article" />
<meta property="og:image" content="{$DOMAIN}{if $listing_share_image || $product_share_image}{$listing_share_image}{$product_share_image}{elseif $gallery && $gallery.0.gallery_link}{$gallery.0.gallery_link}{elseif $listing_image}{$listing_image}{else}/images/logo.png{/if}"/>
<meta property="og:url" content="{$DOMAIN}{$REQUEST_URI}" />
<meta property="og:description" content="{if $listing_share_text || $product_share_text}{$listing_share_text}{$product_share_text}{else}{$product_meta_description}{$listing_meta_description}{/if}" />

<!-- for Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="">
<meta name="twitter:title" content="{if $listing_share_title || $product_share_title}{$listing_share_title}{$product_share_title}{else}{$product_seo_title}{$listing_seo_title}{/if}" />
<meta name="twitter:description" content="{if $listing_share_text || $product_share_text}{$listing_share_text}{$product_share_text}{else}{$product_meta_description}{$listing_meta_description}{/if}" />
<meta name="twitter:creator" content="@THEMAdvertising">
<meta name="twitter:image:src" content="{$DOMAIN}{if $listing_share_image || $product_share_image}{$listing_share_image}{$product_share_image}{elseif $gallery && $gallery.0.gallery_link}{$gallery.0.gallery_link}{elseif $listing_image}{$listing_image}{else}/images/logo.png{/if}"/>
<meta name="twitter:domain" content="{$DOMAIN}">

<!-- Schema.org -->
<meta itemprop="name" content="{$product_seo_title}{$listing_seo_title}">
<meta itemprop="description" content="{$product_meta_description} {$listing_meta_description}">
<meta itemprop="image" content="{$DOMAIN}/images/logo.png">

{printfile file='/includes/css/styles.css' type='style'}


<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script type="text/javascript" src="/includes/js/html5shiv.js"></script>
      <script type="text/javascript" src="/includes/js/respond.min.js"></script>
      <script type="text/javascript" src="/includes/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
 <![endif]-->
{block name=head}{/block}

{if $ga_id}
	<script>
	(function(i,s,o,g,r,a,m){ i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '{$ga_id}', 'auto', { 'siteSpeedSampleRate': 50 });
	ga('require', 'displayfeatures');
	ga('require', 'linkid', 'linkid.js');
	ga('require', 'ec');
	ga('set', '&cu', 'AUD');
	ga('send', 'pageview');
	</script>
{/if}

{if $gtm_id}
<script>(function(w,d,s,l,i){ w[l]=w[l]||[];w[l].push({ 'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$gtm_id}');</script>
{/if}

</head>
<body>
{if $gtm_id}
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$gtm_id}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
{/if}
<header>
  {include file='menu.tpl'}
</header>
	{block name=body}{/block}
	{include file='footer.tpl'}
  
  <div id="myModalSession" class="modal fade session-modal" tabindex="-1" role="dialog" aria-labelledby="myModalSession" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span>
        <div class="small">CLOSE</div></button>
      </div>
      <div class="modal-body text-center">
          <div class="session-modal-txt">Your session will expire in <b id="timer-counter">5 minutes</b></div>
          <button class="btn-red btn" onclick="ExtendSession();">Click here to extend it</button>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

  {$conversionTracking}
    
	{printfile file='/includes/js/jquery-2.1.4.min.js' type='script'}
	{printfile file='/includes/js/bootstrap.min.js' type='script'}
    {printfile file='/includes/js/jquery.validate.min.js' type='script'}
	{printfile file='/includes/js/shopping-cart.min.js' type='script'}
	{printfile file='/includes/js/custom.min.js' type='script'}
  
	<script type="text/javascript">
	if (jQuery.validator) {
	  jQuery.validator.setDefaults({
	    errorClass: 'has-error',
	    validClass: 'has-success',
	    ignore: "",
	    highlight: function (element, errorClass, validClass) {
	      //$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	      $(element).closest('.form-group').addClass('has-error');
	      $(element).closest('form').find('#form-error').html('Error, please check the highlighted fields.').show();
	      $(element).closest('form').find('.error-textbox').html('Error, please check the highlighted fields.').show();
	      if($('#accordion.validateaccordion').length){
	        $('#accordion.validateaccordion').accordion( "option", "active", 99 );
	        ValidateFormWithAccordion($(element).closest('form'));
	        $('html,body').animate({
           scrollTop : $(element).closest('form').offset().top
          });
	        
	        
	        //OPEN TAB AND FOCUS ON ELEMENET
	        // var setopt = $('div.acc-body').index( $(element).closest('div.acc-body') );
	        // $('#accordion.validateaccordion').accordion( "option", "active", setopt );
	        //  $('html,body').animate({
	 	      //  		scrollTop : $(element).offset().top
	 	      //  	});
	      }
	    },
	    unhighlight: function (element, errorClass, validClass) {
	      //$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	      $(element).closest('.form-group').removeClass('has-error');
          $(element).closest('.form-group').find('.help-block').text('');
   	      if(!$(element).closest('form').find('.form-group.has-error').length){
   	        $(element).closest('form').find('#form-error').html('').hide();
   	        $(element).closest('form').find('.error-textbox').html('').hide();
   	      }else{
   	        if($('#accordion.validateaccordion').length){
   	          ValidateFormWithAccordion($(element).closest('form'));
   	        }
   	      }
   	      
	    },
	    errorPlacement: function (error, element) {
			$(element).closest('.form-group').find('.help-block').text(error.text());
	    },
	    submitHandler: function (form) {
		      $('.error-textbox').hide();
	    	  var formID = $(form).attr('id');
	    	  var formCheck = $(form).attr('data-attr-id');
	    	  if(formCheck == undefined || formCheck == ""){
	    		  formCheck = $(form).attr('id');
	    	  }
	          switch ( formCheck ) {
	        	case 'form-': 
	          		break;
	          		
	          	default:
	          		form.submit();
	          }
		    }
	  });
	  
	  jQuery.validator.addMethod(
	 	  		"hasLowercase", 
	 	  		function(value, element) {
	 	  		  var validStr = /[a-z]/;
	 	  		  return value == '' || validStr.test(value)
	 			}, 
	 			"Must include at least one lower case character"
	 	);
	 	
	 	jQuery.validator.addMethod(
	 	  		"hasUppercase", 
	 	  		function(value, element) {
	 	  		  var validStr = /[A-Z]/;
	 	  		  return value == '' || validStr.test(value)
	 			}, 
	 			"Must include at least one upper case character"
	 	);

	 	jQuery.validator.addMethod(
	 	  		"hasDigit", 
	 	  		function(value, element) {
	 	  		  var validStr = /\d/;
	 	  		  return value == '' || validStr.test(value)
	 			}, 
	 			"Must include at least one number/digit"
	 	);

	 	jQuery.validator.addMethod(
	 	  		"hasSpecialChar", 
	 	  		function(value, element) {
	 	  		  var validStr = /[!@#\$%\^&*)(\-._=+]/;
	 	  		  return value == '' || validStr.test(value)
	 			}, 
	 			"Must include at least one special character: !@#$%^&*)(-._=+"
	 	);	
	  
	}
	
	function ValidateFormWithAccordion(ELEMENT){
	  ELEMENT.find('div.acc-body').each(function(){
     var errorCnt = $(this).find('.form-group.has-error').length;
     $(this).attr('data-error', errorCnt);
     var errorTabMsg = errorCnt > 0 ? '<span class="acc-tab-error">' + errorCnt + ' error'+ (errorCnt > 1 ? 's' : '') +'</span>' : '';
     var curTab = $('#accordion.validateaccordion h3[aria-controls="' + $(this).attr('id') + '"]');
     if(errorTabMsg){
       if(curTab.find('.acc-tab-error').length){
         curTab.find('.acc-tab-error').html(errorTabMsg);
       }else{
         curTab.append(errorTabMsg);  
       }
     }else{
       curTab.find('.acc-tab-error').remove();
     }
   });
	}
	
	//EXPIRY SESSION TIMER
	var sessionWarningTime = 60000;//1440000; //24min
	var sessionTimer;
	var startTimer = 15;//301; //5mins + 1sec
	var timer = startTimer;
	var sessionRedirectURL = '/process/user?logout=true';
	
	function InitSessionTimer(){
	  sessionTimer = setTimeout(function() {
     $('#myModalSession').modal('show');
     timer = startTimer;
     CountDown($('#timer-counter'));
     console.log('session-timer init');
   }, sessionWarningTime); 
	}
	
	function ExtendSession(){
	  $('#myModalSession .btn').attr('disabled', 'disabled');
	  $.ajax({
     type: "POST",
     url: "/my-account",
     cache: false,
     dataType: "html",
     success : function(obj, textStatus) {
       clearTimeout(sessionTimer);
       InitSessionTimer();
       timer = -1;
	     $('#myModalSession').modal('hide');
	     $('#myModalSession .btn').removeAttr('disabled');
	   },
     error: function(jqXHR, textStatus, errorThrown) {
       console.log('AJAX error:' + errorThrown);
     }
   });
	}
	
	function CountDown(ELEMENT) {
	  if (timer > 0) {
	    --timer; 
       ELEMENT.html( timer + ' secs.');
       setTimeout(function(){
         CountDown(ELEMENT);
       }, 1000);
   } else if(timer == 0) {
     window.location = sessionRedirectURL;
   }
  }
	
	$(document).ready(function(){
	  
	  	$('#newsl_form').validate();

		$('#searchbox').bind('keyup', function(event) {
		    if(event.keyCode==13){
		    	$('#search-form').submit();
		    }
		});
		$('.sb-icon-search').click(function() {
		 	setTimeout(function() {
		    $('#search').focus();
      		}, 1000);	
		  });
		
		$('#search').blur(function(){
		  if(!$('#autocomplete-search-results').is(":focus") && $(window).width() > 992){
		  setTimeout(function() {
		    $('#autocomplete-search-results').fadeOut('slow');
      		}, 1000);
		  }
		});

		$('#cart').hover(function() {
		  $(this).find('.dropdown-menu:hidden').fadeIn(200);
		}, function() {
		  $(this).find('.dropdown-menu:visible').fadeOut(200)
		});
		
		{if $user.id || $new_user}
		InitSessionTimer();
		{/if}
	});


	</script>
	{block name=tail}{/block}
    
</body>
</html>