{block name=body}
	<link href="/includes/css/socialwall.css" rel="stylesheet" media="screen">
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout" class="headerbg">
				{include file='desktopmenu.tpl'}
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_content1}
					  			</div>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div id="orangebox">
	  	<div class="containerA">
		  	<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->
		    <div id="fixed-top">
				<div class="navbar">
		            <div class="navbar-inner">
		                <div class="container">
		                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                    </a>
		                    <div class="nav-collapse collapse">
		                        <ul class="nav" id="filters">
		                            <li ><a id="all" href="#" data-filter=".item" data-type="0" class="list-image-all btn_element" >All</a></li>
									<li><a href="#" data-filter=".twitter" data-type="2" class="list-image-tw btn_element" ></a></li>
		                            <li><a href="#" data-filter=".instragram" data-type="1" class="list-image-insta btn_element"></a></li>
		                            <li><a href="#" data-filter=".youtube" data-type="3"class="list-image-youtube btn_element"></a></li>
		                            <li><a href="#" data-filter=".facebook" data-type="4" class="list-image-facebook btn_element"></a></li>
								</ul>
		                    </div><!--/.nav-collapse -->
		                </div>
		            </div>
		        </div>
		    </div>
			<div class="containerA">
		        	<div class="row" id="container1"><div class="clear"></div></div>
		        	<div class="loading" id="load-element"><img src="/images/103.gif" /><div class="clear"></div></div>
					<div class="clear"></div>
			</div> <!-- /container -->
	  	</div>
	  </div>
	
	<script src="/includes/js/jquery.isotope.min.js"></script>
	<script src="/includes/js/socialwall/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<script type="text/javascript" src="/includes/js/socialwall/socialwall.js?time={$smarty.now}"></script>
	<script type="text/javascript" src="/includes/js/socialwall/share.js"></script>

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
