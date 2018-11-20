{block name="head"}
{/block} {block name=body}
<div id="headgrey">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h2>Welcome back, {$user.gname}</h2>
        <div>
          <a href="/process/user?logout=true" title="Click to log out">Log out</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="pagehead">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-left">
        {if $error}
        <div class="alert alert-danger fade in">{$error}</div>
        {/if}
      </div>
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" id="listtoptext" data-locked="{$user.maf.main.locked}" data-mslocked="{$user.maf.main.membership_system_locked}">
        <h1>{$listing_title}</h1>
        
        {* SET CURRENT MEMBER RECORD *}
        {if $user.maf.main.locked}{$member_record = $user.maf.pending}{else}{$member_record = $user.maf.update}{/if}
        
        {if $user.maf.main.membership_system_locked}
        <div class="profile-locked">
          {$listing_content2}
        </div>
        {elseif !$user.maf.main.membership_system_locked && $user.maf.main.locked}
        <div class="profile-locked">
          {$listing_content3}
        </div>
        {else if $user.maf.main.lifetime eq 1}
          <div class="profile-locked">
              Please be aware that as a lifetime member, you'll be charged $6.50 for any changes you want to make.
          </div>
        {/if}
      </div>
    </div>
  </div>
</div>

<div id="updateprof">
  <div class="container">
    <div id="update-profile-wrapper" data-last-validated="{$user.maf.main.last_validated_date}" data-display-confirmation="{$user.maf.main.display_confirm_details_notice}">
      <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
          <a href="/process/print-profile" target="_blank" title="Click to print profile" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my {if $user.maf.main.locked}current {/if}profile</a>
          {* if $user.maf.main.last_validated_date}<div>Last validated date: {$user.maf.main.last_validated_date|date_format:'%d/%m/%Y'}<br><br></div>{/if *}
        </div>
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" {if $user.maf.main.membership_system_locked}style="display:none;"{/if}>    
          <form id="update-profile-form" role="form" accept-charset="UTF-8" action="" method="post" data-changed="0">
            <input type="hidden" value="update-profile" name="action" id="action" />
            <input type="hidden" name="formToken" id="formToken" value="{$token}" />
            <input type="hidden" class="exclude" name="hschngd" id="hschngd" value="0" />
            <div class="row">
              <div class="col-sm-12 text-center">
                <div class="error-textbox" style="display:none"></div>
              </div>
            </div>
            <div id="accordion" class="validateaccordion">
              <h3>
                <div class="head-text">
                  <div class="head-title">Personal information</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="title" class="visible-ie-only">
                      Title:
                    </label>
                    <select class="selectlist-medium" id="title" name="user_title">
                      <option value="">Please select</option>
                      <option value="." {if $member_record.user_title eq '.'}selected="selected"{/if}>.</option>
                      <option value="Brother" {if $member_record.user_title eq 'Brother'}selected="selected"{/if}>Brother</option>
                      <option value="Dr" {if $member_record.user_title eq 'Dr'}selected="selected"{/if}>Dr</option>
                      <option value="Father" {if $member_record.user_title eq 'Father'}selected="selected"{/if}>Father</option>
                      <option value="Lady" {if $member_record.user_title eq 'Lady'}selected="selected"{/if}>Lady</option>
                      <option value="Lt Col" {if $member_record.user_title eq 'Lt Col'}selected="selected"{/if}>Lt Col</option>
                      <option value="Major General" {if $member_record.user_title eq 'Major General'}selected="selected"{/if}>Major General</option>
                      <option value="Master" {if $member_record.user_title eq 'Master'}selected="selected"{/if}>Master</option>
                      <option value="Miss" {if $member_record.user_title eq 'Miss'}selected="selected"{/if}>Miss</option>
                      <option value="Monsignor" {if $member_record.user_title eq 'Monsignor'}selected="selected"{/if}>Monsignor</option>
                      <option value="Mr" {if $member_record.user_title eq 'Mr'}selected="selected"{/if}>Mr</option>
                      <option value="Mrs" {if $member_record.user_title eq 'Mrs'}selected="selected"{/if}>Mrs</option>
                      <option value="Ms" {if $member_record.user_title eq 'Ms'}selected="selected"{/if}>Ms</option>
                      <option value="Prof" {if $member_record.user_title eq 'Prof'}selected="selected"{/if}>Prof</option>
                      <option value="Rev" {if $member_record.user_title eq 'Rev'}selected="selected"{/if}>Rev</option>
                      <option value="Rev. Canon" {if $member_record.user_title eq 'Rev. Canon'}selected="selected"{/if}>Rev. Canon</option>
                      <option value="Rev Fr" {if $member_record.user_title eq 'Rev Fr'}selected="selected"{/if}>Rev Fr</option>
                      <option value="Sir" {if $member_record.user_title eq 'Sir'}selected="selected"{/if}>Sir</option>
                      <option value="Sister" {if $member_record.user_title eq 'Sister'}selected="selected"{/if}>Sister</option>
                      <option value="The Hon." {if $member_record.user_title eq 'The Hon.'}selected="selected"{/if}>The Hon.</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="gname" class="visible-ie-only">
                      First Name<span>*</span>:
                    </label>
                    <input type="text" value="{$member_record.user_firstname}" class="form-control" id="gname" name="user_firstname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="middlename" class="visible-ie-only"> Middle Name: </label>
                    <input type="text" value="{$member_record.user_middlename}" class="form-control" id="middlename" name="user_middlename">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="surname" class="visible-ie-only">
                      Last Name<span>*</span>:
                    </label>
                    <input type="text" value="{$member_record.user_lastname}" class="form-control" id="surname" name="user_lastname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="address" class="visible-ie-only">
                      Address<span>*</span>:
                    </label>
                    <input type="text" value="{$member_record.user_address}" class="form-control" id="address" name="user_address" required>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="suburb" class="visible-ie-only">
                      Suburb<span>*</span>:
                    </label>
                    <input type="text" value="{$member_record.user_suburb}" class="form-control" id="suburb" name="user_suburb" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="state" class="visible-ie-only">
                      State<span>*</span>:
                    </label>
                    <select class="selectlist-medium" id="state" name="user_state_id" required>
                      <option value="">Please select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $member_record.user_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="postcode" class="visible-ie-only">
                      Postcode<span>*</span>:
                    </label>
                    <input type="text" maxlength="4" value="{$member_record.user_postcode}" class="form-control" id="postcode" name="user_postcode" pattern="[0-9]*" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="email" class="visible-ie-only">
                      Email<span>*</span>:
                    </label>
                    <input type="email" value="{$member_record.user_email}" class="form-control" id="reg-email" name="user_email" required>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="homephone" class="visible-ie-only"> Home phone<span>*</span>: </label>
                    <input type="text" maxlength="10" value="{$member_record.user_phone_home}" class="form-control" id="homephone" name="user_phone_home" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="workphone" class="visible-ie-only"> Work phone<span>*</span>: </label>
                    <input type="text" maxlength="10" value="{$member_record.user_phone_work}" class="form-control" id="workphone" name="user_phone_work" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="mobile" class="visible-ie-only">
                      Mobile<span>*</span>:
                    </label>
                    <input type="text" maxlength="10" value="{$member_record.user_mobile|replace:' ':''}" class="form-control" id="mobile" name="user_mobile" pattern="[0-9]*" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="preferred" class="visible-ie-only">
                      Preferred correspondence<span>*</span>:
                    </label>
                    <select class="selectlist-medium" id="preferred" name="correspondenceType" required>
                      <option value="Email" {if $member_record.correspondenceType neq 'Post' || !$member_record.contact_phone_home}selected="selected"{/if}>Email</option>
                      <option value="Post" {if $member_record.correspondenceType eq 'Post' && $member_record.contact_phone_home}selected="selected"{/if}>Post</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="dob" class="visible-ie-only">
                      Date of birth<span>*</span>:
                    </label>
                    <input type="hidden" value="{$member_record.user_dob}" name="user_dob" id="user_dob" required>
                    <input type="text" value="{$member_record.user_dob|date_format:'%d/%m/%Y'}" placeholder="DD/MM/YYYY" class="form-control" id="dob" name="dob" onchange="setDateValue('user_dob',this.value);" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="gender" class="visible-ie-only">
                      Gender<span>*</span>:
                    </label>
                    <select class="selectlist-medium" id="gender" name="user_gender" required>
                      <option value="M" {if $member_record.user_gender eq 'M'}selected="selected"{/if}>Male</option>
                      <option value="F" {if $member_record.user_gender eq 'F'}selected="selected"{/if}>Female</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
            </div>
            
              <h3>
                <div class="head-text">
                  <div class="head-title">Emergency contact</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emername" class="visible-ie-only"> Name: </label>
                    <input type="text" value="{$member_record.contact_name}" class="form-control" id="emername" name="contact_name">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emerrel" class="visible-ie-only"> Relationship: </label>
                    <input type="text" value="{$member_record.contact_relationship}" class="form-control" id="emerrel" name="contact_relationship">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emeraddress" class="visible-ie-only"> Address: </label>
                    <input type="text" value="{$member_record.contact_address}" class="form-control" id="emeraddress" name="contact_address">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emersuburb" class="visible-ie-only"> Suburb: </label>
                    <input type="text" value="{$member_record.contact_suburb}" class="form-control" id="emersuburb" name="contact_suburb">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emerstate" class="visible-ie-only"> State: </label>
                    <select class="selectlist-medium" id="emerstate" name="contact_state_id">
                      <option value="">Please select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $member_record.contact_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emerpostcode" class="visible-ie-only"> Postcode: </label>
                    <input type="text" maxlength="4" value="{$member_record.contact_postcode}" class="form-control" id="emerpostcode" name="contact_postcode" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emerhomephone" class="visible-ie-only"> Home phone <span>*</span>: </label>
                    <input type="text" maxlength="10" value="{$member_record.contact_phone_home}" class="form-control" id="emerhomephone" name="contact_phone_home" pattern="[0-9]*" required>
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="emerworkphone" class="visible-ie-only"> Work phone <span>*</span>: </label>
                    <input type="text" maxlength="10" value="{$member_record.contact_phone_work}" class="form-control" id="emerworkphone" name="contact_phone_work" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emermobile" class="visible-ie-only"> Mobile <span>*</span>: </label>
                    <input type="text" value="{$member_record.contact_mobile}" maxlength="10" class="form-control" id="emermobile" name="contact_mobile" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>

              <h3>
                <div class="head-text">
                  <div class="head-title">Doctor Information</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docname" class="visible-ie-only"> Name: </label>
                    <input type="text" value="{$member_record.doc_name}" class="form-control" id="docname" name="doc_name">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docmedcentre" class="visible-ie-only"> Medical centre: </label>
                    <input type="text" value="{$member_record.doc_medical_centre}" class="form-control" id="docmedcentre" name="doc_medical_centre">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docaddress" class="visible-ie-only"> Address: </label>
                    <input type="text" value="{$member_record.doc_address}" class="form-control" id="docaddress" name="doc_address">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docsuburb" class="visible-ie-only"> Suburb: </label>
                    <input type="text" value="{$member_record.doc_suburb}" class="form-control" id="docsuburb" name="doc_suburb">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docstate" class="visible-ie-only"> State: </label>
                    <select class="selectlist-medium" id="docstate" name="doc_state_id">
                      <option value="">Please select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $member_record.doc_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docpostcode" class="visible-ie-only"> Postcode: </label>
                    <input type="text" maxlength="4" value="{$member_record.doc_postcode}" class="form-control" id="docpostcode" name="doc_postcode" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only"> Phone: </label>
                    <input type="text" value="{$member_record.doc_phone}" maxlength="10" class="form-control" id="docphone" name="doc_phone" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="docfileno" class="visible-ie-only"> File No. (if known): </label>
                    <input type="text" maxlength="50" value="{$member_record.doc_file_no}" class="form-control" id="docfileno" name="doc_file_no">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>

              <h3>
                <div class="head-text">
                  <div class="head-title">Organ/Tissue donation</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">
                      It is recommended that you officially register your donation decision on the <a href="https://www.humanservices.gov.au/customer/services/medicare/australian-organ-donor-register" title="Australian Organ Donor Register" target="_blank">Australian Organ Donor Register</a>, and discuss your decision with your family.
                    </p>
                    <input type="radio" name="user_donor" id="organdonationy" class="form-control" value="t" {if $member_record.user_donor eq 't'}checked="checked" {/if}/>
                    <label for="organdonationy" class="radiolab">Yes</label>

                    <input type="radio" name="user_donor" id="organdonationn" class="form-control" value="f" {if $member_record.user_donor neq 't'}checked="checked" {/if} />
                    <label for="organdonationn" class="radiolab">No</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="organotherinfo" class="visible-ie-only">Other Info:</label>
                    <textarea id="organotherinfo" name="user_donorFreeText" class="form-control">{$member_record.user_donorFreeText}</textarea>
                  </div>
                </div>
              </div>


              <h3>
                <div class="head-text">
                  <div class="head-title">Blood group</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="bloodgroup" class="visible-ie-only">Blood group:</label>
                    {$op = ['O Positive', 'O Rh (D) Positive', 'O Rh POS']}
                    {$on = ['O Negative', 'O Rh (D) Negative', 'O Rh NEG']}
                    {$ap = ['A Positive', 'A Rh (D) Positive', 'A Rh POS']}
                    {$an = ['A Negative', 'A Rh (D) Negative', 'A Rh NEG']}
                    {$bp = ['B Positive', 'B Rh (D) Positive', 'B Rh POS']}
                    {$bn = ['B Negative', 'B Rh (D) Negative', 'B Rh NEG', 'Rh (D) B Negative']}
                    {$abp = ['AB Positive', 'AB Rh (D) Positive', 'AB Rh POS']}
                    {$abn = ['AB Negative', 'AB Rh (D) Negative', 'AB Rh NEG']}
                    <select class="selectlist-medium" id="bloodgroup" name="blood_group">
                      <option value="">Select an option</option>
                      <option value="O Positive" {if $member_record.blood_group|in_array:$op}selected="selected"{/if}>O Positive</option>
                      <option value="O Negative" {if $member_record.blood_group|in_array:$on}selected="selected"{/if}>O Negative</option>
                      <option value="A Positive" {if $member_record.blood_group|in_array:$ap}selected="selected"{/if}>A Positive</option>
                      <option value="A Negative" {if $member_record.blood_group|in_array:$an}selected="selected"{/if}>A Negative</option>
                      <option value="B Positive" {if $member_record.blood_group|in_array:$bp}selected="selected"{/if}>B Positive</option>
                      <option value="B Negative" {if $member_record.blood_group|in_array:$bn}selected="selected"{/if}>B Negative</option>
                      <option value="AB Positive" {if $member_record.blood_group|in_array:$abp}selected="selected"{/if}>AB Positive</option>
                      <option value="AB Negative" {if $member_record.blood_group|in_array:$abn}selected="selected"{/if}>AB Negative</option>
                    </select>
                  </div>
                </div>
              </div>


              <h3>
                <div class="head-text">
                  <div class="head-title">Medical conditions</div>
                </div>
              </h3>
              <div class="acc-body">
                {if $member_record.conditions}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove a condition untick the relevant box.</p>
                  </div>
                  {foreach $member_record.conditions as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="conditions[]" id="medicalcond{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1' || $v.status eq '2'}checked="checked" {/if}/>
                    <label for="medicalcond{$k}" class="radiolab">{$v.value}</label>
                  </div>
                  {/foreach}
                </div>
                <br /> {/if}
                <div class="row">
                  <div class="col-sm-12 form-group text-left">
                    <label class="" for="medicalcond-other">Add new conditions in the text box below using a semicolon(;) to separate each condition.</label>
                  </div>
                  <div class="col-sm-12 form-group">
                    <textarea maxlength="800" id="medicalcond-other" name="conditions[]" class="form-control"></textarea>
                  </div>
                </div>
              </div>



              <h3>
                <div class="head-text">
                  <div class="head-title">Allergies</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">The Australasian Society of Clinical Immunology & Allergy recommends allergies should always be confirmed (authenticated) by your doctor.</p>
                  </div>
                </div>
                <br /> {if $member_record.allergies}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove an allergy untick the relevant box.</p>
                  </div>
                  {foreach $member_record.allergies as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="allergies[]" id="allergies{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1' || $v.status eq '2'}checked="checked" {/if}/>
                    <label for="allergies{$k}" class="radiolab">{$v.value}</label>
                  </div>
                  {/foreach}
                </div>
                <br /> {/if}
                <div class="row">
                  <div class="col-sm-12 form-group text-left">
                    <label class="" for="allergies-other">Add new allergies in the text box below using a semicolon(;) to separate each allergy.</label>
                  </div>
                  <div class="col-sm-12 form-group">
                    <textarea maxlength="800" id="allergies-other" name="allergies[]" class="form-control"></textarea>
                  </div>
                </div>
              </div>

              <h3>
                <div class="head-text">
                  <div class="head-title">Medications</div>
                </div>
              </h3>

              <div class="acc-body">
                {if $member_record.medications}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove a medication untick the relevant box.</p>
                  </div>
                  {foreach $member_record.medications as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="medications[]" id="medications{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1' || $v.status eq '2'}checked="checked" {/if}/>
                    <label for="medications{$k}" class="radiolab">{$v.value}</label>
                  </div>
                  {/foreach}
                </div>
                <br /> {/if}
                <div class="row">
                  <div class="col-sm-12 form-group text-left">
                    <label class="" for="medications-other">Add new medications in the text box below using a semicolon(;) to separate each medication.</label>
                  </div>
                  <div class="col-sm-12 form-group">
                    <textarea maxlength="800" id="medications-other" name="medications[]" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              
              
              <h3>
                <div class="head-text">
                  <div class="head-title">NDIS details</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only">Plan Number:</label>
                    <input type="text" value="{$member_record.ndis_plan_number}" class="form-control" id="ndis_plan_number" name="ndis_plan_number">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="bloodgroup" class="visible-ie-only">Plan type:</label>
                    <select class="selectlist-medium" id="ndis_plan_type" name="ndis_plan_type" onchange="RenderPlanManagedFields();">
                      <option value="">Select an option</option>
                      <option value="NDIS Managed" {if $member_record.ndis_plan_type eq 'NDIS Managed'}selected="selected"{/if}>NDIS Managed</option>
                      <option value="Plan Managed" {if $member_record.ndis_plan_type eq 'Plan Managed'}selected="selected"{/if}>Plan Managed</option>
                      <option value="Self Managed" {if $member_record.ndis_plan_type eq 'Self Managed'}selected="selected"{/if}>Self Managed</option>
                    </select>
                  </div>
                </div>
                <div class="row plan-managed-wrapper">
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only">Plan Manager's Name:</label>
                    <input type="text" value="{$member_record.ndis_manager_name}" class="form-control" id="ndis_manager_name" name="ndis_manager_name">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only">Plan Manager's Company Name:</label>
                    <input type="text" value="{$member_record.ndis_manager_company}" class="form-control" id="ndis_manager_company" name="ndis_manager_company">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row plan-managed-wrapper">
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only">Plan Manager's Email:</label>
                    <input type="email" value="{$member_record.ndis_manager_email}" class="form-control" id="ndis_manager_email" name="ndis_manager_email">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only">Plan Manager's Phone:</label>
                    <input type="text" value="{$member_record.ndis_manager_phone}" maxlength="10" class="form-control" id="ndis_manager_phone" name="ndis_manager_phone" pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>                 
              </div>
              
              <h3>
                <div class="head-text">
                  <div class="head-title">Other info</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="dva" class="visible-ie-only"> DVA gold card number: </label>
                    <input type="text" value="{$member_record.attributes.10}" class="form-control" id="dva" name="dvagoldcard">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthfundname" class="visible-ie-only"> Health fund name: </label>
                    <input type="text" value="{$member_record.attributes.12}" class="form-control" id="healthfundname" name="healthfundname">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthfundno" class="visible-ie-only"> Health fund number: </label>
                    <input type="text" value="{$member_record.attributes.13}" class="form-control" id="healthfundno" name="healthfundnumber">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthid" class="visible-ie-only">
                      Individual health identifier: <img src="/images/question-mark.png" alt="Health identifier information" title="Note: An Individual Health Identifier (IHI) number is your numerical identifier that uniquely identifies each individual in the Australian healthcare system (my health record).
The IHI is part of the government e-health initiative developed to enhance the way information is exchanged, shared and managed in the Australian health sector." data-toggle="tooltip" data-placement="top" />
                    </label>
                    <input type="text" value="{$member_record.attributes.14}" class="form-control" id="healthid" name="ehealth">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="seniorscard" class="visible-ie-only"> Seniors card: </label>
                    <input type="text" value="{$member_record.attributes.18}" class="form-control" id="seniorscard" name="seniorscard">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <hr />
                    <p class="text-left">Only vital information that should be known in an emergency situation is to be recorded here. For example: additional emergency contact details, advance care directive, specialist contact details and special needs.</p>

                    <p class="text-left">
                      <span class="bold">Note:</span> the first 95 characters (including spaces) are likely to print on your membership card, the remainder of the details will be held in your profile.
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="emergencyinfo" class="visible-ie-only"> Emergency information: </label>

                    <textarea maxlength="1000" id="emergencyinfo" name="emergencyInfo" class="form-control">{$member_record.emergencyInfo}</textarea>
                  </div>
                </div>
              </div>

              <h3>
                <div class="head-text">
                  <div class="head-title">Documents</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-12 form-group text-left">
                    <p>Relevant documents such as allergy action plan, asthma action plan, advance care directive, passports, implant device information, specialist letter (e.g. difficult intubation) or a medical summary from your treating doctor can be added to your profile. Should you wish to add documents of these types to your member profile, please scan and email to enquiry@medicalert.org.au with your name and membership number.</p>

                    <p><b>Medical record files:</b></p>
                      {$hasfile = 0}
                      {foreach $member_record.medicalRecordFiles as $k => $v}
                        {if $v.fileId}
                          {$hasfile = 1}
                          <div class="filename"><a target="_blank" href="/process/user?action=getfile&fid={$v.fileId}" title="Click to download">{$v.fileName}</a></div>
                          <div class="filedesc">{$v.fileDescription}</div>
                         {* <div class="filedownload"><a target="_blank" href="/process/user?action=getfile&fid={$v.fileId}" title="Click to download">Download file</a></div> *}
                        {/if}
                      {/foreach}
                      {if $hasfile eq 0}
                        <div class="file-emtpy">None.</div>
                      {/if}
                    <br>
                    <p><b>Other Files</b></p>
                      {$hasfile = 0}
                      {foreach $member_record.otherFiles as $k => $v}
                        {if $v.fileId}
                          {$hasfile = 1}
                          <div class="filename"><a target="_blank" href="/process/user?action=getfile&fid={$v.fileId}" title="Click to download">{$v.fileName}</a></div>
                          <div class="filedesc">{$v.fileDescription}</div>
                          {* <div class="filedownload"><a target="_blank" href="/process/user?action=getfile&fid={$v.fileId}" title="Click to download">Download file</a></div> *}
                        {/if}
                      {/foreach}
                      {if $hasfile eq 0}
                        <div class="file-emtpy">None.</div>
                      {/if}
                    <br>
                    <p>
                      <span class="bold">PLEASE NOTE:</span> To add or remove files, please contact Membership Services on <a href="tel:{$COMPANY.toll_free}" title="Click to call">{$COMPANY.toll_free}</a> (Mon-Fri, 8.30am-5.30pm CST) or email {obfuscate email=$COMPANY.email attr='title="Click to email us"'}
                    </p>

                    <p>
                      <a href="https://get.adobe.com/reader/" target="_blank" title="Click to download Adobe Acrobat Reader"><img src="/images/adobereader.png" alt="Adobe reader"> Get Adobe reader</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            {if true || !$user.maf.main.membership_system_locked}
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1">
                <br /> <a href="/process/print-profile" target="_blank" title="Click to print profile" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my {if $user.maf.main.locked}current {/if}profile</a> <br />
                {$listing_content1}
              </div>
            </div>
            <br />
            {if $user.maf.main.lifetime eq 1}
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1 text-left">
                <input type="checkbox" class="exclude" id="order-card" name="order_card" value="yes" /> <label class="checkbox-label" for="order-card">Tick this box if you'd like to order a membership card containing your updated information for $8.</label>
                <br><br>
              </div>
            </div>
            {/if}
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1 text-left form-group">
                <input type="checkbox" class="form-control exclude" id="profile-confirmation" name="profile-confirmation" value="yes" required="required"/> <label class="checkbox-label" for="profile-confirmation">By ticking this box I am confirming that I have reviewed all the information stored in my profile and am verifying that all information is up-to-date.</label>
                <div class="error-msg help-block"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
              <div class="row error-msg" id="form-error" style="display:none"></div>
                <div class="error-alert" style="display: none;">
                  <div class="alert alert-danger fade in ">
                    <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
                    <strong></strong>
                  </div>
                </div>
                <div class="success-alert" style="display: none;">
                  <div class="alert alert-success fade in ">
                    <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
                    <strong></strong>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-sm-offset-3">
                <br>
                <a href="javascript:void(0)" class="btn btn-red process-cnt" id="submit-btn" title="Click to save changes" onclick="$('#update-profile-form').submit();">Save my changes and verify</a>
              </div>
            </div>
            {/if}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script type="text/javascript" src="/node_modules/jquery-ui-dist/jquery-ui.min.js"></script>
<script src="/node_modules/selectboxit/src/javascripts/jquery.selectBoxIt.min.js"></script>
<script>
  $(function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $("#accordion").accordion({
      icons: icons,
      heightStyle: "content",
      collapsible: true,
      activate: function( event, ui ) {
        if(!$.isEmptyObject(ui.newHeader.offset()) && !isScrolledIntoView(ui.newHeader)) {
            $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top }, 'slow');
        }
    }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {

    {if $user.maf.main.membership_system_locked}
    	lockFields();
    {/if}

    RenderPlanManagedFields();

    $("select").selectBoxIt();
    $('[data-toggle="tooltip"]').tooltip();

    $('#update-profile-form').validate({
      onkeyup: false,
      onfocusout: false,
      onclick: false,
      submitHandler: function(form) {
        SubmitProfileForm($(form).attr('id'));
      }
    });

    $("#dob").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "-120:+0",
      maxDate: "-1D"
    });

    $('#postcode').rules("add", {
      digits: true,
      minlength: 3
    });

    $('#emerpostcode').rules("add", {
      digits: true,
      minlength: 3
    });

    $('#docpostcode').rules("add", {
      digits: true,
      minlength: 3
    });

    $('#mobile').rules("add", {
      required: {
        depends: function(element){
          if($('#homephone').val() == '' && $('#workphone').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify your mobile number"
      }
    });
    
    $('#homephone').rules("add", {
      required: {
        depends: function(element){
          if($('#mobile').val() == '' && $('#workphone').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify your mobile number"
      }
    });
    
    $('#workphone').rules("add", {
      required: {
        depends: function(element){
          if($('#homephone').val() == '' && $('#mobile').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify your mobile number"
      }
    });

    $('#emerhomephone').rules("add", {
      required: {
        depends: function(element){
          if($('#emerworkphone').val() == '' && $('#emermobile').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify phone number"
      }
    });
    
    $('#emerworkphone').rules("add", {
      required: {
        depends: function(element){
          if($('#emerhomephone').val() == '' && $('#emermobile').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify phone number"
      }
    });
    
    $('#emermobile').rules("add", {
      required: {
        depends: function(element){
          if($('#emerhomephone').val() == '' && $('#emerworkphone').val() == ''){
            return true;
          }
          return false;
        }
      },
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        required: "At least 1 of the phone numbers is required",
        equalTo: "Please verify your mobile number"
      }
    });
    
    $('#ndis_manager_phone').rules("add", {
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify phone number"
      }
    });
    
    $('#homephone').rules("add", {
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify phone number"
      }
    });
    
    $('#workphone').rules("add", {
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify phone number"
      }
    });

    $('#docphone').rules("add", {
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify phone number"
      }
    });
    
    
    var $form = $('#update-profile-form :input').not('.exclude'),
    origForm = $form.serialize();
    $('#update-profile-form :input').not('.exclude').on('change input', function() {
        $('#hschngd').val($form.serialize() !== origForm ? 1 : 0);
    });
    
  });

  function SubmitProfileForm(FORM) {
    $('body').css('cursor', 'wait');
    ClearPlanManagedFields();
    var datastring = $('#' + FORM).serialize();
    $('#' + FORM).find('.error-alert').hide();
    $('#' + FORM).find('.success-alert').hide();
    $('#submit-btn').html('SAVING...');
    $.ajax({
      type: "POST",
      url: "/process/user",
      cache: false,
      data: datastring,
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.url){
            window.location.href = obj.url;
          }else if(obj.error){
            $('#' + FORM).find('.error-alert').find('strong').html(obj.error);
            $('#' + FORM).find('.error-alert').fadeIn('slow');
          }else if(obj.success){
            var chk_url = window.location.href;
            if(chk_url.indexOf('submit=true#profile-locked') != '-1'){
              window.location.reload();
            } else{
              window.location.href = '/update-my-profile?submit=true#profile-locked';
            }
          }
        }catch(err){
          console.log('TRY-CATCH error');
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + FORM).find('.error-alert').find('strong').html('Something went wrong or your session has expired.<br>Please refresh the page and try again or <a href="/contact-us">contact us</a>.');
        $('#' + FORM).find('.error-alert').fadeIn('slow');
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }

  function redirectWin(url) {
    window.location.replace(url);
  }

  function convert_to_mysql_date_format(str) {
    var dateArr = str.split("/");
    return dateArr[2] + '-' + dateArr[1] + '-' + dateArr[0];
  }

  function setDateValue(id, date) {
    $("#" + id).val(convert_to_mysql_date_format(date));
  }

  function lockFields(){
    $('#update-profile-form :input').attr('disabled', 'disabled');
  }
  
  function RenderPlanManagedFields(){
    if($('#ndis_plan_type').val() == 'Plan Managed'){
      $('.plan-managed-wrapper').show('slow');
    }else{
      $('.plan-managed-wrapper').hide('slow');
    }
  };
  
  function ClearPlanManagedFields(){
    if($('#ndis_plan_type').val() != 'Plan Managed'){
      $('.plan-managed-wrapper').find('input').val('');
    }
  };
</script>
{/block}




