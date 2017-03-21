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
        {if $productsOnCart}{$listing_content1}{else}{$listing_content2}{/if}
      </div>
    </div>
  </div>
</div>

{if $productsOnCart}
<div id="giftgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Payment information</h2>
        <br>
        {foreach from=$productsOnCart item=item}
          <h4>{$item.cartitem_product_name} - {$item.variant_name}: &nbsp;&nbsp; ${$item.cartitem_product_price|number_format:2:'.':','}</h4>
        {/foreach}
        <h3><b>Total ${$totals.total|number_format:2:'.':','}</b></h3>
        
        <form id="quickrenew_form" class="payment-autorenew" accept-charset="UTF-8" method="post" action="/process/cart" novalidate="novalidate">
          <input type="hidden" name="action" value="quick-renew" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
              <div class="fields-wrapper">
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
                      <input type="text" id="cccsv" name="cc[csv]" class="seccode form-control copycc" autocomplete="off" pattern="[0-9]" maxlength="4" required/>
                      <img class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                    </div>
                    <div class="error-msg help-block"></div>
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
    
    
    $("select").selectBoxIt();
    

  });
  
</script>
{/block}
