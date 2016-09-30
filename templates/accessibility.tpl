{block name=body}

<div id="pagehead">
  <div class="bannerout">
	  <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container" id="contpage">
	 <div class="row">
        <div class="col-sm-12 text-center" id="listtoptext">
  	  		<h1>{$listing_title}</h1>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content1}
        </div>
	 </div>
   </div>
</div>

<div id="access-grey">
  <div class="container">
     <div class="row">
        <div class="col-sm-12 text-center">
          <h3>Website</h3>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content2}
          
        </div>
     </div>
     <div class="row" id="contopt">

			<div class="col-sm-4 text-center ways">
				<img src="/images/access-phone.png" alt="Phone" class="img-responsive" />
				<div class="grey-text">
					<div class="bold">Call</div>
					<div>Free call: <a class="tel" href="tel:{$COMPANY.toll_free}">{$COMPANY.toll_free}</a></div>
					<div>Phone (outside Australia): <a class="tel" href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a></div>
				</div>
			</div>
			<div class="col-sm-4 text-center ways">
				<img src="/images/access-email.png" alt="Email" class="img-responsive" />
				<div class="grey-text">
					<div class="bold">Email</div>
					<div>{obfuscate email=$COMPANY.email attr='title="Click to email us"'}</div>
				</div>
			</div>
			<div class="col-sm-4 text-center ways">
				<img src="/images/access-hours.png" alt="Office hours" class="img-responsive" />
				<div class="grey-text">
					<div class="bold">Office hours</div>
					<div>Monday - Friday, 8:30am - 5:30pm (ACST)</div>
				</div>
			</div>
     </div>
     
  </div>
</div>

{if $listing_content3 || $listing_content4}
<div>
<div class="container">
    <br>
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content3}
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content4}
      </div>
    </div>
  </div>
</div>
{/if}
{/block}

{block name=tail}
<script type="text/javascript">

</script>
{/block}
