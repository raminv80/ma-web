{block name=footer}
{function name=render_footer_list level=0 menu=0 ismobile=0}
  {assign var='count' value=0}
    {foreach $items as $item}
      {if $level lt 1}
        <li class="{if count($item.subs.list) > 0}dropdown{/if} {if $item.selected eq 1}active{/if}">
          <a title="{$item.title}" {if $item.type eq 3}target="_blank"{/if} href="{$item.url}" >{$item.title} {if count($item.subs.list) > 0 && $ismobile neq 1}<span class="arrow-down"></span>{/if}</a>
          {if count($item.subs.list) > 0}
            <ul class="{if $ismobile neq 1}dropdown-menu{else}subcat-menu{/if}">
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
  <div id="foot1">
    <div class="container">
      <div class="row" id="main-footer">
        <div class="col-sm-12">
          {call name=render_footer_list items=$menuitems['footer']['list'] ismobile=0}
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="socout">
            <a target="_blank" href="http://www.facebook.com/sharer.php?u={$DOMAIN}{$REQUEST_URI}"><img src="/images/fb.png" alt="Facebook icon" title="Share this on Facebook" /></a>
          </div>
          <div class="socout">
            <a target="_blank" href="http://twitter.com/home?status={if $listing_share_text}{$listing_share_text}{else}We spend over 15 hours a day sitting down. Imagine what that does to your posture! Find ergonomic solutions here:{/if} {$DOMAIN}{$REQUEST_URI}"><img src="/images/twitter.png" alt="Twitter icon" title="Share this on Twitter" /></a>
          </div>
          <div class="socout">
            <a target="_blank" href="http://tumblr.com/widgets/share/tool?canonicalUrl={$DOMAIN}{$REQUEST_URI}"><img src="/images/tumblr.png" alt="Tumblr icon" title="Share this on Tumblr" /></a>
          </div>
          <div class="socout">
            <a href="mailto:?subject=Ergonomic solutions&body={if $listing_share_text}{$listing_share_text} {$DOMAIN}{$REQUEST_URI}{else}Hi%0A%0DI saw this site and thought of you: {$DOMAIN}{$REQUEST_URI}%0A%0DThe Ergo Centre has a huge range of ergonomic solutions that can help you at work and at home. From office seating and sit to stand desks, to accessories and beds, I think you'll find what you need.%0A%0DYou can shop online, or they have a store at Keswick.%0A%0DKind regards%0A%0D{/if} "><img src="/images/mail.png" alt="Mail icon" title="Share by email" /></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="foot2">
    <div id="mobbot">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">Copyright {"Y"|date} {$COMPANY.name}</div>
          <div class="col-sm-6 text-right">
            <div class="text-center-xs">
              Made by <a href="http://www.them.com.au" target="_blank" title="Them Advertising">Them Advertising</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <a href="#" id="totop">Back to top</a>

</footer>
{/block}
