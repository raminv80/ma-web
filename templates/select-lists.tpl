{* 	Country selector block. This is templated so that all
	country selectors will contain the same set of country names *}
{block name=countryselect}
		<option value="" {if $country eq ''}selected="selected"{/if}></option>
		<option value="Afghanistan" {if $country eq 'Afghanistan'}selected="selected"{/if}>Afghanistan</option>
		<option value="Aland Islands" {if $country eq 'Aland Islands'}selected="selected"{/if}>Aland Islands</option>
		<option value="Albania" {if $country eq 'Albania'}selected="selected"{/if}>Albania</option>
		<option value="Algeria" {if $country eq 'Algeria'}selected="selected"{/if}>Algeria</option>
		<option value="American Samoa" {if $country eq 'American Samoa'}selected="selected"{/if}>American Samoa</option>
		<option value="Andorra" {if $country eq 'Andorra'}selected="selected"{/if}>Andorra</option>
		<option value="Angola" {if $country eq 'Angola'}selected="selected"{/if}>Angola</option>
		<option value="Anguilla" {if $country eq 'Anguilla'}selected="selected"{/if}>Anguilla</option>
		<option value="Antarctica" {if $country eq 'Antarctica'}selected="selected"{/if}>Antarctica</option>
		<option value="Antigua and Barbuda" {if $country eq 'Antigua and Barbuda'}selected="selected"{/if}>Antigua and Barbuda</option>
		<option value="Argentina" {if $country eq 'Argentina'}selected="selected"{/if}>Argentina</option>
		<option value="Armenia" {if $country eq 'Armenia'}selected="selected"{/if}>Armenia</option>
		<option value="Aruba" {if $country eq 'Aruba'}selected="selected"{/if}>Aruba</option>
		<option value="Australia" {if $country eq 'Australia'}selected="selected"{/if}>Australia</option>
		<option value="Austria" {if $country eq 'Austria'}selected="selected"{/if}>Austria</option>
		<option value="Azerbaijan" {if $country eq 'Azerbaijan'}selected="selected"{/if}>Azerbaijan</option>
		<option value="Bahamas" {if $country eq 'Bahamas'}selected="selected"{/if}>Bahamas</option>
		<option value="Bahrain" {if $country eq 'Bahrain'}selected="selected"{/if}>Bahrain</option>
		<option value="Bangladesh" {if $country eq 'Bangladesh'}selected="selected"{/if}>Bangladesh</option>
		<option value="Barbados" {if $country eq 'Barbados'}selected="selected"{/if}>Barbados</option>
		<option value="Belarus" {if $country eq 'Belarus'}selected="selected"{/if}>Belarus</option>
		<option value="Belgium" {if $country eq 'Belgium'}selected="selected"{/if}>Belgium</option>
		<option value="Belize" {if $country eq 'Belize'}selected="selected"{/if}>Belize</option>
		<option value="Benin" {if $country eq 'Benin'}selected="selected"{/if}>Benin</option>
		<option value="Bermuda" {if $country eq 'Bermuda'}selected="selected"{/if}>Bermuda</option>
		<option value="Bhutan" {if $country eq 'Bhutan'}selected="selected"{/if}>Bhutan</option>
		<option value="Bolivia" {if $country eq 'Bolivia'}selected="selected"{/if}>Bolivia</option>
		<option value="Bosnia and Herzegovina" {if $country eq 'Bosnia and Herzegovina'}selected="selected"{/if}>Bosnia and Herzegovina</option>
		<option value="Botswana" {if $country eq 'Botswana'}selected="selected"{/if}>Botswana</option>
		<option value="Bouvet Island" {if $country eq 'Bouvet Island'}selected="selected"{/if}>Bouvet Island</option>
		<option value="Brazil" {if $country eq 'Brazil'}selected="selected"{/if}>Brazil</option>
		<option value="British Indian Ocean Territory" {if $country eq 'British Indian Ocean Territory'}selected="selected"{/if}>British Indian Ocean Territory</option>
		<option value="Brunei Darussalam" {if $country eq 'Brunei Darussalam'}selected="selected"{/if}>Brunei Darussalam</option>
		<option value="Bulgaria" {if $country eq 'Bulgaria'}selected="selected"{/if}>Bulgaria</option>
		<option value="Burkina Faso" {if $country eq 'Burkina Faso'}selected="selected"{/if}>Burkina Faso</option>
		<option value="Burundi" {if $country eq 'Burundi'}selected="selected"{/if}>Burundi</option>
		<option value="Cambodia" {if $country eq 'Cambodia'}selected="selected"{/if}>Cambodia</option>
		<option value="Cameroon" {if $country eq 'Cameroon'}selected="selected"{/if}>Cameroon</option>
		<option value="Canada" {if $country eq 'Canada'}selected="selected"{/if}>Canada</option>
		<option value="Cape Verde" {if $country eq 'Cape Verde'}selected="selected"{/if}>Cape Verde</option>
		<option value="Cayman Islands" {if $country eq 'Cayman Islands'}selected="selected"{/if}>Cayman Islands</option>
		<option value="Central African Republic" {if $country eq 'Central African Republic'}selected="selected"{/if}>Central African Republic</option>
		<option value="Chad" {if $country eq 'Chad'}selected="selected"{/if}>Chad</option>
		<option value="Chile" {if $country eq 'Chile'}selected="selected"{/if}>Chile</option>
		<option value="China" {if $country eq 'China'}selected="selected"{/if}>China</option>
		<option value="Christmas Island" {if $country eq 'Christmas Island'}selected="selected"{/if}>Christmas Island</option>
		<option value="Cocos (Keeling) Islands" {if $country eq 'Cocos (Keeling) Islands'}selected="selected"{/if}>Cocos (Keeling) Islands</option>
		<option value="Colombia" {if $country eq 'Colombia'}selected="selected"{/if}>Colombia</option>
		<option value="Comoros" {if $country eq 'Comoros'}selected="selected"{/if}>Comoros</option>
		<option value="Congo" {if $country eq 'Congo'}selected="selected"{/if}>Congo</option>
		<option value="Congo, The Democratic Republic of The" {if $country eq 'Congo, The Democratic Republic of The'}selected="selected"{/if}>Congo, The Democratic Republic of The</option>
		<option value="Cook Islands" {if $country eq 'Cook Islands'}selected="selected"{/if}>Cook Islands</option>
		<option value="Costa Rica" {if $country eq 'Costa Rica'}selected="selected"{/if}>Costa Rica</option>
		<option value="Cote D\'ivoire" {if $country eq 'Cote D\'ivoire'}selected="selected"{/if}>Cote D'ivoire</option>
		<option value="Croatia" {if $country eq 'Croatia'}selected="selected"{/if}>Croatia</option>
		<option value="Cuba" {if $country eq 'Cuba'}selected="selected"{/if}>Cuba</option>
		<option value="Cyprus" {if $country eq 'Cyprus'}selected="selected"{/if}>Cyprus</option>
		<option value="Czech Republic" {if $country eq 'Czech Republic'}selected="selected"{/if}>Czech Republic</option>
		<option value="Denmark" {if $country eq 'Denmark'}selected="selected"{/if}>Denmark</option>
		<option value="Djibouti" {if $country eq 'Djibouti'}selected="selected"{/if}>Djibouti</option>
		<option value="Dominica" {if $country eq 'Dominica'}selected="selected"{/if}>Dominica</option>
		<option value="Dominican Republic" {if $country eq 'Dominican Republic'}selected="selected"{/if}>Dominican Republic</option>
		<option value="Ecuador" {if $country eq 'Ecuador'}selected="selected"{/if}>Ecuador</option>
		<option value="Egypt" {if $country eq 'Egypt'}selected="selected"{/if}>Egypt</option>
		<option value="El Salvador" {if $country eq 'El Salvador'}selected="selected"{/if}>El Salvador</option>
		<option value="Equatorial Guinea" {if $country eq 'Equatorial Guinea'}selected="selected"{/if}>Equatorial Guinea</option>
		<option value="Eritrea" {if $country eq 'Eritrea'}selected="selected"{/if}>Eritrea</option>
		<option value="Estonia" {if $country eq 'Estonia'}selected="selected"{/if}>Estonia</option>
		<option value="Ethiopia" {if $country eq 'Ethiopia'}selected="selected"{/if}>Ethiopia</option>
		<option value="Falkland Islands (Malvinas)" {if $country eq 'Falkland Islands (Malvinas)'}selected="selected"{/if}>Falkland Islands (Malvinas)</option>
		<option value="Faroe Islands" {if $country eq 'Faroe Islands'}selected="selected"{/if}>Faroe Islands</option>
		<option value="Fiji" {if $country eq 'Fiji'}selected="selected"{/if}>Fiji</option>
		<option value="Finland" {if $country eq 'Finland'}selected="selected"{/if}>Finland</option>
		<option value="France" {if $country eq 'France'}selected="selected"{/if}>France</option>
		<option value="French Guiana" {if $country eq 'French Guiana'}selected="selected"{/if}>French Guiana</option>
		<option value="French Polynesia" {if $country eq 'French Polynesia'}selected="selected"{/if}>French Polynesia</option>
		<option value="French Southern Territories" {if $country eq 'French Southern Territories'}selected="selected"{/if}>French Southern Territories</option>
		<option value="Gabon" {if $country eq 'Gabon'}selected="selected"{/if}>Gabon</option>
		<option value="Gambia" {if $country eq 'Gambia'}selected="selected"{/if}>Gambia</option>
		<option value="Georgia" {if $country eq 'Georgia'}selected="selected"{/if}>Georgia</option>
		<option value="Germany" {if $country eq 'Germany'}selected="selected"{/if}>Germany</option>
		<option value="Ghana" {if $country eq 'Ghana'}selected="selected"{/if}>Ghana</option>
		<option value="Gibraltar" {if $country eq 'Gibraltar'}selected="selected"{/if}>Gibraltar</option>
		<option value="Greece" {if $country eq 'Greece'}selected="selected"{/if}>Greece</option>
		<option value="Greenland" {if $country eq 'Greenland'}selected="selected"{/if}>Greenland</option>
		<option value="Grenada" {if $country eq 'Grenada'}selected="selected"{/if}>Grenada</option>
		<option value="Guadeloupe" {if $country eq 'Guadeloupe'}selected="selected"{/if}>Guadeloupe</option>
		<option value="Guam" {if $country eq 'Guam'}selected="selected"{/if}>Guam</option>
		<option value="Guatemala" {if $country eq 'Guatemala'}selected="selected"{/if}>Guatemala</option>
		<option value="Guernsey" {if $country eq 'Guernsey'}selected="selected"{/if}>Guernsey</option>
		<option value="Guinea" {if $country eq 'Guinea'}selected="selected"{/if}>Guinea</option>
		<option value="Guinea-bissau" {if $country eq 'Guinea-bissau'}selected="selected"{/if}>Guinea-bissau</option>
		<option value="Guyana" {if $country eq 'Guyana'}selected="selected"{/if}>Guyana</option>
		<option value="Haiti" {if $country eq 'Haiti'}selected="selected"{/if}>Haiti</option>
		<option value="Heard Island and Mcdonald Islands" {if $country eq 'Heard Island and Mcdonald Islands'}selected="selected"{/if}>Heard Island and Mcdonald Islands</option>
		<option value="Holy See (Vatican City State)" {if $country eq 'Holy See (Vatican City State)'}selected="selected"{/if}>Holy See (Vatican City State)</option>
		<option value="Honduras" {if $country eq 'Honduras'}selected="selected"{/if}>Honduras</option>
		<option value="Hong Kong" {if $country eq 'Hong Kong'}selected="selected"{/if}>Hong Kong</option>
		<option value="Hungary" {if $country eq 'Hungary'}selected="selected"{/if}>Hungary</option>
		<option value="Iceland" {if $country eq 'Iceland'}selected="selected"{/if}>Iceland</option>
		<option value="India" {if $country eq 'India'}selected="selected"{/if}>India</option>
		<option value="Indonesia" {if $country eq 'Indonesia'}selected="selected"{/if}>Indonesia</option>
		<option value="Iran, Islamic Republic of" {if $country eq 'Iran, Islamic Republic of'}selected="selected"{/if}>Iran, Islamic Republic of</option>
		<option value="Iraq" {if $country eq 'Iraq'}selected="selected"{/if}>Iraq</option>
		<option value="Ireland" {if $country eq 'Ireland'}selected="selected"{/if}>Ireland</option>
		<option value="Isle of Man" {if $country eq 'Isle of Man'}selected="selected"{/if}>Isle of Man</option>
		<option value="Israel" {if $country eq 'Israel'}selected="selected"{/if}>Israel</option>
		<option value="Italy" {if $country eq 'Italy'}selected="selected"{/if}>Italy</option>
		<option value="Jamaica" {if $country eq 'Jamaica'}selected="selected"{/if}>Jamaica</option>
		<option value="Japan" {if $country eq 'Japan'}selected="selected"{/if}>Japan</option>
		<option value="Jersey" {if $country eq 'Jersey'}selected="selected"{/if}>Jersey</option>
		<option value="Jordan" {if $country eq 'Jordan'}selected="selected"{/if}>Jordan</option>
		<option value="Kazakhstan" {if $country eq 'Kazakhstan'}selected="selected"{/if}>Kazakhstan</option>
		<option value="Kenya" {if $country eq 'Kenya'}selected="selected"{/if}>Kenya</option>
		<option value="Kiribati" {if $country eq 'Kiribati'}selected="selected"{/if}>Kiribati</option>
		<option value="Korea, Democratic People\'s Republic of" {if $country eq 'Korea, Democratic People\'s Republic of'}selected="selected"{/if}>Korea, Democratic People's Republic of</option>
		<option value="Korea, Republic of" {if $country eq 'Korea, Republic of'}selected="selected"{/if}>Korea, Republic of</option>
		<option value="Kuwait" {if $country eq 'Kuwait'}selected="selected"{/if}>Kuwait</option>
		<option value="Kyrgyzstan" {if $country eq 'Kyrgyzstan'}selected="selected"{/if}>Kyrgyzstan</option>
		<option value="Lao People\'s Democratic Republic" {if $country eq 'Lao People\'s Democratic Republic'}selected="selected"{/if}>Lao People's Democratic Republic</option>
		<option value="Latvia" {if $country eq 'Latvia'}selected="selected"{/if}>Latvia</option>
		<option value="Lebanon" {if $country eq 'Lebanon'}selected="selected"{/if}>Lebanon</option>
		<option value="Lesotho" {if $country eq 'Lesotho'}selected="selected"{/if}>Lesotho</option>
		<option value="Liberia" {if $country eq 'Liberia'}selected="selected"{/if}>Liberia</option>
		<option value="Libyan Arab Jamahiriya" {if $country eq 'Libyan Arab Jamahiriya'}selected="selected"{/if}>Libyan Arab Jamahiriya</option>
		<option value="Liechtenstein" {if $country eq 'Liechtenstein'}selected="selected"{/if}>Liechtenstein</option>
		<option value="Lithuania" {if $country eq 'Lithuania'}selected="selected"{/if}>Lithuania</option>
		<option value="Luxembourg" {if $country eq 'Luxembourg'}selected="selected"{/if}>Luxembourg</option>
		<option value="Macao" {if $country eq 'Macao'}selected="selected"{/if}>Macao</option>
		<option value="Macedonia, The Former Yugoslav Republic of" {if $country eq 'Macedonia, The Former Yugoslav Republic of'}selected="selected"{/if}>Macedonia, The Former Yugoslav Republic of</option>
		<option value="Madagascar" {if $country eq 'Madagascar'}selected="selected"{/if}>Madagascar</option>
		<option value="Malawi" {if $country eq 'Malawi'}selected="selected"{/if}>Malawi</option>
		<option value="Malaysia" {if $country eq 'Malaysia'}selected="selected"{/if}>Malaysia</option>
		<option value="Maldives" {if $country eq 'Maldives'}selected="selected"{/if}>Maldives</option>
		<option value="Mali" {if $country eq 'Mali'}selected="selected"{/if}>Mali</option>
		<option value="Malta" {if $country eq 'Malta'}selected="selected"{/if}>Malta</option>
		<option value="Marshall Islands" {if $country eq 'Marshall Islands'}selected="selected"{/if}>Marshall Islands</option>
		<option value="Martinique" {if $country eq 'Martinique'}selected="selected"{/if}>Martinique</option>
		<option value="Mauritania" {if $country eq 'Mauritania'}selected="selected"{/if}>Mauritania</option>
		<option value="Mauritius" {if $country eq 'Mauritius'}selected="selected"{/if}>Mauritius</option>
		<option value="Mayotte" {if $country eq 'Mayotte'}selected="selected"{/if}>Mayotte</option>
		<option value="Mexico" {if $country eq 'Mexico'}selected="selected"{/if}>Mexico</option>
		<option value="Micronesia, Federated States of" {if $country eq 'Micronesia, Federated States of'}selected="selected"{/if}>Micronesia, Federated States of</option>
		<option value="Moldova, Republic of" {if $country eq 'Moldova, Republic of'}selected="selected"{/if}>Moldova, Republic of</option>
		<option value="Monaco" {if $country eq 'Monaco'}selected="selected"{/if}>Monaco</option>
		<option value="Mongolia" {if $country eq 'Mongolia'}selected="selected"{/if}>Mongolia</option>
		<option value="Montenegro" {if $country eq 'Montenegro'}selected="selected"{/if}>Montenegro</option>
		<option value="Montserrat" {if $country eq 'Montserrat'}selected="selected"{/if}>Montserrat</option>
		<option value="Morocco" {if $country eq 'Morocco'}selected="selected"{/if}>Morocco</option>
		<option value="Mozambique" {if $country eq 'Mozambique'}selected="selected"{/if}>Mozambique</option>
		<option value="Myanmar" {if $country eq 'Myanmar'}selected="selected"{/if}>Myanmar</option>
		<option value="Namibia" {if $country eq 'Namibia'}selected="selected"{/if}>Namibia</option>
		<option value="Nauru" {if $country eq 'Nauru'}selected="selected"{/if}>Nauru</option>
		<option value="Nepal" {if $country eq 'Nepal'}selected="selected"{/if}>Nepal</option>
		<option value="Netherlands" {if $country eq 'Netherlands'}selected="selected"{/if}>Netherlands</option>
		<option value="Netherlands Antilles" {if $country eq 'Netherlands Antilles'}selected="selected"{/if}>Netherlands Antilles</option>
		<option value="New Caledonia" {if $country eq 'New Caledonia'}selected="selected"{/if}>New Caledonia</option>
		<option value="New Zealand" {if $country eq 'New Zealand'}selected="selected"{/if}>New Zealand</option>
		<option value="Nicaragua" {if $country eq 'Nicaragua'}selected="selected"{/if}>Nicaragua</option>
		<option value="Niger" {if $country eq 'Niger'}selected="selected"{/if}>Niger</option>
		<option value="Nigeria" {if $country eq 'Nigeria'}selected="selected"{/if}>Nigeria</option>
		<option value="Niue" {if $country eq 'Niue'}selected="selected"{/if}>Niue</option>
		<option value="Norfolk Island" {if $country eq 'Norfolk Island'}selected="selected"{/if}>Norfolk Island</option>
		<option value="Northern Mariana Islands" {if $country eq 'Northern Mariana Islands'}selected="selected"{/if}>Northern Mariana Islands</option>
		<option value="Norway" {if $country eq 'Norway'}selected="selected"{/if}>Norway</option>
		<option value="Oman" {if $country eq 'Oman'}selected="selected"{/if}>Oman</option>
		<option value="Pakistan" {if $country eq 'Pakistan'}selected="selected"{/if}>Pakistan</option>
		<option value="Palau" {if $country eq 'Palau'}selected="selected"{/if}>Palau</option>
		<option value="Palestinian Territory, Occupied" {if $country eq 'Palestinian Territory, Occupied'}selected="selected"{/if}>Palestinian Territory, Occupied</option>
		<option value="Panama" {if $country eq 'Panama'}selected="selected"{/if}>Panama</option>
		<option value="Papua New Guinea" {if $country eq 'Papua New Guinea'}selected="selected"{/if}>Papua New Guinea</option>
		<option value="Paraguay" {if $country eq 'Paraguay'}selected="selected"{/if}>Paraguay</option>
		<option value="Peru" {if $country eq 'Peru'}selected="selected"{/if}>Peru</option>
		<option value="Philippines" {if $country eq 'Philippines'}selected="selected"{/if}>Philippines</option>
		<option value="Pitcairn" {if $country eq 'Pitcairn'}selected="selected"{/if}>Pitcairn</option>
		<option value="Poland" {if $country eq 'Poland'}selected="selected"{/if}>Poland</option>
		<option value="Portugal" {if $country eq 'Portugal'}selected="selected"{/if}>Portugal</option>
		<option value="Puerto Rico" {if $country eq 'Puerto Rico'}selected="selected"{/if}>Puerto Rico</option>
		<option value="Qatar" {if $country eq 'Qatar'}selected="selected"{/if}>Qatar</option>
		<option value="Reunion" {if $country eq 'Reunion'}selected="selected"{/if}>Reunion</option>
		<option value="Romania" {if $country eq 'Romania'}selected="selected"{/if}>Romania</option>
		<option value="Russian Federation" {if $country eq 'Russian Federation'}selected="selected"{/if}>Russian Federation</option>
		<option value="Rwanda" {if $country eq 'Rwanda'}selected="selected"{/if}>Rwanda</option>
		<option value="Saint Helena" {if $country eq 'Saint Helena'}selected="selected"{/if}>Saint Helena</option>
		<option value="Saint Kitts and Nevis" {if $country eq 'Saint Kitts and Nevis'}selected="selected"{/if}>Saint Kitts and Nevis</option>
		<option value="Saint Lucia" {if $country eq 'Saint Lucia'}selected="selected"{/if}>Saint Lucia</option>
		<option value="Saint Pierre and Miquelon" {if $country eq 'Saint Pierre and Miquelon'}selected="selected"{/if}>Saint Pierre and Miquelon</option>
		<option value="Saint Vincent and The Grenadines" {if $country eq 'Saint Vincent and The Grenadines'}selected="selected"{/if}>Saint Vincent and The Grenadines</option>
		<option value="Samoa" {if $country eq 'Samoa'}selected="selected"{/if}>Samoa</option>
		<option value="San Marino" {if $country eq 'San Marino'}selected="selected"{/if}>San Marino</option>
		<option value="Sao Tome and Principe" {if $country eq 'Sao Tome and Principe'}selected="selected"{/if}>Sao Tome and Principe</option>
		<option value="Saudi Arabia" {if $country eq 'Saudi Arabia'}selected="selected"{/if}>Saudi Arabia</option>
		<option value="Senegal" {if $country eq 'Senegal'}selected="selected"{/if}>Senegal</option>
		<option value="Serbia" {if $country eq 'Serbia'}selected="selected"{/if}>Serbia</option>
		<option value="Seychelles" {if $country eq 'Seychelles'}selected="selected"{/if}>Seychelles</option>
		<option value="Sierra Leone" {if $country eq 'Sierra Leone'}selected="selected"{/if}>Sierra Leone</option>
		<option value="Singapore" {if $country eq 'Singapore'}selected="selected"{/if}>Singapore</option>
		<option value="Slovakia" {if $country eq 'Slovakia'}selected="selected"{/if}>Slovakia</option>
		<option value="Slovenia" {if $country eq 'Slovenia'}selected="selected"{/if}>Slovenia</option>
		<option value="Solomon Islands" {if $country eq 'Solomon Islands'}selected="selected"{/if}>Solomon Islands</option>
		<option value="Somalia" {if $country eq 'Somalia'}selected="selected"{/if}>Somalia</option>
		<option value="South Africa" {if $country eq 'South Africa'}selected="selected"{/if}>South Africa</option>
		<option value="South Georgia and The South Sandwich Islands" {if $country eq 'South Georgia and The South Sandwich Islands'}selected="selected"{/if}>South Georgia and The South Sandwich Islands</option>
		<option value="Spain" {if $country eq 'Spain'}selected="selected"{/if}>Spain</option>
		<option value="Sri Lanka" {if $country eq 'Sri Lanka'}selected="selected"{/if}>Sri Lanka</option>
		<option value="Sudan" {if $country eq 'Sudan'}selected="selected"{/if}>Sudan</option>
		<option value="Suriname" {if $country eq 'Suriname'}selected="selected"{/if}>Suriname</option>
		<option value="Svalbard and Jan Mayen" {if $country eq 'Svalbard and Jan Mayen'}selected="selected"{/if}>Svalbard and Jan Mayen</option>
		<option value="Swaziland" {if $country eq 'Swaziland'}selected="selected"{/if}>Swaziland</option>
		<option value="Sweden" {if $country eq 'Sweden'}selected="selected"{/if}>Sweden</option>
		<option value="Switzerland" {if $country eq 'Switzerland'}selected="selected"{/if}>Switzerland</option>
		<option value="Syrian Arab Republic" {if $country eq 'Syrian Arab Republic'}selected="selected"{/if}>Syrian Arab Republic</option>
		<option value="Taiwan, Province of China" {if $country eq 'Taiwan, Province of China'}selected="selected"{/if}>Taiwan, Province of China</option>
		<option value="Tajikistan" {if $country eq 'Tajikistans'}selected="selected"{/if}>Tajikistan</option>
		<option value="Tanzania, United Republic of" {if $country eq 'Tanzania, United Republic of'}selected="selected"{/if}>Tanzania, United Republic of</option>
		<option value="Thailand" {if $country eq 'Thailand'}selected="selected"{/if}>Thailand</option>
		<option value="Timor-leste" {if $country eq 'Timor-leste'}selected="selected"{/if}>Timor-leste</option>
		<option value="Togo" {if $country eq 'Togo'}selected="selected"{/if}>Togo</option>
		<option value="Tokelau" {if $country eq 'Tokelau'}selected="selected"{/if}>Tokelau</option>
		<option value="Tonga" {if $country eq 'Tonga'}selected="selected"{/if}>Tonga</option>
		<option value="Trinidad and Tobago" {if $country eq 'Trinidad and Tobago'}selected="selected"{/if}>Trinidad and Tobago</option>
		<option value="Tunisia" {if $country eq 'Tunisia'}selected="selected"{/if}>Tunisia</option>
		<option value="Turkey" {if $country eq 'Turkey'}selected="selected"{/if}>Turkey</option>
		<option value="Turkmenistan" {if $country eq 'Turkmenistan'}selected="selected"{/if}>Turkmenistan</option>
		<option value="Turks and Caicos Islands" {if $country eq 'Turks and Caicos Islands'}selected="selected"{/if}>Turks and Caicos Islands</option>
		<option value="Tuvalu" {if $country eq 'Tuvalu'}selected="selected"{/if}>Tuvalu</option>
		<option value="Uganda" {if $country eq 'Uganda'}selected="selected"{/if}>Uganda</option>
		<option value="Ukraine" {if $country eq 'Ukraine'}selected="selected"{/if}>Ukraine</option>
		<option value="United Arab Emirates" {if $country eq 'United Arab Emirates'}selected="selected"{/if}>United Arab Emirates</option>
		<option value="United Kingdom" {if $country eq 'United Kingdom'}selected="selected"{/if}>United Kingdom</option>
		<option value="United States" {if $country eq 'United States'}selected="selected"{/if}>United States</option>
		<option value="United States Minor Outlying Islands" {if $country eq 'United States Minor Outlying Islands'}selected="selected"{/if}>United States Minor Outlying Islands</option>
		<option value="Uruguay" {if $country eq 'Uruguay'}selected="selected"{/if}>Uruguay</option>
		<option value="Uzbekistan" {if $country eq 'Uzbekistan'}selected="selected"{/if}>Uzbekistan</option>
		<option value="Vanuatu" {if $country eq 'Vanuatu'}selected="selected"{/if}>Vanuatu</option>
		<option value="Venezuela" {if $country eq 'Venezuela'}selected="selected"{/if}>Venezuela</option>
		<option value="Viet Nam" {if $country eq 'Viet Nam'}selected="selected"{/if}>Viet Nam</option>
		<option value="Virgin Islands, British" {if $country eq 'Virgin Islands, British'}selected="selected"{/if}>Virgin Islands, British</option>
		<option value="Virgin Islands, U.S." {if $country eq 'Virgin Islands, U.S.'}selected="selected"{/if}>Virgin Islands, U.S.</option>
		<option value="Wallis and Futuna" {if $country eq 'Wallis and Futuna'}selected="selected"{/if}>Wallis and Futuna</option>
		<option value="Western Sahara" {if $country eq 'Western Sahara'}selected="selected"{/if}>Western Sahara</option>
		<option value="Yemen" {if $country eq 'Yemen'}selected="selected"{/if}>Yemen</option>
		<option value="Zambia" {if $country eq 'Zambia'}selected="selected"{/if}>Zambia</option>
		<option value="Zimbabwe" {if $country eq 'Zimbabwe'}selected="selected"{/if}>Zimbabwe</option>
{/block}

{*	Month Selector is a the templated contents of a drop down
	selector for month names. It maps name to a 01-12 value *}
{block name=monthselect}
		<option value="" {if $month eq ''}selected="selected"{/if}></option>
        <option value="01" {if $month eq '01'}selected="selected"{/if}>January</option>
        <option value="02" {if $month eq '02'}selected="selected"{/if}>Febuary</option>
        <option value="03" {if $month eq '03'}selected="selected"{/if}>March</option>
        <option value="04" {if $month eq '04'}selected="selected"{/if}>April</option>
        <option value="05" {if $month eq '05'}selected="selected"{/if}>May</option>
        <option value="06" {if $month eq '06'}selected="selected"{/if}>June</option>
        <option value="07" {if $month eq '07'}selected="selected"{/if}>July</option>
        <option value="08" {if $month eq '08'}selected="selected"{/if}>August</option>
        <option value="09" {if $month eq '09'}selected="selected"{/if}>September</option>
        <option value="10" {if $month eq '10'}selected="selected"{/if}>October</option>
        <option value="11" {if $month eq '11'}selected="selected"{/if}>November</option>
        <option value="12" {if $month eq '12'}selected="selected"{/if}>December</option>
{/block}
