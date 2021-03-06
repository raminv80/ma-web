<div class=" col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 text-center" id="contform">
<h3>NDIS Registration Form</h3>
  <form id="contact_form" accept-charset="UTF-8" method="post" action="/process/ndis-contact-us" novalidate="novalidate">
    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
    <input type="hidden" value="NDIS" name="form_name" id="form_name" />
    <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />

    <div class="row">
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="name">Full name<span>*</span>:</label>
        <input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required>
        <div class="error-msg help-block"></div>
      </div>
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="email">Email<span>*</span>:</label>
        <input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required>
        <div class="error-msg help-block"></div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="phone">Phone<span>*</span>:</label>
        <input class="form-control" value="{if $post.phone}{$post.phone}{else}{$user.maf.main.user_mobile}{/if}" type="text" name="phone" id="phone" required pattern="[0-9]*">
        <div class="error-msg help-block"></div>
      </div>
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="plan_no">NDIS Plan number<span>*</span>:</label>
        <input class="form-control" value="{if $post.plan_no}{$post.plan_no}{/if}" type="text" name="plan_no" id="plan_no"  pattern="[0-9]*" required>
      <div class="error-msg help-block"></div>
      </div>
    </div>  

    <div class="row">
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="plan_type">Plan<span>*</span></label>
        <select class="selectlist-medium" id="plan-type" name="plan_type" required onchange="UpdatePlanType(this.value);">
          <option value="">Please select</option>
          <option value="NDIS Managed" {if $post.plan_type eq "NDIS Managed"}selected{/if}>NDIS Managed</option>
          <option value="Self Managed" {if $post.plan_type eq "Self-managed"}selected{/if}>Self Managed</option>
          <option value="Plan Managed"{if $post.plan_type eq "Plan managed"}selected{/if}>Plan Managed</option>
        </select>
        <div class="error-msg help-block"></div>
      </div>
    </div>
    
    <div class="row {if $post.plan_type neq 'Plan managed'}hide{/if}" id="plan-manager-group" style="border: 1px solid #eaebec; border-radius: 5px; margin-bottom: 20px;" >
      <h4>Plan Manager Details</h4>
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="pmanager_name">Plan Manager's Name<span>*</span>:</label>
        <input class="form-control" value="{if $post.pmanager_name}{$post.pmanager_name}{/if}" type="text" name="pmanager_name" id="pmanager-name" required>
        <div class="error-msg help-block"></div>
      </div>
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="pmanager_company_name">Plan Manager's Company Name<span>*</span>:</label>
        <input class="form-control" value="{if $post.pmanager_company_name}{$post.pmanager_company_name}{/if}" type="text" name="pmanager_company_name" id="pmanager-company-name" required>
        <div class="error-msg help-block"></div>
      </div>      
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="pmanager_email">Plan Manager's Email<span>*</span>:</label>
        <input class="form-control" value="{if $post.pmanager_email}{$post.pmanager_email}{else}{$user.email}{/if}" type="email" name="pmanager_email" id="pmanager-email" required>
        <div class="error-msg help-block"></div>
      </div>
      <div class="col-sm-6 form-group">
        <label class="visible-ie-only" for="pmanager_phone">Plan Manager's Phone<span>*</span>:</label>
        <input class="form-control" value="{if $post.pmanager_phone}{$post.pmanager_phone}{else}{$user.maf.main.user_mobile}{/if}" type="text" name="pmanager_phone" id="pmanager-phone" required pattern="[0-9]*">
        <div class="error-msg help-block"></div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-sm-6 form-group">        
        <p>Already an existing MedicAlert member:</p>
        <label class="radio-inline"><input type="radio" name="maf_member" id="maf-member" value="1" {if $post.maf_member eq 1}checked{/if}>Yes</label>
        <label class="radio-inline"><input type="radio" name="maf_member" id="maf-member" value="0" {if $post.maf_member eq 0}checked{/if}>No</label>        
      </div>          
      <div class="col-sm-6 form-group {if $post.maf_member eq 0}hide{/if}" >
        <label class="visible-ie-only" for="maf_no">Membership number:</label>
        <input class="form-control" value="{if $post.maf_no}{$post.maf_no}{else}{$user.id}{/if}" type="text" name="maf_no" id="maf-no"  pattern="[0-9]*">
        <div class="error-msg help-block"></div>
      </div>
    </div>  
      
    <div class="row">
      <div style="height:0;overflow:hidden;">
        <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
      </div>
      <div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    </div> 

    <div class="row">
      <div class="col-sm-12">
        <input type="button" value="SEND" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
      </div>
    </div>
  </form>
</div>

