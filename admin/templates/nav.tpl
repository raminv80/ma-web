{* This template contains the structure for dynamically building a menu. Any hard coded menu items should also go here Variables: Array of menu items = $menuitems Company Name = {$company_name} *} {block name=nav}
<nav class="navbar navbar-default">
    <div class="navbar-header">
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="/admin"><img src="/admin/images/themlogo.jpg" alt="them advertising logo" class="img-responsive" id="logo" /></a>
  </div>

  <div class="collapse navbar-collapse js-navbar-collapse">
    <ul class="nav navbar-nav wide">
      <li class="dropdown mega-dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Menu</a>
        
        <div class="dropdown-menu mega-dropdown-menu container"><div id="pin" title="Pin menu"><span class="glyphicon glyphicon-pushpin"></span></div>
        <ul>
          <!-- <li class="col-sm-3">
            <ul>
              <li class="dropdown-header">News from Them </li>                            
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                              <div class="carousel-inner">
                                <div class="item active">
                                    <a href="#"><img src="http://placehold.it/254x150/3498db/f5f5f5/&text=New+Collection" class="img-responsive" alt="product 1"></a>
                                    <h4><small>Summer dress floral prints</small></h4>                                        
                                    <button class="btn btn-primary" type="button">49,99 €</button> <button href="#" class="btn btn-default" type="button"><span class="glyphicon glyphicon-heart"></span> Add to Wishlist</button>       
                                </div>End Item
                                <div class="item">
                                    <a href="#"><img src="http://placehold.it/254x150/ef5e55/f5f5f5/&text=New+Collection" class="img-responsive" alt="product 2"></a>
                                    <h4><small>Gold sandals with shiny touch</small></h4>                                        
                                    <button class="btn btn-primary" type="button">9,99 €</button> <button href="#" class="btn btn-default" type="button"><span class="glyphicon glyphicon-heart"></span> Add to Wishlist</button>        
                                </div>End Item
                                <div class="item">
                                    <a href="#"><img src="http://placehold.it/254x150/2ecc71/f5f5f5/&text=New+Collection" class="img-responsive" alt="product 3"></a>
                                    <h4><small>Denin jacket stamped</small></h4>                                        
                                    <button class="btn btn-primary" type="button">49,99 €</button> <button href="#" class="btn btn-default" type="button"><span class="glyphicon glyphicon-heart"></span> Add to Wishlist</button>      
                                </div>End Item                                
                              </div>End Carousel Inner
                            </div>/.carousel
            </ul>
          </li> -->
          {foreach item=cat key=name from=$menu name=foo}
              <li class="col-sm-2"><h3>{$name}</h3>
            <ul>
              {foreach item=item from=$cat.section name=section}
              {if $item.customUrls}
              {foreach key=k item=li from=$item.customUrls}
                <li><a href='{$k}' class='list-header'>{$li}</a></li> 
              {/foreach}
              {else}
                <li><a href='{$item.url}' class='list-header'>{$item.title}</a></li>
              
              {foreach item=li from=$item.list}
                <li><a href='{$li.url}' class='list-header'>{$li.title}</a></li> 
              {/foreach}
            {/if}
          {/foreach}
            </ul>
            </li>
          {/foreach}
        </ul><a href="/admin/logout" class="btn btn-default" style="position: absolute;bottom: 5px;right: 5px;cursor: pointer;">Logout</a></div>
        
      </li>
    </ul>
    
  </div><!-- /.nav-collapse -->
</nav>

<script>
jQuery(document).ready(function($){
  function createCookie(name,value,days) {
      if (days) {
          var date = new Date();
          date.setTime(date.getTime()+(days*24*60*60*1000));
          var expires = "; expires="+date.toGMTString();
      }
      else var expires = "";
      document.cookie = name+"="+value+expires+"; path=/";
  }

  function readCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }

  function eraseCookie(name) {
      createCookie(name,"",-1);
  }
  var x = readCookie('pinnedmenu');
  if (x) {
     $("#pin").addClass("pinned");
     $(".dropdown-menu").addClass("pinned");
     $(".dropdown-toggle").hide();
     $("#pin").attr("title","Unpin menu");
  }
  
  $("#pin").click(function(){
    if($(this).hasClass("pinned")){
      eraseCookie('pinnedmenu');
      $(this).removeClass("pinned");  
      $(".dropdown-menu").removeClass("pinned");
      $(".dropdown-toggle").show();
      $(this).attr("title","Pin menu");
    }
    else{
      createCookie('pinnedmenu','1',7);
      $(this).addClass("pinned");
      $(".dropdown-menu").addClass("pinned");
      $(".dropdown-toggle").hide();
      $(this).attr("title","Unpin menu");
    }
  });
});
</script>
{/block}