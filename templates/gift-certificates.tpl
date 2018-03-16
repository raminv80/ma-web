{block name="head"}
{printfile file='/includes/css/jquery-ui.css' type='style'}
{/block} {block name=body}
<style>
.row.notice {
    padding: 20px 0;
}
</style>
<div id="pagehead">
  <div class="bannerout">
    <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-md-offset-2 text-center">
        <h1>{$listing_title}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="giftgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>Purchase your gift certificate now</h3>
        <p>Please select a gift certificate amount:</p>
        {$defaultImgArr = ['images/gift-1.jpg', 'images/gift-2.jpg', 'images/gift-3.jpg', 'images/gift-4.jpg']}
        <form id="gift_form" accept-charset="UTF-8" method="post" action="/process/cart" novalidate="novalidate">
          <input type="hidden" name="action" value="quickcheckout" />
          <input type="hidden" name="product_id" value="{$products.product_object_id}" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            {foreach $products.variants as $k => $v} {$price = $v.variant_price} {if $v.variant_specialprice gt 0}{$price = $v.variant_specialprice}{/if} {if $user.id && $v.variant_membersprice gt 0}{$price = $v.variant_membersprice}{/if}
            <div class="col-sm-6 col-md-3">
              <div class="giftoption">
                <label for="variant-{$v.variant_id}">
                  <input type="radio" {if $post.variant_id eq $v.variant_id}checked="checked"{/if} class="all-options{if $v.variant_editableprice eq 1} show-otherval{/if}" data-value="{$price|number_format:0:'.':','}" value="{$v.variant_id}" name="variant_id" id="variant-{$v.variant_id}">
                  <div class="giftopin">
                    <div class="giftopimg">
                      <img src="/{$defaultImgArr.$k}" class="img-responsive" alt="{$v.variant_uid}" title="{$v.variant_uid}" />
                    </div>
                    <div class="giftoptext">
                      <h3>
                        {if $v.variant_editableprice eq 1}Other{else}<span>$</span>{$price|number_format:0:'.':','}{/if}
                      </h3>
                      <p>{$v.variant_description|truncate:150:"..."}</p>
                      <a href="javascript:void(0);" class="btn btn-red">Select</a>
                    </div>
                  </div>
                </label>
              </div>
              <input type="hidden" disabled value="{$v.attr_value_id}" name="attr[{$v.attribute_id}][id]" id="attribute_id-{$v.variant_id}" class="variant-attributes"/>
            </div>
            {/foreach}

          </div>
          <br />
          <br />
          <div class="row" id="fields-wrapper" {if !$post}style="display:none"{/if}>
            <div class="col-sm-12 col-md-8 col-md-offset-2">
              <div class="row">
                <div class="col-sm-12 form-group" id="otheram" {if $post.price neq ''}style="display:block"{/if}>
                  <label class="visible-ie-only" for="price">
                    Please specify a whole dollar amount over $5<span>*</span>:
                  </label>
                  <input class="form-control" value="{$post.price}" type="text" pattern="[0-9]*" name="price" id="price" required="">
                  <div class="error-msg help-block"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label class="visible-ie-only" for="rname">
                    Recipient's name<span>*</span>:
                  </label>
                  <input class="form-control" value="{$post.rname}" type="text" name="rname" id="rname" required="">
                  <div class="error-msg help-block"></div>
                </div>
                <div class="col-sm-6 form-group">
                  <label class="visible-ie-only" for="remail">
                    Recipient's email<span>*</span>:
                  </label>
                  <input class="form-control" value="{$post.remail}" type="email" name="remail" id="remail" required="">
                  <div class="error-msg help-block"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label class="visible-ie-only" for="sname">
                    Your name<span>*</span>:
                  </label>
                  <input class="form-control" value="{$post.name}" type="text" name="name" id="sname" required="">
                  <div class="error-msg help-block"></div>
                </div>

                <div class="col-sm-6 form-group">
                  <label class="visible-ie-only" for="semail">
                    Your email<span>*</span>:
                  </label>
                  <input class="form-control" value="{$post.email}" type="text" name="email" id="semail" required="">
                  <div class="error-msg help-block"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <input type="checkbox" {if $post.anonymous}checked="checked"{/if} class="form-control" value="Send anonymously" id="anonymous" name="anonymous" />
                  <label for="anonymous">Send anonymously</label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 form-group">
                  <label class="visible-ie-only" for="message">Message (optional):</label>
                  <textarea class="form-control" name="message" id="message">{$post.message}</textarea>
                  <div class="error-msg help-block"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4 form-group">
                  <div>
                    <input type="radio" class="form-control" {if !$post.sendtime || $post.sendtime eq 'now'}checked="checked"{/if} value="now" id="sendnow" name="sendtime" required/>
                    <label for="sendnow">Send now</label>
                  </div>
                  <div class="clearl">
                    <input type="radio" class="form-control" {if $post.sendtime eq 'another-day'}checked="checked"{/if} value="another-day" id="selectday" name="sendtime" required/>
                    <label for="selectday">Select a day to send</label>
                  </div>
                  <div class="clearl">
                    <div class="error-msg help-block"></div>
                  </div>
                  <br />

                  <br />
                  <input type="text" class="customdate" value="{$post.sendday}" placeholder="DD/MM/YYYY" id="sendday" name="sendday" />
                </div>
              </div>
              <div class="row customdate" style="display:none">
                <div class="col-sm-12 text-left">
                  <small><b>*All certificates with a set delivery date will be sent at 9.00am (AEST).</b></small>
                </div>
              </div>
              <br />
              <br />
              <div class="row">
                <div class="col-sm-12">
                  Payment accepted: <img src="/images/cards.png" alt="Visa Mastercard" />
                </div>
              </div>
              <div class="row notice">
                <div class="col-sm-12 dark">
                  Australia Medic Alert Foundation is secured by GeoTrust&reg; to protect your information. To learn more, please view our <a href="/privacy-policy" target="_blank">privacy policy</a>.
                  <br>
                  <br>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6 form-group">
                  <label class="visible-ie-only" for="ccno">
                    Card number<span>*</span>:
                  </label>
                  <input type="text" id="ccno" class="form-control" name="cc[number]" pattern="[0-9]*" autocomplete="off" required />
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
                    <input type="text" id="cccsv" name="cc[csv]" class="seccode form-control" autocomplete="off" pattern="[0-9]*" required/>
                    <img class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                  </div>
                  <div class="error-msg help-block"></div>
                </div>
              </div>
                <div class="row text-left">
                  <div class="col-sm-12 form-group chkbx">
	                  <div>
                          <input class="autor" {if $post.accept}checked="checked"{/if} type="checkbox" id="accept" name="accept" required/>
                          <label class="autor chklab" for="accept">
                            I confirm that I have read and agreed to the <a href="/terms-and-conditions#gift-certificates" title="View our terms and conditions" target="_blank">terms &amp; conditions</a>.
                          </label>
	                  </div>
                    <div class="error-msg help-block clearl"></div>
                  </div>
                </div>
              <div style="height: 0; overflow: hidden;">
                <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off" >
              </div>
              <div class="row error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="button" value="Buy now" onclick="$('#gift_form').submit();" class="btn-red btn" id="fbsub">
                </div>
              </div>
            </div>
          </div>
        </form>
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

    $("#sendday").datepicker({
      dateFormat: "dd/mm/yy",
      minDate: "+1D"
    });

    $('#gift_form').validate({
      submitHandler: function(form) {
        $('#fbsub').val('Processing...').attr('disabled','disabled');
      	form.submit();
      }
    });

    $('#price').rules("add", {
      required: true,
      digits: true,
      min: 5,
      max: 1000,
      messages: {
        min: "Please enter a value greater than or equal to $5.",
        max: "Please enter a value less than or equal to $1000."
      }
    });

    $('input[name="sendtime"]').change(function() {
      if($(this).val() == 'now'){
        $(".customdate").hide();
      }else{
        $(".customdate").fadeIn();
      }
    });

    $('#ccno').rules("add", {
      creditcard : true,
    });

    $('#cccsv').rules("add", {
      digits: true,
      minlength: 3
    });


    $("input[name=variant_id]").change(function(){
      //Set attribute
      $('.variant-attributes').attr('disabled', 'disabled');
      $('#attribute_id-' + $(this).val()).removeAttr('disabled');
      $('#giftgrey .giftoptext a.btn').html('Select');
      $(this).parents('.giftoption').find('.giftoptext a.btn').html('Selected');

      if($("input[name=variant_id]:checked").hasClass('show-otherval')){
        $("#otheram").show();
        $("#price").val('');
      }else{
        $("#price").val( $("input[name=variant_id]:checked").attr('data-value') );
        $("#otheram").hide();
      }
      $('#fields-wrapper').fadeIn();
    });

  });
</script>
{/block}
