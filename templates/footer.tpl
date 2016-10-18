{block name=footer}
{function name=render_footer_list level=0 menu=0 ismobile=0}
  {assign var='count' value=0}
    {foreach $items as $item}
      {if $level lt 1}
        <li class="{if count($item.subs.list) > 0}baselevel{/if} {if $item.selected eq 1}{/if}">
         {if $item.type neq 2}
         <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{$item.url}" >{$item.title} {if count($item.subs.list) > 0 && $ismobile neq 1}<span class="arrow-down"></span>{/if}</a>
         {else}
         <div class="foot-cat">{$item.title}</div>
         {/if}

          {if count($item.subs.list) > 0 && $item.type neq 1}
            <ul class="{if $ismobile neq 1}{else}subcat-menu{/if}">
              {call name=render_list items=$item.subs.list level=$level+1 menu=$menu ismobile=$ismobile}
            </ul>
          {/if}
       </li>
       {else}
       <li class="sub-li {if $item.selected eq 1}active{/if}">
     <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{$item.url}">{$item.title}</a>
     {/if}
  {/foreach}
{/function}
<footer>
<div id="newsletter">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>Stay up to date with MedicAlert Foundation</h2>
				<p>If you, or someone you care for, are one of the many Australians living with a medical condition, sign up to our email newsletter and be the first to hear about the latest news and special offers from MedicAlert<sup>&reg;</sup></p>
				<br />

					<form action="https://themdigital.createsend.com/{if $staging}t/r/s/kiidlhi/{else}t/r/s/djiijii/{/if}" method="post" id="newsl_form" novalidate="novalidate">
						<div class="row form-group">
							<div class="col-sm-6 ">
							  <label for="fieldName">Name<span>*</span></label>
							  <input class="form-control" type="text" name="cm-name" id="fieldName" required="">
							  <div class="error-msg help-block"></div>
							</div>
							<div class="col-sm-6 ">
							  <label for="fieldEmail">Email<span>*</span></label>
							  <input class="form-control" type="email" name="{if $staging}cm-kiidlhi-kiidlhi{else}cm-djiijii-djiijii{/if}" id="fieldEmail" required="">
							  <div class="error-msg help-block"></div>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-12 text-center">
								<input type="submit" value="Subscribe" class="btn-red btn">
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>

	<div id="needhelp" class="hidden-xs hidden-sm">
		<div id="helptab">Need help? <div id="nhicon"><img src="/images/needhelp.png" alt="help" /></div></div>
		<div id="helpcont">
			<div id="helpcontin">
			<div class="needrow">
				<div class="needl"><img src="/images/need-call.png" alt="Call" /></div>
				<div class="needr"><h5><a href="tel:{$COMPANY.toll_free}" title="Give us a call">Call Membership Services<br />{$COMPANY.toll_free}</a></h5></div>
			</div>
			<div class="needrow">
				<div class="needl"><img src="/images/need-contact.png" alt="contact" /></div>
				<div class="needr"><h5><a href="/contact-us" title="Click to contact us">Contact us</a></h5></div>
			</div>
			<div class="needrow">
				<div class="needl"><img src="/images/need-faqs.png" alt="faqs" /></div>
				<div class="needr"><h5><a href="/faqs" title="View our FAQs">FAQs</a></h5></div>
			</div>
			</div>
		</div>
	</div>
</div>
  <div id="foot1">
    <div class="container">
      <div class="row" id="main-footer">
        <div class="col-sm-12">
	      <ul id="footmenu">
          {call name=render_footer_list items=$menuitems['footer']['list'] ismobile=0}
	      </ul>
        </div>
      </div>
      <div class="row" id="socfooter">
        <div class="col-sm-6" id="socfooter1">
	      <div>Join the community:</div>
          <div class="socout">
            <a target="_blank" href="https://www.facebook.com/AustraliaMedicAlertFoundation"><img src="/images/footer-fb.png" alt="Facebook icon" title="Visit our Facebook page" /></a>
          </div>
          <div class="socout">
            <a target="_blank" href="https://twitter.com/MedicAlert_Aust"><img src="/images/footer-twitter.png" alt="Twitter icon" title="Find us on Twitter" /></a>
          </div>
          <div class="socout">
            <a target="_blank" href="https://www.youtube.com/user/MedicAlertFoundation"><img src="/images/footer-yt.png" alt="Youtube icon" title="Find us on YouTube" /></a>
          </div>
          <div class="socout">
            <a target="_blank" href="https://www.instagram.com/medicalert_aust/"><img src="/images/footer-insta.png" alt="Instagram icon" title="Find us on Instagram" /></a>
          </div>
        </div>
        <div class="col-sm-3 col-md-2 col-sm-offset-3 col-md-offset-4" id="socfooter2">
	        <div>Site secured by:</div>
	        <img src="/images/geotrust.jpg" alt="Site secured by Geotrust" />
        </div>

        <div class="col-xs-12 visible-xs text-center">
			<a href="/privacy-policy">Privacy policy</a> | <a href="/terms">Terms &amp; conditions</a><br />
			&copy; Copyright {$COMPANY.name} {"Y"|date}. <br />
			Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them</a>
        </div>
      </div>
    </div>
  </div>

  <div id="foot2">
    <div id="mobbot">
      <div class="container">
        <div class="row">
          <div class="col-sm-9">&copy; Copyright {$COMPANY.name} {"Y"|date}. | <a href="/privacy-policy" title="View our privacy policy">Privacy policy</a> | <a href="/terms-and-conditions" title="View our terms and conditions">Terms &amp; conditions</a>
          </div>
          <div class="col-sm-3 text-right">
            <div class="text-center-xs">
              Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <a href="#" id="totop">Back to top</a>

</footer>
{/block}
