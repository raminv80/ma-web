{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="pagehead">
  <div class="bannerout">
    <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1>{$listing_name}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>


<div id="giftgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>Make a donation to MedicAlert Foundation</h3>
        <p>Please select a donation amount:</p>

        <form id="gift_form" accept-charset="UTF-8" method="post" action="/process/cart">
          <input type="hidden" name="action" value="quickcheckout" />
          <input type="hidden" name="product_id" value="{$products.product_object_id}" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            <div class="col-sm-12 text-center form-group">
			{foreach $products.variants as $v}
			<div class="donbtn">
			  	<label for="variant-{$v.variant_id}">
                    <input type="radio" value="{$v.variant_id}" {if $post.variant_id eq $v.variant_id}checked="checked"{/if} data-value="{$v.variant_price|number_format:0:'.':','}" class="{if $v.variant_editableprice eq 1}show-otherval{/if}" name="variant_id" id="variant-{$v.variant_id}" required>
                    <input type="hidden" disabled value="{$v.attr_value_id}" name="attr[{$v.attribute_id}][id]" id="attribute_id-{$v.variant_id}" class="variant-attributes"/>
                	<div id="variant-{$v.variant_id}-btn" class="donate-btn btn btn-grey {if $post.variant_id eq $v.variant_id}active{/if}">{if $v.variant_editableprice eq 1}Other{else}${$v.variant_price|number_format:0:'.':','}{/if}</div>
                </label>
            </div>
            {/foreach}
            <div class="help-block"></div>
            </div>
          </div>
          <br />
          <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
              <div class="fields-wrapper" {if !$post}style="display:none"{/if}>
                <div class="row">
                  <div class="col-sm-12 form-group" id="otheram">
                    <label class="visible-ie-only" for="amount">Please only specify a whole dollar amount<span>*</span>:</label>
                    <input class="form-control" value="{$post.price}" type="text" pattern="[0-9]" name="price" id="price" required="">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="fname">
                      First name<span>*</span>:
                    </label>
                    <input class="form-control" value="{$post.name}" type="text" name="name" id="fname" required="">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="lname">
                      Last name<span>*</span>:
                    </label>
                    <input class="form-control" value="{$post.lastname}" type="text" name="lastname" id="lname" required="">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="email">
                      Your email<span>*</span>:
                    </label>
                    <input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="pcode">
                      Postcode<span>*</span>:
                    </label>
                    <input class="form-control" value="{$post.postcode}" type="text" name="postcode" id="postcode" required="">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <br />
                <br />
                <div class="row">
                  <div class="col-sm-12">
                    <h3>Payment information</h3>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <br>Payment accepted: <img src="/images/gift-cards.jpg" alt="Payment accepted" title="Payment accepted" id="accepted" />
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="ccno">
                      Card number<span>*</span>:
                    </label>
                    <input type="text" id="ccno" class="form-control" name="cc[number]" autocomplete="off" required />
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="ccname">
                      Cardholder's name<span>*</span>:
                    </label>
                    <input type="text" id="ccname" class="form-control" name="cc[name]" autocomplete="off" required />
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="ccmonth">
                      Expiry<span>*</span>:
                    </label>
                    <div class="row">
                      <div class="col-sm-6">
                        <select id="ccmonth" name="cc[month]" class="form-control" required>
                          <option value="">Month</option>
                          <option value="01">1 - Jan</option>
                          <option value="02">2 - Feb</option>
                          <option value="03">3 - Mar</option>
                          <option value="04">4 - Apr</option>
                          <option value="05">5 - May</option>
                          <option value="06">6 - Jun</option>
                          <option value="07">7 - Jul</option>
                          <option value="08">8 - Aug</option>
                          <option value="09">9 - Sep</option>
                          <option value="10">10 - Oct</option>
                          <option value="11">11 - Nov</option>
                          <option value="12">12 - Dec</option>
                        </select>
                        <div class="error-msg help-block"></div>
                      </div>
                      <div class="col-sm-6">
                        <select id="ccyear" name="cc[year]" class="cc-select-req form-control">
                          {assign var=thisyear value=$smarty.now|date_format:"%Y"} {assign var=numyears value=$thisyear+20}
                          <option value="">Year</option>
                          {for $year=$thisyear to $numyears}
                          <option value="{$year}">{$year}</option>
                          {/for}
                        </select>
                        <div class="error-msg help-block"></div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="cccsv">
                      Security code<span>*</span> <img src="/images/question-mark.png" alt="The three-digit number on the signature panel on the back of the card." title="The three-digit number on the signature panel on the back of the card." data-toggle="tooltip" data-placement="top" /> :
                    </label>
                    <div>
                      <input type="text" id="cccsv" name="cc[csv]" class="seccode form-control" autocomplete="off" pattern="[0-9]" required/>
                      <img class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                    </div>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 text-center">
                    <br />
                    <p>
                      By selecting 'Donate now' you are agreeing to MedicAlert Foundation's <a href="/terms-and-conditions">terms &amp; conditions</a> and <a href="/privacy-policy">privacy policy</a>.
                    </p>
                    <br />
                  </div>
                </div>
                <div style="height: 0; overflow: hidden;">
                  <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off" >
                </div>
                <div class="row error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="submit" value="Donate now" class="btn-red btn" id="fbsub">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="cost-grey" class="howto donate">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        {$listing_content3} <br />
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 text-center ways">
        <img src="/images/access-email.png" alt="Email" class="img-responsive" />
        <div class="grey-text">
          <span class="bold">Online</span><span>*</span><br /> <a href="#giftgrey">Donate online now</a>
        </div>
      </div>
      <div class="col-sm-4 text-center ways">
        <img src="/images/join-post.png" alt="Post" class="img-responsive" />
        <div class="grey-text">
          <span class="bold">Mailing a cheque or money order payable to:</span><br /> {$COMPANY.name}<br /> {$COMPANY.address.street}<br /> {$COMPANY.address.suburb} {$COMPANY.address.state} {$COMPANY.address.postcode}
        </div>
      </div>
      <div class="col-sm-4 text-center ways">
        <img src="/images/access-phone.png" alt="Phone" class="img-responsive" />
        <div class="grey-text">
          <span class="bold">Calling our Membership Services team </span><br /> during business hours on <a class="tel" href="tel:{$COMPANY.toll_free}" title="Click to call">{$COMPANY.toll_free}</a>. Please have your credit card details ready.
        </div>
      </div>

    </div>
  </div>
</div>

<div class="emergency-grey">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h3>Gifts to the MedicAlert Foundation in your Will</h3>
				<a href="/gifts-in-your-will" class="btn btn-red">Learn more</a>
			</div>
		</div>
	</div>
</div>

{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();

    $('#gift_form').validate({
      submitHandler: function(form) {
        $('#fbsub').val('Processing...').attr('disabled','disabled');
      	form.submit();
      }
    });

    $('#price').rules("add", {
      required: true,
      digits: true,
      max: 1000
    });

    $('#ccno').rules("add", {
      creditcard : true,
    });

    $('#cccsv').rules("add", {
      digits: true,
      minlength: 3
    });

    $("select").selectBoxIt();

  /*   $("input[name=variant_id]").change(function(){
      //Set attribute
      $('.variant-attributes').attr('disabled', 'disabled');
      $('#attribute_id-' + $(this).val()).removeAttr('disabled');

      if($("input[name=variant_id]:checked").hasClass('show-otherval')){
        $("#otheram").show();
        $("#price").val('');
      }else{
        $("#price").val( $("input[name=variant_id]:checked").attr('data-value') );
        $("#otheram").hide();
      }
      $('#fields-wrapper').fadeIn();
    }); */


    $('input[name="variant_id"]').change(function(){
      $('.donate-btn').removeClass('active');

      //Set attribute
      $('.variant-attributes').attr('disabled', 'disabled');
      $('#attribute_id-' + $(this).val()).removeAttr('disabled');

      //Show/hide/highligth content based on selection
      $('#variant-' + $(this).val() + '-btn').addClass('active');
      if($("input[name=variant_id]:checked").hasClass('show-otherval')){
        $("#otheram").show();
        $("#price").val('');
      }else{
        $("#price").val( $("input[name=variant_id]:checked").attr('data-value') );
        $("#otheram").hide();
      }
      $('.fields-wrapper').fadeIn();
    });


  });
</script>
{/block}
