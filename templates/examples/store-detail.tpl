{block name=storedetail}

         <h1 class="title">{$listing_name}</h1>
         <div id="map" data-name="{$listing_name}" data-lat="{$location_latitude}" data-lon="{$location_longitude}">
         	<div id="map-canvas">Loading Map...</div>
      </div>
			
			<div id="details" class="row">
				<div class="col-sm-5">
					<div class="row">
						<div class="col-sm-4 title">
						ADDRESS:
						</div>
						<div class="col-sm-8">
						{$location_street}<br />
						{$location_suburb} {$location_state} {$location_postcode}
						</div>
						<div class="col-sm-4 title">
						PHONE:
						</div>
						<div class="col-sm-8">
						<a href="tel:{$location_phone}" title="Click here to call">{$location_phone}</a>
						</div>
						<div class="col-sm-4 title">
						EMAIL:
						</div>
						<div class="col-sm-8">
						{obfuscate email=$location_email}
						</div>
					</div>
				</div>
				<div class="col-sm-7">
					{if $listing_content1}
					<div class="row">
						<div class="col-sm-4 title">
						TRADING HOURS:
						</div>
						<div class="col-sm-8">
						<div class="row">
							<div id="trading-hours">
								{$listing_content1}
							</div>
						</div>
						</div>
					</div>
					{/if}
				</div>
			</div>
			<div id="contact-person" class="row">
				{if $listing_content1}
				<div class="row">
					<div class="col-sm-10">
						<div class="col-sm-3 title">
							CONTACT PERSON:
						</div>
						<div class="col-sm-9">
							{$listing_content3}
						</div>
					</div>
				</div>
				{/if}
			</div>
			<div class="row">
				<div class="col-sm-12">
				{$listing_content2}
				
				{if $services} 
					<h3>Installation services</h3>
					{foreach $services as $service}
						<div class="media">
							<div class="store-mini-image">
									{if $service.service_image}<img class="media-object" src="{$service.service_image}" alt="{$service.service_name}-img" title="{$service.service_name}">{/if}
							</div>
							<div class="media-body">
								<h4 class="media-heading">{$service.service_name}</h4>
								<div class="media-text">
								{$service.service_description}
								</div>
								<div class="media-read">read more ></div>
								<div class="requote"><a href="#">request a quote <img src="/images/reqquote.png" alt="quote-icon" /></a></div>
							</div>
						</div>
					{/foreach}
				{/if}
				
				{if $listing_content4}
					<h3>Our community</h3>
					{$listing_content4}
				{/if}
				
				{if $files}
					<h3>Downloads and forms</h3>
					{foreach $files as $file}
						<div class="media">
							<div class="store-mini-image">
									{if $file.files_image}<img class="media-object" src="{$file.files_image}" alt="downlaod-img">{/if}
							</div>
							<div class="media-body">
								<h4 class="media-heading">{$file.files_friendly_name}</h4>
								<div class="media-text">
								{$file.files_filename}
								</div>
								<div class="media-read">read more ></div>
								<div class="requote"><a href="{$file.files_path}" target="_blank">Download <img src="/images/download.png" alt="download-icon" title="download"/></a></div>
							</div>
						</div>					
					{/foreach}
				{/if}
				</div>
			</div>

{/block}