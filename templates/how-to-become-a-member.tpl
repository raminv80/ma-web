{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{$listing_image}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				{$listing_content1}
			</div>
		</div>
	</div>
</div>

<div id="cost">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1">
			<div class="row">

			<div class="col-sm-3 text-center ">
				<img src="/images/join-1.png" alt="Choose your medical ID " class="img-responsive" />
				<div class="cost-text"><span>1.</span> Choose your medical ID from our <a href="/products">product range</a>. Prices start from just {$CONFIG_VARS['medical_id_price']}</div>
			</div>
			<div class="hidden-xs col-sm-1 text-center">
				<img src="/images/join-arrow.png" alt="Arrow" />
			</div>
			<div class="col-sm-3 text-center">
				<img src="/images/join-2.png" alt="Fill out your details" class="img-responsive" />
				<div class="cost-text"><span>2.</span> Fill out your details to receive a membership number</div>
			</div>
			<div class="hidden-xs col-sm-1 text-center">
				<img src="/images/join-arrow.png" alt="Arrow" />
			</div>
			<div class="col-sm-3 text-center">
				<img src="/images/join-3.png" alt="Postage" class="img-responsive" />
				<div class="cost-text"><span>3.</span> Sign in to the Members’ Area to complete your profile </div>
			</div>

			</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<br />
				{$listing_content2}
				<a href="/products" class="btn btn-red">Find a product</a>
			</div>
		</div>
	</div>
</div>

<div id="cost-grey" class="howto">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				{$listing_content3}
				<br />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3 text-center ways">
				<img src="/images/join-post.png" alt="Post" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Post</span><span>*</span><br />
					Australia MedicAlert Foundation,<br />
					GPO Box 9963 in your<br />
					capital city<br />
					(e.g. ADELAIDE SA 5001)
				</div>
			</div>
			<div class="col-sm-3 text-center ways">
				<img src="/images/access-phone.png" alt="Phone" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Call</span><br />
                  <a class="tel" href="tel:{$COMPANY.toll_free}" title="Click to call">{$COMPANY.toll_free}</a>
                   <br />
					between 8.30am - 5.30pm ACST
				</div>
			</div>
			<div class="col-sm-3 text-center ways">
				<img src="/images/access-email.png" alt="Email" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Email</span><span>*</span><br />
					{obfuscate email=$COMPANY.email attr='title="Click to email us"'}
				</div>
			</div>
			<div class="col-sm-3 text-center ways">
				<img src="/images/join-fax.png" alt="Fax" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Fax</span><span>*</span><br />
					<a href="tel:{$COMPANY.fax}">{$COMPANY.fax}</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<br />
				*Please download <a href="/uploads/documents/maf_membership_form.pdf" target="_blank" title="Click to download the membership application form">a membership application form</a> and complete your details to join by post, email or fax.
				<br />
				<br />
				<a href="/uploads/documents/maf_membership_form.pdf" target="_blank" title="Click to download the membership application form" class="btn btn-red">Download form</a>
				<br />
				<br />
				{$listing_content4}
			</div>
		</div>
	</div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#readmore").click(function(){
		$(this).hide();
		$("#benefits #benefit1").show();
	});

	$("#readmore2").click(function(){
		$(this).hide();
		$("#benefits #benefit4 .tablebox").css("height","auto");
	});

});
</script>
{/block}
