{block name=desktopmenu}
<div id="topfix">
    <div class="navbar navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
      <div id="logo1" class="col-xs-4  pull-left">
        <a href="/diy"><img class="img-responsive" src="/images/logo.png" alt="Steeline - Service over and above" title="Service over and above" /></a>
      </div>
      <div id="callst" class="col-xs-6">
        <a href="tel://1300 78335463" title="click here to call Steeline">1300 STEELINE</a>
      </div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse">
          <div id="change" class="pull-left">
          You are viewing Steeling for DIY - <a href="/trade" title="Click here to change the site to TRADE">Change to TRADE</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="topitems"><a href="/diy/about" title="Click here to read more about Steeline">About Steeline</a></li>
            <li class="topitems"><a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to contact Steeline">Contact us</a></li>
            <li class="topitems"><a href="/diy/find-a-store" title="Click here to find a store near you">Find a store</a></li>
            <li class="topitems"><a href="#viewestimate" data-toggle="modal" data-target="#viewestimate" title="Click here to view your current estimate">My estimate</a></li>
            <li class="topitems"><a href="javascript:void(0);" onclick="memberArea();" title="Click here to view your account details" >My account</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div id="nav2out">
      <div class="container">
      <div class="row">
      <div id="logo" class="col-xs-5 col-sm-2 pull-left">
        <a href="/diy"><img src="/images/logo.png" alt="Steeline" alt="Steeline - Service over and above" title="Steeline - Service over and above " /></a>
      </div>
      <div id="contmenu" class="col-sm-4 col-sm-offset-2">
      <div>
        <img src="/images/menubars.jpg" alt="Click here to view the menu" title="Click here to view the menu" /> Menu
      </div>
      </div>
      <div id="phone" class="col-xs-12 col-sm-4 pull-right">
        Have a question or need help?
        <div id="phno">
        <a href="tel://1300 78335463" title="Click here to call Steeline">
          <!--<div class="smallnum">78335463</div>-->
          <div class="bignum">1300 STEELINE</div>
        </a>
        </div>
      </div>
      <div id="menusearch" class="col-xs-12 col-sm-10 pull-right">
        <div class="col-sm-9">
          <ul id="mainmenu">
            <li{if $REQUEST_URI eq "/diy"} class="current"{/if}><a href="/diy" title="Click here to go to the Steeline homepage">Home</a></li>
            <li{if {preg_match pattern="\/diy\/products" subject=$REQUEST_URI}} class="current"{/if}><a href="/diy/products" title="Click here to view Steeline products">Products</a></li>
            <li{if {preg_match pattern="\/diy\/services" subject=$REQUEST_URI}} class="current"{/if}><a href="/diy/services" title="Click here to view Steeline services">Services</a></li>
            <li{if {preg_match pattern="\/diy\/colorbond" subject=$REQUEST_URI}} class="current"{/if}><a href="/diy/colorbond" title="Click here to see the COLORBOND range">COLORBOND&reg;</a></li>
           <!--  <li><a href="/diy/specials">Specials</a></li>
            <li><a href="/diy/recent-projects">Recent projects</a></li>
            <li><a href="/diy/do-it-yourself">DIY</a></li>
            <li><a href="/diy/blog">Blog</a></li> -->
          </ul>
        </div>
        <div class="col-xs-12 col-sm-2">
	        <form accept-charset="UTF-8" action="/diy/search" method="get" id="search-form" > 
				    <input type="text" id="search" name="q" value="" placeholder="Search.." />
				  </form>
        </div>
      </div>
      </div>
      </div>
    </div>
  </div>
  <div id="bannerout" class="homeb">
     <div class="container">
       <div id="bannettextout">
       <div class="banner-text"></div>
       </div>
     </div>
   </div>
   <div id="menu2out">
     <div class="container">
       <div class="row">
         <div class="col-xs-6 col-sm-3 menu2item">
           <a href="/diy/find-a-store" title="Click here to find a Steeline store">
             <img src="/images/findstore.png"  title="Click here to find a Steeline store" />
             Find a store
           </a>
         </div>
        <!--  <div class="col-xs-6 col-sm-3 menu2item">
           <a href="/diy/recent-projects" >
             <img src="/images/recent.png"/>
             Recent projects
           </a>
         </div> -->
         <div class="col-xs-6 col-sm-3 menu2item">
           <a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get help from a Steeline store">
             <img src="/images/reqquotebig.png" title="Click here to get help from a Steeline store" alt="Click here to get help from a Steeline store" />
             Get a quote
           </a>
         </div>
         <div class="col-xs-6 col-sm-3 menu2item">
           <a href="/diy/products" title="Click here to view Steeline DIY products">
           <img src="/images/products.png" title="Click here to view Steeline DIY products" />
             Our products
           </a>
         </div>
         <div class="col-xs-6 col-sm-3 menu2item2">
           <a href="#needhelp" data-toggle="modal" data-target="#needhelp" title="Click here to get help from a Steeline store">
             <img src="/images/needhelp.png" alt="Click here to get help from a Steeline store" title="Click here to get help from a Steeline store" />
             Need help?
           </a>
         </div>
       </div>
     </div>
   </div>
{/block}


