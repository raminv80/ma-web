{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="pagehead">
  <div class="bannerout">
    <!-- 
    <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
     -->
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1>{$listing_name}</h1>
        {if $user.maf.main.renew eq 't' || $user.maf.main.renew_option eq 't'}{$listing_content1}{else}{$listing_content2}{/if}
      </div>
    </div>
  </div>
</div>

{if $user.maf.main.renew eq 't' || $user.maf.main.renew_option eq 't'}
<div id="giftgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Payment information</h2>
        <br>
		<div class="container">
          {foreach from=$productsOnCart item=item}
            <div class="row">
              <div class="col-sm-4 col-sm-offset-2 col-xs-6 text-right">
                {$item.cartitem_product_name} - {$item.variant_name}:
              </div>
              <div class="col-sm-4 text-left col-xs-6">
                ${$item.cartitem_product_price|number_format:2:'.':','}
              </div>
            </div>
          {/foreach}
          {if $totals.discount gt 0}
          <div class="row">
              <div class="col-sm-4 col-sm-offset-2 col-xs-6 text-right">
                Discount:
              </div>
              <div class="col-sm-4 col-xs-6 text-left" >
                ${$totals.discount|number_format:2:".":","}
              </div>
          </div>
          {/if}
          <br>
          <div class="row">
            <div class="col-sm-12">
              <div class="col-sm-4 col-sm-offset-2 col-xs-6 text-right">
                <h3><strong>Total:</strong></h3>
              </div>
              <div class="col-sm-4 col-xs-6 text-left" >
                <h3><strong>${$totals.total|number_format:2:'.':','}</strong></h3>
              </div>
            </div>
          </div>
        </div>
        <form id="quickrenew_form" class="payment-autorenew" accept-charset="UTF-8" method="post" action="/process/cart" novalidate="novalidate">
          <input type="hidden" name="action" value="quick-renew" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
              <div class="fields-wrapper">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <br>Payment accepted: <img src="/images/cards.png" alt="Payment accepted" title="Payment accepted" id="accepted" />
                  </div>
                </div>
  
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="ccno">
                      Card number<span>*</span>:
                    </label>
                    <input type="text" id="ccno" class="form-control copycc" name="cc[number]" autocomplete="off" required />
                    <div class="error-msg help-block"></div>
                  </div>
  
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="ccname">
                      Cardholder's name<span>*</span>:
                    </label>
                    <input type="text" id="ccname" class="form-control copycc" name="cc[name]" autocomplete="off" required />
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
                        <select id="ccmonth" name="cc[month]" class="form-control copycc" required>
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
                        <select id="ccyear" name="cc[year]" class="cc-select-req form-control copycc">
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
                      <input type="text" id="cccsv" name="cc[csv]" class="seccode form-control copycc" autocomplete="off" pattern="[0-9]*" maxlength="4" required/>
                      <img class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                    </div>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                
                <div class="col-sm-12 text-center autorenew" {if $user.maf.main.autoBilling eq 't'}style="display:none"{/if}>

            <div class="row">
              <div class="col-sm-12 text-center">
                <h3>Auto renewal of membership (optional)</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 text-center">
                <input class="autor" type="checkbox" value="autorenew" name="autorenewal" id="autorenewal" onclick="autorenew();">
                <label class="autor chkbox" for="autorenewal">Stay protected each year: sign up for auto-renewal.</label>
              </div>
            </div>
            <div class="row notice">
              <div class="col-sm-12 text-center">
                <p>Auto-renewal helps protect you by paying your annual membership fee automatically each year by direct debit from your nominated payment method.
                            Please confirm your auto-renewal payment method below. The first payment will occur at your next renewal of annual membership.</p>

              
              </div>
            </div>
            <div id="autorenew-subform">
            <div class="row">
              <div class="col-sm-6 text-center">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <input class="form-control autor" type="radio" value="cc" name="autopayment" id="autocredit">
                    <label class="autor chkbox" for="autocredit">Credit card</label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 text-center">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <input class="form-control autor" type="radio" value="dd" name="autopayment" id="autodd">
                    <label class="autor chkbox" for="autodd">Direct debit</label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>
            </div>

            <div id="auto-cc-wrapper" class="auto-opts" style="display:none;">
                <div class="row">
                <div class="col-sm-12">
                <p>Please update with an alternative credit card if required.</p><br>
                </div>
              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccno">Card number<span>*</span>:</label>
                <input type="text" id="auto-ccno" class="auto-cc-req form-control" pattern="[0-9]*" name="auto-cc[number]" autocomplete="off" />
                <div class="error-msg help-block"></div>
              </div>

              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccname">Cardholder's name<span>*</span>:</label>
                <input type="text" id="auto-ccname" class="auto-cc-req form-control" name="auto-cc[name]" autocomplete="off" />
                <div class="error-msg help-block"></div>
              </div>
                   </div>

            <div class="row">
              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccmonth">Expiry<span>*</span>:</label>
                <div class="row">
                <div class="col-sm-6">
                <select id="auto-ccmonth" name="auto-cc[month]" class="auto-cc-req form-control">
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
                        <select id="auto-ccyear" name="auto-cc[year]" class="auto-cc-req form-control" >
                         {assign var=thisyear value=$smarty.now|date_format:"%Y"}
                         {assign var=numyears value=$thisyear+20}
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
                <label class="visible-ie-only" for="auto-cccsv">Security code<span>*</span> <img src="/images/question-mark.png" alt="The three-digit number on the signature panel on the back of the card." title="The three-digit number on the signature panel on the back of the card." data-toggle="tooltip" data-placement="top" /> :</label>
                <div>
                  <input type="text" id="auto-cccsv" name="auto-cc[csv]" class="seccode auto-cc-req form-control" autocomplete="off" pattern="[0-9]*" maxlength="4" />
                  <img  class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                </div>
                <div class="error-msg help-block"></div>
              </div>
                   </div>
              </div>

              <div class="row auto-opts" id="auto-dd-wrapper" style="display: none;">
                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autobsb">BSB<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[bsb]" autocomplete="off" maxlength="6" pattern="[0-9]*" id="autobsb" >
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autoddname">Account holder's name<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[name]" autocomplete="off" maxlength="80" id="autoddname" >
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autoddno">Account number<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[number]" autocomplete="off" maxlength="9" pattern="[0-9]" id="autoddno" >
                    <div class="error-msg help-block"></div>
                  </div>

              </div>

              <div id="auto-renewal-conf-wrapper" class="col-sm-12 text-center" style="display:none;">
                <div class="row">
                  <div class="col-sm-12 form-group chkbx">
                          <input class="autor auto-dd-req auto-cc-req" type="checkbox" id="accept" name="accept" />
                          <label class="autor chklab" for="accept">
                            I confirm that I have read and agree to the <a href="/terms-and-conditions#auto-renewal" target="_blank" title="Click to view our terms and conditions">auto-renewal terms &amp; conditions</a> and I wish to register for auto-renewal of my membership.
                          </label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>

            </div>
            </div>

                </div>
                <div style="height: 0; overflow: hidden;">
                  <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off" >
                </div>
                <div class="row error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                <br>
                  <input type="submit" value="Pay now" class="btn-red btn" id="fbsub">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{/if}



{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();
   
    $('#quickrenew_form').validate();

    
    $('#ccno').rules("add", {
      creditcard : true,
    });

    $('#cccsv').rules("add", {
      digits: true,
      minlength: 3
    });
    
    $('#auto-ccno').rules("add", {
      creditcard : true,
    });
    
    $('#autoddno').rules("add", {
      digits: true
    });
    
    $('#autobsb').rules("add", {
      digits: true,
      minlength: 6
    });
    
    $('#auto-cccsv').rules("add", {
      digits: true,
      minlength: 3
    });
    
    
    autorenew();

    $('input[name="autopayment"]').change(function(){
      automethod();
    });

  });
  
  function automethod(){
    var option = $('input[name="autopayment"]:checked').val();
    $('.auto-opts').hide();
    $('.auto-cc-req').removeAttr('required');
    $('.auto-dd-req').removeAttr('required');
    if(option){
      $('.auto-'+option+'-req').attr('required', 'required');
      $('#auto-'+option+'-wrapper').fadeIn();
      $('#auto-renewal-conf-wrapper').fadeIn();
      if(option == 'cc'){
        $('.copycc').each(function(){
          elementID = $(this).attr('id');
          elementName = $(this).attr('name');
          elementVal = $(this).val();
          elementNode = $(this).prop('nodeName');
          if(elementNode == 'SELECT'){
            $('select#auto-' + elementID).val(elementVal); 
            //$('select#auto-' + elementID).data("selectBox-selectBoxIt").selectOption(elementVal);            
          }else{
            $('input[name="auto-' + elementName + '"]').val(elementVal);  
          }
        });
      }
    }else{
      $('#auto-renewal-conf-wrapper').hide();
    }
  }

  function autorenew(){
    if($("#autorenewal").is(':checked')){
      $('#autorenew-subform').show();
      $('input[name="autopayment"]').attr('required', 'required');
      automethod();
    }else{
      $('#autorenew-subform').hide();
      $('input[name="autopayment"]').removeAttr('required');
      $('.auto-cc-req').removeAttr('required');
      $('.auto-dd-req').removeAttr('required');
    }
  }
</script>
{/block}
