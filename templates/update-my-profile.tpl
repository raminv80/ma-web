{block name="head"}
<!-- <link href="/includes/css/jquery-ui.css" rel="stylesheet" media="screen"> -->
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
      <div class="col-sm-12 text-center" id="listtoptext">
        <h1>{$listing_title}</h1>
        {if $user.maf.main.locked}
        <div class="profile-locked">
          {$listing_content2}
          <br><br>
        </div>
        {/if}
      </div>
    </div>
  </div>
</div>

<div id="updateprof">
  <div class="container">
    <div id="update-profile-wrapper">
      <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
          <a href="/process/print-profile" target="_blank" title="Click to print profile" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my profile</a>

          <form id="update-profile-form" role="form" accept-charset="UTF-8" action="" method="post">
            <input type="hidden" value="update-profile" name="action" id="action" />
            <input type="hidden" name="formToken" id="formToken" value="{$token}" />

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
                    <select class="selectlist-medium" id="title" name="title">
                      <option value="">Please select</option>
                      <option value="Mr" {if $user.maf.update.user_title eq 'Mr'}selected="selected"{/if}>Mr</option>
                      <option value="Mrs" {if $user.maf.update.user_title eq 'Mrs'}selected="selected"{/if}>Mrs</option>
                      <option value="Miss" {if $user.maf.update.user_title eq 'Miss'}selected="selected"{/if}>Miss</option>
                      <option value="Dr" {if $user.maf.update.user_title eq 'Dr'}selected="selected"{/if}>Dr</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="gname" class="visible-ie-only">
                      First Name<span>*</span>:
                    </label>
                    <input type="text" value="{$user.maf.update.user_firstname}" class="form-control" id="gname" name="user_firstname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="middlename" class="visible-ie-only"> Middle Name: </label>
                    <input type="text" value="{$user.maf.update.user_middlename}" class="form-control" id="middlename" name="user_middlename">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="surname" class="visible-ie-only">
                      Last Name<span>*</span>:
                    </label>
                    <input type="text" value="{$user.maf.update.user_lastname}" class="form-control" id="surname" name="user_lastname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="address" class="visible-ie-only">
                      Address<span>*</span>:
                    </label>
                    <input type="text" value="{$user.maf.update.user_address}" class="form-control" id="address" name="user_address" required>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="suburb" class="visible-ie-only">
                      Suburb<span>*</span>:
                    </label>
                    <input type="text" value="{$user.maf.update.user_suburb}" class="form-control" id="suburb" name="user_suburb" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="state" class="visible-ie-only">
                      State<span>*</span>:
                    </label>
                    <select class="selectlist-medium" id="state" name="user_state_id" required>
                      <option value="">Select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $user.maf.update.user_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="postcode" class="visible-ie-only">
                      Postcode<span>*</span>:
                    </label>
                    <input type="text" maxlength="4" value="{$user.maf.update.user_postcode}" class="form-control" id="postcode" name="user_postcode" pattern="[0-9]" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="email" class="visible-ie-only">
                      Email<span>*</span>:
                    </label>
                    <input type="email" value="{$user.maf.update.user_email}" class="form-control" id="reg-email" name="user_email" required>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="homephone" class="visible-ie-only"> Home phone: </label>
                    <input type="text" value="{$user.maf.update.user_phone_home}" class="form-control" id="homephone" name="user_phone_home" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="workphone" class="visible-ie-only"> Work phone: </label>
                    <input type="text" value="{$user.maf.update.user_phone_work}" class="form-control" id="workphone" name="user_phone_work" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="mobile" class="visible-ie-only">
                      Mobile<span>*</span>:
                    </label>
                    <input type="text" maxlength="10" value="{$user.maf.update.user_mobile}" class="form-control" id="mobile" name="user_mobile" pattern="[0-9]" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="preferred" class="visible-ie-only">
                      Preferred correspondence<span>*</span>:
                    </label>
                    <select class="selectlist-medium" id="preferred" name="correspondenceType" required>
                      <option value="">Select an option</option>
                      <option value="Email" {if $user.maf.update.correspondenceType neq 'Post'}selected="selected"{/if}>Email</option>
                      <option value="Post" {if $user.maf.update.correspondenceType eq 'Post'}selected="selected"{/if}>Post</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="dob" class="visible-ie-only">
                      Date of birth<span>*</span>:
                    </label>
                    <input type="hidden" value="{$user.maf.update.user_dob}" name="user_dob" id="user_dob" required>
                    <input type="text" value="{$user.maf.update.user_dob|date_format:'%d/%m/%Y'}" placeholder="DD/MM/YYYY" class="form-control" id="dob" name="dob" onchange="setDateValue('user_dob',this.value);" required>
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
                    <input type="text" value="{$user.maf.update.contact_name}" class="form-control" id="emername" name="contact_name">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emerrel" class="visible-ie-only"> Relationship: </label>
                    <input type="text" value="{$user.maf.update.contact_relationship}" class="form-control" id="emerrel" name="contact_relationship">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emeraddress" class="visible-ie-only"> Address: </label>
                    <input type="text" value="{$user.maf.update.contact_address}" class="form-control" id="emeraddress" name="contact_address">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emersuburb" class="visible-ie-only"> Suburb: </label>
                    <input type="text" value="{$user.maf.update.contact_suburb}" class="form-control" id="emersuburb" name="contact_suburb">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emerstate" class="visible-ie-only"> State: </label>
                    <select class="selectlist-medium" id="emerstate" name="contact_state_id">
                      <option value="">Select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $user.maf.update.contact_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="emerpostcode" class="visible-ie-only"> Postcode: </label>
                    <input type="text" maxlength="4" value="{$user.maf.update.contact_postcode}" class="form-control" id="emerpostcode" name="contact_postcode" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emerhomephone" class="visible-ie-only"> Home phone: </label>
                    <input type="text" value="{$user.maf.update.contact_phone_home}" class="form-control" id="emerhomephone" name="contact_phone_home" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="emerworkphone" class="visible-ie-only"> Work phone: </label>
                    <input type="text" value="{$user.maf.update.contact_phone_work}" class="form-control" id="emerworkphone" name="contact_phone_work" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="emermobile" class="visible-ie-only"> Mobile: </label>
                    <input type="text" maxlength="10" value="{$user.maf.update.contact_mobile}" class="form-control" id="emermobile" name="contact_mobile" pattern="[0-9]">
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
                    <input type="text" value="{$user.maf.update.doc_name}" class="form-control" id="docname" name="doc_name">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docmedcentre" class="visible-ie-only"> Medical centre: </label>
                    <input type="text" value="{$user.maf.update.doc_medical_centre}" class="form-control" id="docmedcentre" name="doc_medical_centre">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docaddress" class="visible-ie-only"> Address: </label>
                    <input type="text" value="{$user.maf.update.doc_address}" class="form-control" id="docaddress" name="doc_address">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docsuburb" class="visible-ie-only"> Suburb: </label>
                    <input type="text" value="{$user.maf.update.doc_suburb}" class="form-control" id="docsuburb" name="doc_suburb">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docstate" class="visible-ie-only"> State: </label>
                    <select class="selectlist-medium" id="docstate" name="doc_state_id">
                      <option value="">Select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $user.maf.update.doc_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-6 form-group">
                    <label for="docpostcode" class="visible-ie-only"> Postcode: </label>
                    <input type="text" maxlength="4" value="{$user.maf.update.doc_postcode}" class="form-control" id="docpostcode" name="doc_postcode" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="docphone" class="visible-ie-only"> Phone: </label>
                    <input type="text" value="{$user.maf.update.doc_phone}" class="form-control" id="docphone" name="doc_phone" pattern="[0-9]">
                    <div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="docfileno" class="visible-ie-only"> File No. (if known): </label>
                    <input type="text" maxlength="10" value="{$user.maf.update.doc_file_no}" class="form-control" id="docfileno" name="doc_file_no" pattern="[0-9]">
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
                      It is recommended that you officially register your donation decision on the <a href="#" target="_blank">Australian Organ Donor Register</a>, and discuss your decision with your family.
                    </p>
                    <input type="radio" name="user_donor" id="organdonationy" class="form-control" value="t" {if $user.maf.update.user_donor eq 't'}checked="checked" {/if}/>
                    <label for="organdonationy" class="radiolab">Yes</label>

                    <input type="radio" name="user_donor" id="organdonationn" class="form-control" value="f" {if $user.maf.update.user_donor eq 'f'}checked="checked" {/if} />
                    <label for="organdonationn" class="radiolab">No</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="organotherinfo" class="visible-ie-only">Other Info:</label>
                    <textarea id="organotherinfo" name="user_donorFreeText" class="form-control">{$user.maf.update.user_donorFreeText}</textarea>
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
                    <select class="selectlist-medium" id="bloodgroup" name="blood_group">
                      <option value="">Select an option</option>
                      <option value="O+" {if $user.maf.update.blood_group eq 'O+'}checked="checked"{/if}>O+</option>
                      <option value="O-" {if $user.maf.update.blood_group eq 'O-'}checked="checked"{/if}>O-</option>
                      <option value="A+" {if $user.maf.update.blood_group eq 'A+'}checked="checked"{/if}>A+</option>
                      <option value="A-" {if $user.maf.update.blood_group eq 'A-'}checked="checked"{/if}>A-</option>
                      <option value="B+" {if $user.maf.update.blood_group eq 'B+'}checked="checked"{/if}>B+</option>
                      <option value="B-" {if $user.maf.update.blood_group eq 'B-'}checked="checked"{/if}>B-</option>
                      <option value="AB+" {if $user.maf.update.blood_group eq 'AB+'}checked="checked"{/if}>AB+</option>
                      <option value="AB-" {if $user.maf.update.blood_group eq 'AB-'}checked="checked"{/if}>AB-</option>
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
                {if $user.maf.update.conditions}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove a condition untick the relevant box.</p>
                  </div>
                  {foreach $user.maf.update.conditions as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="conditions[]" id="medicalcond{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1'}checked="checked" {/if}/>
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
                <br /> {if $user.maf.update.allergies}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove an allergy untick the relevant box.</p>
                  </div>
                  {foreach $user.maf.update.allergies as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="allergies[]" id="allergies{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1'}checked="checked" {/if}/>
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
                {if $user.maf.update.medications}
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <p class="text-left">To remove a medication untick the relevant box.</p>
                  </div>
                  {foreach $user.maf.update.medications as $k => $v}
                  <div class="col-sm-12 form-group">
                    <input type="checkbox" name="medications[]" id="medications{$k}" class="form-control" value="{$v.value}" {if $v.status eq '1'}checked="checked" {/if}/>
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
                  <div class="head-title">Other info</div>
                </div>
              </h3>
              <div class="acc-body">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="dva" class="visible-ie-only"> DVA gold card number: </label>
                    <input type="text" value="{$user.maf.update.attributes.10}" class="form-control" id="dva" name="dvagoldcard">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthfundname" class="visible-ie-only"> Health fund name: </label>
                    <input type="text" value="{$user.maf.update.attributes.12}" class="form-control" id="healthfundname" name="healthfundname">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthfundno" class="visible-ie-only"> Health fund number: </label>
                    <input type="text" value="{$user.maf.update.attributes.13}" class="form-control" id="healthfundno" name="healthfundnumber">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group">
                    <label for="healthid" class="visible-ie-only">
                      Individual health identifier: <img src="/images/question-mark.png" alt="Health identifier information" title="Health identifier information" data-toggle="tooltip" data-placement="top" />
                    </label>
                    <input type="text" value="{$user.maf.update.attributes.14}" class="form-control" id="healthid" name="ehealth">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <hr />
                    <p class="text-left">Only vital information that should be known in an emergency situation is to be recorded here. For example: additional emergency contact details, advance directive, specialist contact details and special needs.</p>

                    <p class="text-left">
                      <span class="bold">Note:</span> the first 95 characters (including spaces) are likely to print on your membership card, the remainder of the details will be held in your profile.
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="emergencyinfo" class="visible-ie-only"> Emergency information: </label>

                    <textarea maxlength="95" id="emergencyinfo" name="emergencyInfo" class="form-control">{$user.maf.update.emergencyInfo}</textarea>
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
                      {foreach $user.maf.update.medicalRecordFiles as $k => $v}
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
                      {foreach $user.maf.update.otherFiles as $k => $v}
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
                      <span class="bold">PLEASE NOTE:</span> To add or remove files, please contact Membership Services on <a href="tel:{$COMPANY.toll_free}" title="Click to call">{$COMPANY.toll_free}</a> (Mon-Fri, 8.3am-5.30pm CST) or email {obfuscate email=$COMPANY.email attr='title="Click to email us"'}
                    </p>

                    <p>
                      <a href="https://get.adobe.com/reader/" target="_blank" title="Click to download Adobe Acrobat Reader"><img src="/images/adobereader.png" alt="Adobe reader"> Get Adobe reader</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            {if !$user.maf.main.locked}
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1">
                <br /> <a href="/process/print-profile" target="_blank" title="Click to print profile" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my profile</a> <br />
                {$listing_content1}
              </div>
            </div>
            <br />
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
              <div class="col-sm-5 col-sm-offset-1">
                <a href="/my-account" title="Back to dashboard" class="btn btn-red">< NO CHANGES, GO BACK TO DASHBOARD</a>
              </div>
              <div class="col-sm-5">
                <a href="javascript:void(0)" class="btn btn-red process-cnt" id="submit-btn" title="Click to save changes" onclick="$('#update-profile-form').submit();">Save my changes</a>
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
<script type="text/javascript" src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script>
  $(function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $("#accordion").accordion({
      icons: icons,
      heightStyle: "content",
      collapsible: true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    
    {if $user.maf.main.locked}
    	lockFields();
    {/if}
    
    
    $("select").selectBoxIt();
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#update-profile-form').validate({
      submitHandler: function(form) {
        SubmitProfileForm($(form).attr('id'));
      }
    });
    
    $("#dob").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "-100:+0",
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
      required: true,
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify your mobile number"
      }
    });
    
    $('#emermobile').rules("add", {
      required: false,
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify your mobile number"
      }
    });
    
  });
  
  function SubmitProfileForm(FORM) {
    $('body').css('cursor', 'wait');
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
            window.location.href = '/update-my-profile#profile-locked';
          }
        }catch(err){
          console.log('TRY-CATCH error');
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + FORM).find('.error-alert').find('strong').html('Undefined error');
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
</script>
{/block}




