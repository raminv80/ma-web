{block name="head"}
<!-- <link href="/includes/css/jquery-ui.css" rel="stylesheet" media="screen"> -->
{/block} {block name=body}
<div id="headgrey">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>Welcome back, {$user.gname}</h2><div><a href="/process/user?logout=true" title="Click to log out">Log out</a></div>
			</div>
		</div>
	</div>
</div>
<div id="pagehead">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-left">
			{if $error}
			<div class="alert alert-danger fade in">
				{$error}
			</div>
			{/if}
	  		{if $notice}
			<div class="alert alert-success fade in">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
				{$notice}
			</div>
			{/if}
      </div>
      <div class="col-sm-12 text-center" id="listtoptext">
      	<h1>{$listing_title}</h1>
      </div>
    </div>
  </div>
</div>

<div id="updateprof">
  <div class="container">
      <div id="update-profile-wrapper">
          <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
		      <a href="#" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my profile</a>

              <form id="update-profile-form"  role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="" name="action" id="action" />
                <input type="hidden" value="" name="redirect" class="redirect" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />

				<div id="accordion">
					<h3>
					<div class="head-text">
						<div class="head-title">Personal information</div>
					</div>
					</h3>
					<div>
		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="title" class="visible-ie-only">
		                      Title<span>*</span>:
		                    </label>
		                    <select class="selectlist-medium" id="title" name="title" required>
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
		                    <label for="middlename" class="visible-ie-only">
		                      Middle Name:
		                    </label>
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
		                    <label for="homephone" class="visible-ie-only">
		                      Home phone:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.update.user_phone_home}" class="form-control" id="homephone" name="user_phone_home" pattern="[0-9]">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="workphone" class="visible-ie-only">
		                      Work phone:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.update.user_phone_work}" class="form-control" id="workphone" name="user_phone_work" pattern="[0-9]">
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
                        <input type="hidden" value="{$user.maf.update.user_dob}" name="user_dob" id="user_dob">
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
					<div>
		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="emername" class="visible-ie-only">
		                      Name:
		                    </label>
		                    <input type="text" value="{$user.maf.em.contact_name}" class="form-control" id="emername" name="contact_name">
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="emerrel" class="visible-ie-only">
		                      Relationship:
		                    </label>
		                    <input type="text" value="{$user.maf.em.contact_relationship}" class="form-control" id="emerrel" name="contact_relationship">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>
		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="emeraddress" class="visible-ie-only">
		                      Address:
		                    </label>
		                    <input type="text" value="{$user.maf.em.contact_address}" class="form-control" id="emeraddress" name="contact_address">
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="emersuburb" class="visible-ie-only">
		                      Suburb:
		                    </label>
		                    <input type="text" value="{$user.maf.em.contact_suburb}" class="form-control" id="emersuburb" name="contact_suburb">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="emerstate" class="visible-ie-only">
		                      State:
		                    </label>
		                    <select class="selectlist-medium" id="emerstate" name="contact_state_id">
		                      <option value="">Select an option</option>
		                      {foreach $options_state as $opt}
		                      <option value="{$opt.value}" {if $user.maf.em.contact_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
		                      {/foreach}
		                    </select>
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="emerpostcode" class="visible-ie-only">
		                      Postcode:
		                    </label>
		                    <input type="text" maxlength="4" value="{$user.maf.em.contact_postcode}" class="form-control" id="emerpostcode" name="contact_postcode" pattern="[0-9]" >
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="emerhomephone" class="visible-ie-only">
		                      Home phone:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.em.contact_phone_home}" class="form-control" id="emerhomephone" name="contact_phone_home" pattern="[0-9]">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                  <div class="col-sm-6 form-group">
		                    <label for="emerworkphone" class="visible-ie-only">
		                      Work phone:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.em.contact_phone_work}" class="form-control" id="emerworkphone" name="contact_phone_work" pattern="[0-9]">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="emermobile" class="visible-ie-only">
		                      Mobile:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.em.contact_mobile}" class="form-control" id="emermobile" name="contact_mobile" pattern="[0-9]" >
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>
					</div>

					<h3>
					<div class="head-text">
						<div class="head-title">Doctor Information</div>
					</div>
					</h3>
					<div>
		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="docname" class="visible-ie-only">
		                      Name:
		                    </label>
		                    <input type="text" value="{$user.maf.doc.doc_name}" class="form-control" id="docname" name="doc_name">
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="docmedcentre" class="visible-ie-only">
		                      Medical centre:
		                    </label>
		                    <input type="text" value="{$user.maf.doc.doc_medical_centre}" class="form-control" id="docmedcentre" name="doc_medical_centre">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>
		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="docaddress" class="visible-ie-only">
		                      Address:
		                    </label>
		                    <input type="text" value="{$user.maf.doc.doc_address}" class="form-control" id="docaddress" name="doc_address">
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="docsuburb" class="visible-ie-only">
		                      Suburb:
		                    </label>
		                    <input type="text" value="{$user.maf.doc.doc_suburb}" class="form-control" id="docsuburb" name="doc_suburb">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="docstate" class="visible-ie-only">
		                      State:
		                    </label>
		                    <select class="selectlist-medium" id="docstate" name="doc_state_id">
		                      <option value="">Select an option</option>
		                      {foreach $options_state as $opt}
		                      <option value="{$opt.value}" {if $user.maf.doc.doc_state_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
		                      {/foreach}
		                    </select>
		                    <div class="error-msg help-block"></div>
		                  </div>

		                  <div class="col-sm-6 form-group">
		                    <label for="docpostcode" class="visible-ie-only">
		                      Postcode:
		                    </label>
		                    <input type="text" maxlength="4" value="{$user.maf.doc.doc_postcode}" class="form-control" id="docpostcode" name="doc_postcode" pattern="[0-9]">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>

		                <div class="row">
		                  <div class="col-sm-6 form-group">
		                    <label for="docphone" class="visible-ie-only">
		                      Phone:
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.doc.doc_phone}" class="form-control" id="docphone" name="doc_phone" pattern="[0-9]" >
		                    <div class="error-msg help-block"></div>
		                  </div>
		                  <div class="col-sm-6 form-group">
		                    <label for="docfileno" class="visible-ie-only">
		                      File No. (if known):
		                    </label>
		                    <input type="text" maxlength="10" value="{$user.maf.doc.doc_file_no}" class="form-control" id="docfileno" name="doc_file_no" pattern="[0-9]">
		                    <div class="error-msg help-block"></div>
		                  </div>
		                </div>
					</div>

					<h3>
					<div class="head-text">
						<div class="head-title">Organ/Tissue donation</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-12 form-group">
							<p class="text-left">It is recommended that you officially register your donation decision on the <a href="#" target="_blank">Australian Organ Donor Register</a>, and discuss your decision with your family.</p>
							<input type="radio" name="user_donor" id="organdonationy" class="form-control" value="Yes" {if $user.maf.update.user_donor eq 't'}checked="checked"{/if}/>
		                    <label for="organdonationy" class="radiolab visible-ie-only">Yes</label>

							<input type="radio" name="user_donor" id="organdonationn" class="form-control" value="No" {if $user.maf.update.user_donor eq 'f'}checked="checked"{/if} />
		                    <label for="organdonationn" class="radiolab visible-ie-only">No</label>
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
					<div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-group">
								<label for="bloodgroup" class="visible-ie-only">Blood group:</label>
								<select class="selectlist-medium" id="bloodgroup" name="blood_group">
									<option value="">Select an option</option>
                  					<option value="O+" {if $user.maf.medic.blood_group eq 'O+'}checked="checked"{/if}>O+</option>
                                    <option value="O-" {if $user.maf.medic.blood_group eq 'O-'}checked="checked"{/if}>O-</option>
                                    <option value="A+" {if $user.maf.medic.blood_group eq 'A+'}checked="checked"{/if}>A+</option>
									<option value="A-" {if $user.maf.medic.blood_group eq 'A-'}checked="checked"{/if}>A-</option>
									<option value="B+" {if $user.maf.medic.blood_group eq 'B+'}checked="checked"{/if}>B+</option>
									<option value="B-" {if $user.maf.medic.blood_group eq 'B-'}checked="checked"{/if}>B-</option>
                                    <option value="AB+" {if $user.maf.medic.blood_group eq 'AB+'}checked="checked"{/if}>AB+</option>
                                    <option value="AB-" {if $user.maf.medic.blood_group eq 'AB-'}checked="checked"{/if}>AB-</option>
								</select>
							</div>
						</div>
					</div>


					<h3>
					<div class="head-text">
						<div class="head-title">Medical conditions</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<p class="text-left">To remove a condition untick the relevant box. Add new conditions in the text box below using a semicolon(;) to separate each condition.</p>
								<label class="visible-ie-only">Medical conditions:</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="medicalcond" id="medicalcond1" class="form-control" value="Ischaemic heart disease" />
								<label for="medicalcond1" class="radiolab visible-ie-only">Ischaemic heart disease</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="medicalcond" id="medicalcond2" class="form-control" value="Coronary artery bypass graft x 1" />
								<label for="medicalcond2" class="radiolab visible-ie-only">Coronary artery bypass graft x 1</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="medicalcond" id="medicalcond3" class="form-control" value="Type 2 diabetes mellitus" />
								<label for="medicalcond3" class="radiolab visible-ie-only">Type 2 diabetes mellitus</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="medicalcond" id="medicalcond4" class="form-control" value="Liver transplant" />
								<label for="medicalcond4" class="radiolab visible-ie-only">Liver transplant</label>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-sm-12 form-group">
								<textarea id="medicalcondadd" name="medicalcondadd" class="form-control"></textarea>
							</div>
						</div>
					</div>



					<h3>
					<div class="head-text">
						<div class="head-title">Allergies</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<p class="text-left">To remove an allergy untick the relevant box. Add new allergies in the text box below using a semicolon(;) to separate each allergy.</p>
								<p class="text-left">The Australasian Society of Clinical Immunology & Allergy recommends allergies should always be confirmed (authenticated) by your doctor.</p>
								<label class="visible-ie-only">Allergies:</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="allergies" id="allergies1" class="form-control" value="Nil known" />
								<label for="allergies1" class="radiolab visible-ie-only">Nil known</label>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<textarea id="allergiesadd" name="allergiesadd" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<h3>
					<div class="head-text">
						<div class="head-title">Medications</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<p class="text-left">To remove a medication untick the relevant box. Add new medications in the text box below using a semicolon(;) to separate each medication.</p>
								<label class="visible-ie-only">Medications (dosage not required):</label>
							</div>
							<div class="col-sm-6 form-group">
								<input type="checkbox" name="medications" id="medications1" class="form-control" value="Immunosuppression - do not cease" />
								<label for="medications1" class="radiolab visible-ie-only">Immunosuppression - do not cease</label>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<textarea id="medicationsadd" name="medicationsadd" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<h3>
					<div class="head-text">
						<div class="head-title">Other info</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-group">
		                    <label for="dva" class="visible-ie-only">
		                      DVA gold card number:
		                    </label>
		                    <input type="text" value="{$user.maf.dva}" class="form-control" id="dva" name="dva">
		                    <div class="error-msg help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-group">
		                    <label for="healthfundname" class="visible-ie-only">
		                      Health fund name:
		                    </label>
		                    <input type="text" value="{$user.maf.healthfundname}" class="form-control" id="healthfundname" name="healthfundname">
		                    <div class="error-msg help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-group">
		                    <label for="healthfundno" class="visible-ie-only">
		                      Health fund number:
		                    </label>
		                    <input type="text" value="{$user.maf.healthfundno}" class="form-control" id="healthfundno" name="healthfundno">
		                    <div class="error-msg help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-group">
		                    <label for="healthid" class="visible-ie-only">
		                      Individual health identifier:  <img src="/images/question-mark.png" alt="Health identifier information" title="Health identifier information" data-toggle="tooltip" data-placement="top" />
		                    </label>
		                    <input type="text" value="{$user.maf.healthid}" class="form-control" id="healthid" name="healthid">
		                    <div class="error-msg help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<hr />
								<p class="text-left">Only vital information that should be known in an emergency situation is to be recorded here. For example: additional emergency contact details, advance directive, specialist contact details and special needs.</p>

								<p class="text-left"><span class="bold">Note:</span> the first 95 characters (including spaces) are likely to print on your membership card, the remainder of the details will be held in your profile.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
			                    <label for="emergencyinfo" class="visible-ie-only">
								Emergency information:
								</label>

								<textarea id="emergencyinfo" name="emergencyinfo" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<h3>
					<div class="head-text">
						<div class="head-title">Documents</div>
					</div>
					</h3>
					<div>
						<div class="row">
							<div class="col-sm-12 form-group text-left">
								<p>Relevant documents such as allergy action plan, asthma action plan, advance care directive, passports, implant device information, specialist letter (e.g. difficult intubation) or a medical summary from your treating doctor can be added to your profile. Should you wish to add documents of these types to your member profile, please scan and email to enquiry@medicalert.org.au with your name and membership number.</p>

								<p>Medical record files</p>

								<p>Other Files</p>

								<p><span class="bold">PLEASE NOTE:</span> To add or remove files, please contact Membership Services on <a href="tel:1800 88 22 22">1800 88 22 22</a> (Mon-Fri, 9am-5pm CST) or email <a href="mailto:enquiry@medicalert.org.au">enquiry@medicalert.org.au</a></p>

								<p><a href="#"><img src="/images/adobereader.png" alt="Adobe reader">  Get Adobe reader</a></p>
							</div>
						</div>
					</div>
				</div>

              <div class="clearfix"></div>
              <div class="row">
	              <div class="col-sm-10 col-sm-offset-1">
		              <br />
		              <a href="#" class="btn btn-grey"><img src="/images/print.png" alt="Print" /> Print my profile</a>
		              <br />
					  <p>If you continue and update your record, you will be locked out of your member profile until a Membership Services representative has reviewed and updated your profile. Please make sure you have made changes on all relevant tabs before continuing. This is so that our quality control team can review your updates. You will receive an email once your profile has been confirmed.</p>
	              </div>
              </div>
			  <br />

              <div class="row">
	              <div class="col-sm-5 col-sm-offset-1">
		              <a href="#" class="btn btn-red">< NO CHANGES, GO BACK TO MEMBER AREA</a>
	              </div>
	              <div class="col-sm-5">
		              <a href="#" class="btn btn-red process-cnt" id="payment-btn" onclick="$('#update-profile-form').submit();">Save my changes</a>
	              </div>
              </div>


              </form>

            </div>
          </div>
    </div>
  </div>
</div>


{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail} {literal}
<script type="text/javascript" src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script>
  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true
    });
  } );
</script>
<script type="text/javascript">

  $(document).ready(function() {
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
    
    $('#dob').rules("add", {
      required: true
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
    $.ajax({
      type: "POST",
      url: "/process/user",
      cache: false,
      data: datastring,
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.success && obj.url){
            window.location.href = obj.url;
          }else if(obj.error){
            $('#' + FORM).find('.error-alert').find('strong').html(obj.error);
            $('#' + FORM).find('.error-alert').fadeIn('slow');
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
  
  function convert_to_mysql_date_format(str){
   	var dateArr = str.split("/");
   	return dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]; 
   }
   	
   function setDateValue(id,date){
   	$("#"+id).val( convert_to_mysql_date_format(date) );
   }
</script>
{/literal} {/block}




