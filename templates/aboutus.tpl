{block name=body}
<div class="row-fluid">
		<div class="span16">
			<div class="content-left">
			    {image field=$top_image}
			</div>
		</div>
	</div>
<div class="row-fluid">
        <div class="span16">
        <div class="row-fluid">
			 <div class="span16">	
		          <div class="content-left">
			     <p class="breadcrumbs"><a href="/">Home</a> / {$page_name}</p>
		            <h1>{$page_name}</h1>
					{$content}		
		           </div><!-- /.content-left -->
			  </div>
			</div>
        </div><!-- /.span16-->
      </div>
      <div class="row-fluid">
	<div class="row-fluid">
		 <div class="span16">
			<ul class="nav-tabs" id="myTab">
			  <li class="active"><a href="#home" data-toggle="tab">History</a></li>
			  <li><a href="#profile" data-toggle="tab">Company statement</a></li>
			  <li><a href="#messages" data-toggle="tab">Environmental policy</a></li>
			  <li><a href="#settings" data-toggle="tab">Contact details</a></li>
			</ul>
		</div>
	</div>

<div class="row-fluid">
	 <div class="span16">	
          <div class="content-left">
			<div class="tab-content">
			  <div id="history" class="tab-pane active">
					{$history}	
				</div>
				<div id="profile" class="tab-pane">
					{$company_statement}
				</div>
				<div id="messages" class="tab-pane">
					{$environmental_policy}	
				</div>
				<div id="settings" class="tab-pane">
					{$contact_details}	
				</div>
			</div>
	   </div>
	</div>
	<div class="span16">
			<span class='st_sharethis_large' displayText='ShareThis'></span>
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
			<span class='st_email_large' displayText='Email'></span>
	</div>
	<div class="span16">
	</div>
	</div>
</div>
{/block}