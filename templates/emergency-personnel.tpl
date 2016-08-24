{block name=body}
<div id="pagehead"> 
  <div class="bannerout"> 
    <img alt="Emergency personnel banner" src="{$listing_image}">
  </div> 
  <div class="container" id="contpage">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center" id="listtoptext">
          <h1>{$listing_title}</h1>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          <p>{$listing_content1}</p>
        </div>
    </div>
   </div>
</div>
<div id="greyblock1">
  <div class="container">
     <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content2}
        </div>
        <div class="col-sm-12 text-center">
          <p>For more information, you can download a free training kit.</p>
          <p><a href="#" class="btn-red btn">DOWNLOAD TRAINING KIT</a></p>
        </div>
     </div>
   </div>
</div>
<div id="whiteblock2">
   <div class="container">
     <div class="row">
        <div class="col-sm-12 text-center">
          <h3>Know what to do in medical emergency</h3>
          <p>In the first instance of a medical emergency, you should follow these four simple steps.</p>
        </div>
        <div class="col-sm-3 text-center">
          <img alt="Check" src="">
          <p>Check</p>
          <p>around your patients' wrists or neck (pulse point) for the genuine MedicAlert medical ID. If conscious, ask your patient if they are a MedicAlert member. </p>
        </div>
        <div class="col-sm-3 text-center">
          <img alt="Read" src="">
          <p>Read</p>
          <p>the medical and personal information engraved on the reverse of the patient's MedicAlert medical ID.</p>
        </div>
        <div class="col-sm-3 text-center">
          <img alt="Call" src="">
          <p>Call</p>
          <p>the 24/7 emergency hotline number engraved on the medical ID (08 8272 8822) to receive more detailed medical and personal information.</p>
        </div>
        <div class="col-sm-3 text-center">
          <img alt="Advice" src="">
          <p>Advice</p>
          <p>on handover that your patient is wearing a MedicAlert medical ID.</p>
        </div>
     </div>
   </div>
</div>
<div id="greyblock2">
  <div class="container">
   <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
        <h3>Know what MedicAlert Jewellery to look for</h3>
      </div>
      <div class="col-sm-8 col-sm-offset-2 text-center">
        {$listing_content3}
      </div>
   </div>
  </div>
</div>

<div id="orangebox" class="visible-xs">

</div>
{/block}

{block name=tail}
<script type="text/javascript">
$(document).ready(function(){

	 	$('#contact_form').validate();

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

	 	$('#email').rules("add", {
			email: true
		});

});

</script>
{/block}
