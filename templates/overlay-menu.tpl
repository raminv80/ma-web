{block name=desktopmenu}
{* Define the function *}
{function name=render_list level=0 parenturl="" menu=0}
	{foreach $items as $item}
		{if $level lt 1 && $item.url eq ''}
			<li class="maintitle {if $listing_url eq ''}active{/if}"><a title="{$item.title}" href="/">{$item.title}</a>
			{call name=render_list items=$item.subs level=$level+1 menu=$menu}
		{else}
			<li class="{if count($item.subs) > 0}dropdown{/if} {if $item.selected eq 1}active{/if}"><a title="{$item.title}" {if $item.url|strstr:"http://"}target="_blank"{/if} href="{if $item.url|strstr:"http://"}{$item.url}{else}{$parenturl}/{$item.url}{/if}" >{$item.title}</a>
			{if count($item.subs) > 0}
				<ul class="dropdown-menu">                                
        	{call name=render_list items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=$menu}
				</ul>
			{/if}
			</li>
		{/if}
	{/foreach}	
{/function}

<div id="top">
	<div class="container">
		<div class="row">
			<div class="col-sm-5">
				<a href="/"><img src="/images/logo.png" alt="" class="img-responsive" id="logotop" /></a>
			</div>
			<div class="col-md-6 col-md-offset-1 col-lg-5 col-lg-offset-2">
				<ul id="topmenu">
					<li id="menu">
						<a href="#">
						<div class="bubble">
							<img src="/images/menu.png" alt="" />
						</div>
						MENU
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>



<div id="overlay">
	<div class="container">
		<div class="row">
			<div class="hidden-xs col-sm-3 pull-left text-left" id="hometop">
				<a href="/" id="home1"><span class="glyphicon glyphicon-home"></span>
					<div class="small">HOME</div>
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 pull-right text-right" id="closetop">
				<a href="#" id="close"><span class="glyphicon glyphicon-remove"></span>
					<div class="small">CLOSE MENU</div>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
			<ul id="overlay-categories">
          {foreach $menuitems as $item}
						{if $item.type_id eq 4}<li class="category-buttons"><a href="{$item.url}" target="_blank"><img src="{$item.menu_img}" alt="{$item.title}" /><span>{$item.title}</span></a></li>{/if}
					{/foreach}
			</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
			<ul id="overlay-nav">
				{call name=render_list items=menuitems}
			</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
			<ul id="overlay-social">
				FOLLOW US
				<li id="social-buttons">
					<a href="#" target="_blank"><img src="/images/footfb.png" alt="fb" /></a>
					<a href="#" target="_blank"><img src="/images/footig.png" alt="instagram" /></a>
					<a href="#" target="_blank"><img src="/images/footblogger.png" alt="blogger" /></a>
				</li>
			</ul>
			
			<ul class="visible-xs visible-sm" id="privacyxs">
				<li><a href="/privacy-policy">Privacy Policy</a></li>
				<li><a href="/terms-and-conditions">Terms &amp; Conditions</a></li>
				<li><a href="/refund-policy">Refund Policy</a></li>
				<li><a href="/delivery-policy">Delivery Policy</a></li>
			</ul>
			</div>
		</div>
	</div>
</div>
<div class="cleartop"></div>


<script type="text/javascript">
$("#menu").click(function(){
		if($("#overlay").hasClass("open")){
			$("#overlay").removeClass("open");
			$("body").removeClass("noscroll");
		}
		else{
			$("#overlay").addClass("open");
			$("body").addClass("noscroll");
		}
	});
	
	$("#close").click(function(){
		if($("#overlay").hasClass("open")){
			$("#overlay").removeClass("open");
			$("body").removeClass("noscroll");
		}
	});
</script>

{/block}

