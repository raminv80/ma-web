<?php
function CountrySelect($id='', $country='' , $style = ''){
	if($id==''){
		$id="country";
	}
	$buf='
	<select style="'.$style.'" name="'.$id.'" id="'.$id.'">
                            <option value="" '.($country==''? 'selected="selected"':'').'></option>
                            <option value="Afghanistan" '.($country=='Afghanistan'? 'selected="selected"':'').'>Afghanistan</option>
                            <option value="Aland Islands" '.($country=='Aland Islands'? 'selected="selected"':'').'>Aland Islands</option>
                            <option value="Albania" '.($country=='Albania'? 'selected="selected"':'').'>Albania</option>
                            <option value="Algeria" '.($country=='Algeria'? 'selected="selected"':'').'>Algeria</option>
                            <option value="American Samoa" '.($country=='American Samoa'? 'selected="selected"':'').'>American Samoa</option>
                            <option value="Andorra" '.($country=='Andorra'? 'selected="selected"':'').'>Andorra</option>
                            <option value="Angola" '.($country=='Angola'? 'selected="selected"':'').'>Angola</option>
                            <option value="Anguilla" '.($country=='Anguilla'? 'selected="selected"':'').'>Anguilla</option>
                            <option value="Antarctica" '.($country=='Antarctica'? 'selected="selected"':'').'>Antarctica</option>
                            <option value="Antigua and Barbuda" '.($country=='Antigua and Barbuda'? 'selected="selected"':'').'>Antigua and Barbuda</option>
                            <option value="Argentina" '.($country=='Argentina'? 'selected="selected"':'').'>Argentina</option>
                            <option value="Armenia" '.($country=='Armenia'? 'selected="selected"':'').'>Armenia</option>
                            <option value="Aruba" '.($country=='Aruba'? 'selected="selected"':'').'>Aruba</option>
                            <option value="Australia" '.($country=='Australia'? 'selected="selected"':'').'>Australia</option>
                            <option value="Austria" '.($country=='Austria'? 'selected="selected"':'').'>Austria</option>
                            <option value="Azerbaijan" '.($country=='Azerbaijan'? 'selected="selected"':'').'>Azerbaijan</option>
                            <option value="Bahamas" '.($country=='Bahamas'? 'selected="selected"':'').'>Bahamas</option>
                            <option value="Bahrain" '.($country=='Bahrain'? 'selected="selected"':'').'>Bahrain</option>
                            <option value="Bangladesh" '.($country=='Bangladesh'? 'selected="selected"':'').'>Bangladesh</option>
                            <option value="Barbados" '.($country=='Barbados'? 'selected="selected"':'').'>Barbados</option>
                            <option value="Belarus" '.($country=='Belarus'? 'selected="selected"':'').'>Belarus</option>
                            <option value="Belgium" '.($country=='Belgium'? 'selected="selected"':'').'>Belgium</option>
                            <option value="Belize" '.($country=='Belize'? 'selected="selected"':'').'>Belize</option>
                            <option value="Benin" '.($country=='Benin'? 'selected="selected"':'').'>Benin</option>
                            <option value="Bermuda" '.($country=='Bermuda'? 'selected="selected"':'').'>Bermuda</option>
                            <option value="Bhutan" '.($country=='Bhutan'? 'selected="selected"':'').'>Bhutan</option>
                            <option value="Bolivia" '.($country=='Bolivia'? 'selected="selected"':'').'>Bolivia</option>
                            <option value="Bosnia and Herzegovina" '.($country=='Bosnia and Herzegovina'? 'selected="selected"':'').'>Bosnia and Herzegovina</option>
                            <option value="Botswana" '.($country=='Botswana'? 'selected="selected"':'').'>Botswana</option>
                            <option value="Bouvet Island" '.($country=='Bouvet Island'? 'selected="selected"':'').'>Bouvet Island</option>
                            <option value="Brazil" '.($country=='Brazil'? 'selected="selected"':'').'>Brazil</option>
                            <option value="British Indian Ocean Territory" '.($country=='British Indian Ocean Territory'? 'selected="selected"':'').'>British Indian Ocean Territory</option>
                            <option value="Brunei Darussalam" '.($country=='Brunei Darussalam'? 'selected="selected"':'').'>Brunei Darussalam</option>
                            <option value="Bulgaria" '.($country=='Bulgaria'? 'selected="selected"':'').'>Bulgaria</option>
                            <option value="Burkina Faso" '.($country=='Burkina Faso'? 'selected="selected"':'').'>Burkina Faso</option>
                            <option value="Burundi" '.($country=='Burundi'? 'selected="selected"':'').'>Burundi</option>
                            <option value="Cambodia" '.($country=='Cambodia'? 'selected="selected"':'').'>Cambodia</option>
                            <option value="Cameroon" '.($country=='Cameroon'? 'selected="selected"':'').'>Cameroon</option>
                            <option value="Canada" '.($country=='Canada'? 'selected="selected"':'').'>Canada</option>
                            <option value="Cape Verde" '.($country=='Cape Verde'? 'selected="selected"':'').'>Cape Verde</option>
                            <option value="Cayman Islands" '.($country=='Cayman Islands'? 'selected="selected"':'').'>Cayman Islands</option>
                            <option value="Central African Republic" '.($country=='Central African Republic'? 'selected="selected"':'').'>Central African Republic</option>
                            <option value="Chad" '.($country=='Chad'? 'selected="selected"':'').'>Chad</option>
                            <option value="Chile" '.($country=='Chile'? 'selected="selected"':'').'>Chile</option>
                            <option value="China" '.($country=='China'? 'selected="selected"':'').'>China</option>
                            <option value="Christmas Island" '.($country=='Christmas Island'? 'selected="selected"':'').'>Christmas Island</option>
                            <option value="Cocos (Keeling) Islands" '.($country=='Cocos (Keeling) Islands'? 'selected="selected"':'').'>Cocos (Keeling) Islands</option>
                            <option value="Colombia" '.($country=='Colombia'? 'selected="selected"':'').'>Colombia</option>
                            <option value="Comoros" '.($country=='Comoros'? 'selected="selected"':'').'>Comoros</option>
                            <option value="Congo" '.($country=='Congo'? 'selected="selected"':'').'>Congo</option>
                            <option value="Congo, The Democratic Republic of The" '.($country=='Congo, The Democratic Republic of The'? 'selected="selected"':'').'>Congo, The Democratic Republic of The</option>
                            <option value="Cook Islands" '.($country=='Cook Islands'? 'selected="selected"':'').'>Cook Islands</option>
                            <option value="Costa Rica" '.($country=='Costa Rica'? 'selected="selected"':'').'>Costa Rica</option>
                            <option value="Cote D\'ivoire" '.($country=='Cote D\'ivoire'? 'selected="selected"':'').'>Cote D\'ivoire</option>
                            <option value="Croatia" '.($country=='Croatia'? 'selected="selected"':'').'>Croatia</option>
                            <option value="Cuba" '.($country=='Cuba'? 'selected="selected"':'').'>Cuba</option>
                            <option value="Cyprus" '.($country=='Cyprus'? 'selected="selected"':'').'>Cyprus</option>
                            <option value="Czech Republic" '.($country=='Czech Republic'? 'selected="selected"':'').'>Czech Republic</option>
                            <option value="Denmark" '.($country=='Denmark'? 'selected="selected"':'').'>Denmark</option>
                            <option value="Djibouti" '.($country=='Djibouti'? 'selected="selected"':'').'>Djibouti</option>
                            <option value="Dominica" '.($country=='Dominica'? 'selected="selected"':'').'>Dominica</option>
                            <option value="Dominican Republic" '.($country=='Dominican Republic'? 'selected="selected"':'').'>Dominican Republic</option>
                            <option value="Ecuador" '.($country=='Ecuador'? 'selected="selected"':'').'>Ecuador</option>
                            <option value="Egypt" '.($country=='Egypt'? 'selected="selected"':'').'>Egypt</option>
                            <option value="El Salvador" '.($country=='El Salvador'? 'selected="selected"':'').'>El Salvador</option>
                            <option value="Equatorial Guinea" '.($country=='Equatorial Guinea'? 'selected="selected"':'').'>Equatorial Guinea</option>
                            <option value="Eritrea" '.($country=='Eritrea'? 'selected="selected"':'').'>Eritrea</option>
                            <option value="Estonia" '.($country=='Estonia'? 'selected="selected"':'').'>Estonia</option>
                            <option value="Ethiopia" '.($country=='Ethiopia'? 'selected="selected"':'').'>Ethiopia</option>
                            <option value="Falkland Islands (Malvinas)" '.($country=='Falkland Islands (Malvinas)'? 'selected="selected"':'').'>Falkland Islands (Malvinas)</option>
                            <option value="Faroe Islands" '.($country=='Faroe Islands'? 'selected="selected"':'').'>Faroe Islands</option>
                            <option value="Fiji" '.($country=='Fiji'? 'selected="selected"':'').'>Fiji</option>
                            <option value="Finland" '.($country=='Finland'? 'selected="selected"':'').'>Finland</option>
                            <option value="France" '.($country=='France'? 'selected="selected"':'').'>France</option>
                            <option value="French Guiana" '.($country=='French Guiana'? 'selected="selected"':'').'>French Guiana</option>
                            <option value="French Polynesia" '.($country=='French Polynesia'? 'selected="selected"':'').'>French Polynesia</option>
                            <option value="French Southern Territories" '.($country=='French Southern Territories'? 'selected="selected"':'').'>French Southern Territories</option>
                            <option value="Gabon" '.($country=='Gabon'? 'selected="selected"':'').'>Gabon</option>
                            <option value="Gambia" '.($country=='Gambia'? 'selected="selected"':'').'>Gambia</option>
                            <option value="Georgia" '.($country=='Georgia'? 'selected="selected"':'').'>Georgia</option>
                            <option value="Germany" '.($country=='Germany'? 'selected="selected"':'').'>Germany</option>
                            <option value="Ghana" '.($country=='Ghana'? 'selected="selected"':'').'>Ghana</option>
                            <option value="Gibraltar" '.($country=='Gibraltar'? 'selected="selected"':'').'>Gibraltar</option>
                            <option value="Greece" '.($country=='Greece'? 'selected="selected"':'').'>Greece</option>
                            <option value="Greenland" '.($country=='Greenland'? 'selected="selected"':'').'>Greenland</option>
                            <option value="Grenada" '.($country=='Grenada'? 'selected="selected"':'').'>Grenada</option>
                            <option value="Guadeloupe" '.($country=='Guadeloupe'? 'selected="selected"':'').'>Guadeloupe</option>
                            <option value="Guam" '.($country=='Guam'? 'selected="selected"':'').'>Guam</option>
                            <option value="Guatemala" '.($country=='Guatemala'? 'selected="selected"':'').'>Guatemala</option>
                            <option value="Guernsey" '.($country=='Guernsey'? 'selected="selected"':'').'>Guernsey</option>
                            <option value="Guinea" '.($country=='Guinea'? 'selected="selected"':'').'>Guinea</option>
                            <option value="Guinea-bissau" '.($country=='Guinea-bissau'? 'selected="selected"':'').'>Guinea-bissau</option>
                            <option value="Guyana" '.($country=='Guyana'? 'selected="selected"':'').'>Guyana</option>
                            <option value="Haiti" '.($country=='Haiti'? 'selected="selected"':'').'>Haiti</option>
                            <option value="Heard Island and Mcdonald Islands" '.($country=='Heard Island and Mcdonald Islands'? 'selected="selected"':'').'>Heard Island and Mcdonald Islands</option>
                            <option value="Holy See (Vatican City State)" '.($country=='Holy See (Vatican City State)'? 'selected="selected"':'').'>Holy See (Vatican City State)</option>
                            <option value="Honduras" '.($country=='Honduras'? 'selected="selected"':'').'>Honduras</option>
                            <option value="Hong Kong" '.($country=='Hong Kong'? 'selected="selected"':'').'>Hong Kong</option>
                            <option value="Hungary" '.($country=='Hungary'? 'selected="selected"':'').'>Hungary</option>
                            <option value="Iceland" '.($country=='Iceland'? 'selected="selected"':'').'>Iceland</option>
                            <option value="India" '.($country=='India'? 'selected="selected"':'').'>India</option>
                            <option value="Indonesia" '.($country=='Indonesia'? 'selected="selected"':'').'>Indonesia</option>
                            <option value="Iran, Islamic Republic of" '.($country=='Iran, Islamic Republic of'? 'selected="selected"':'').'>Iran, Islamic Republic of</option>
                            <option value="Iraq" '.($country=='Iraq'? 'selected="selected"':'').'>Iraq</option>
                            <option value="Ireland" '.($country=='Ireland'? 'selected="selected"':'').'>Ireland</option>
                            <option value="Isle of Man" '.($country=='Isle of Man'? 'selected="selected"':'').'>Isle of Man</option>
                            <option value="Israel" '.($country=='Israel'? 'selected="selected"':'').'>Israel</option>
                            <option value="Italy" '.($country=='Italy'? 'selected="selected"':'').'>Italy</option>
                            <option value="Jamaica" '.($country=='Jamaica'? 'selected="selected"':'').'>Jamaica</option>
                            <option value="Japan" '.($country=='Japan'? 'selected="selected"':'').'>Japan</option>
                            <option value="Jersey" '.($country=='Jersey'? 'selected="selected"':'').'>Jersey</option>
                            <option value="Jordan" '.($country=='Jordan'? 'selected="selected"':'').'>Jordan</option>
                            <option value="Kazakhstan" '.($country=='Kazakhstan'? 'selected="selected"':'').'>Kazakhstan</option>
                            <option value="Kenya" '.($country=='Kenya'? 'selected="selected"':'').'>Kenya</option>
                            <option value="Kiribati" '.($country=='Kiribati'? 'selected="selected"':'').'>Kiribati</option>
                            <option value="Korea, Democratic People\'s Republic of" '.($country=='Korea, Democratic People\'s Republic of'? 'selected="selected"':'').'>Korea, Democratic People\'s Republic of</option>
                            <option value="Korea, Republic of" '.($country=='Korea, Republic of'? 'selected="selected"':'').'>Korea, Republic of</option>
                            <option value="Kuwait" '.($country=='Kuwait'? 'selected="selected"':'').'>Kuwait</option>
                            <option value="Kyrgyzstan" '.($country=='Kyrgyzstan'? 'selected="selected"':'').'>Kyrgyzstan</option>
                            <option value="Lao People\'s Democratic Republic" '.($country=='Lao People\'s Democratic Republic'? 'selected="selected"':'').'>Lao People\'s Democratic Republic</option>
                            <option value="Latvia" '.($country=='Latvia'? 'selected="selected"':'').'>Latvia</option>
                            <option value="Lebanon" '.($country=='Lebanon'? 'selected="selected"':'').'>Lebanon</option>
                            <option value="Lesotho" '.($country=='Lesotho'? 'selected="selected"':'').'>Lesotho</option>
                            <option value="Liberia" '.($country=='Liberia'? 'selected="selected"':'').'>Liberia</option>
                            <option value="Libyan Arab Jamahiriya" '.($country=='Libyan Arab Jamahiriya'? 'selected="selected"':'').'>Libyan Arab Jamahiriya</option>
                            <option value="Liechtenstein" '.($country=='Liechtenstein'? 'selected="selected"':'').'>Liechtenstein</option>
                            <option value="Lithuania" '.($country=='Lithuania'? 'selected="selected"':'').'>Lithuania</option>
                            <option value="Luxembourg" '.($country=='Luxembourg'? 'selected="selected"':'').'>Luxembourg</option>
                            <option value="Macao" '.($country=='Macao'? 'selected="selected"':'').'>Macao</option>
                            <option value="Macedonia, The Former Yugoslav Republic of" '.($country=='Macedonia, The Former Yugoslav Republic of'? 'selected="selected"':'').'>Macedonia, The Former Yugoslav Republic of</option>
                            <option value="Madagascar" '.($country=='Madagascar'? 'selected="selected"':'').'>Madagascar</option>
                            <option value="Malawi" '.($country=='Malawi'? 'selected="selected"':'').'>Malawi</option>
                            <option value="Malaysia" '.($country=='Malaysia'? 'selected="selected"':'').'>Malaysia</option>
                            <option value="Maldives" '.($country=='Maldives'? 'selected="selected"':'').'>Maldives</option>
                            <option value="Mali" '.($country=='Mali'? 'selected="selected"':'').'>Mali</option>
                            <option value="Malta" '.($country=='Malta'? 'selected="selected"':'').'>Malta</option>
                            <option value="Marshall Islands" '.($country=='Marshall Islands'? 'selected="selected"':'').'>Marshall Islands</option>
                            <option value="Martinique" '.($country=='Martinique'? 'selected="selected"':'').'>Martinique</option>
                            <option value="Mauritania" '.($country=='Mauritania'? 'selected="selected"':'').'>Mauritania</option>
                            <option value="Mauritius" '.($country=='Mauritius'? 'selected="selected"':'').'>Mauritius</option>
                            <option value="Mayotte" '.($country=='Mayotte'? 'selected="selected"':'').'>Mayotte</option>
                            <option value="Mexico" '.($country=='Mexico'? 'selected="selected"':'').'>Mexico</option>
                            <option value="Micronesia, Federated States of" '.($country=='Micronesia, Federated States of'? 'selected="selected"':'').'>Micronesia, Federated States of</option>
                            <option value="Moldova, Republic of" '.($country=='Moldova, Republic of'? 'selected="selected"':'').'>Moldova, Republic of</option>
                            <option value="Monaco" '.($country=='Monaco'? 'selected="selected"':'').'>Monaco</option>
                            <option value="Mongolia" '.($country=='Mongolia'? 'selected="selected"':'').'>Mongolia</option>
                            <option value="Montenegro" '.($country=='Montenegro'? 'selected="selected"':'').'>Montenegro</option>
                            <option value="Montserrat" '.($country=='Montserrat'? 'selected="selected"':'').'>Montserrat</option>
                            <option value="Morocco" '.($country=='Morocco'? 'selected="selected"':'').'>Morocco</option>
                            <option value="Mozambique" '.($country=='Mozambique'? 'selected="selected"':'').'>Mozambique</option>
                            <option value="Myanmar" '.($country=='Myanmar'? 'selected="selected"':'').'>Myanmar</option>
                            <option value="Namibia" '.($country=='Namibia'? 'selected="selected"':'').'>Namibia</option>
                            <option value="Nauru" '.($country=='Nauru'? 'selected="selected"':'').'>Nauru</option>
                            <option value="Nepal" '.($country=='Nepal'? 'selected="selected"':'').'>Nepal</option>
                            <option value="Netherlands" '.($country=='Netherlands'? 'selected="selected"':'').'>Netherlands</option>
                            <option value="Netherlands Antilles" '.($country=='Netherlands Antilles'? 'selected="selected"':'').'>Netherlands Antilles</option>
                            <option value="New Caledonia" '.($country=='New Caledonia'? 'selected="selected"':'').'>New Caledonia</option>
                            <option value="New Zealand" '.($country=='New Zealand'? 'selected="selected"':'').'>New Zealand</option>
                            <option value="Nicaragua" '.($country=='Nicaragua'? 'selected="selected"':'').'>Nicaragua</option>
                            <option value="Niger" '.($country=='Niger'? 'selected="selected"':'').'>Niger</option>
                            <option value="Nigeria" '.($country=='Nigeria'? 'selected="selected"':'').'>Nigeria</option>
                            <option value="Niue" '.($country=='Niue'? 'selected="selected"':'').'>Niue</option>
                            <option value="Norfolk Island" '.($country=='Norfolk Island'? 'selected="selected"':'').'>Norfolk Island</option>
                            <option value="Northern Mariana Islands" '.($country=='Northern Mariana Islands'? 'selected="selected"':'').'>Northern Mariana Islands</option>
                            <option value="Norway" '.($country=='Norway'? 'selected="selected"':'').'>Norway</option>
                            <option value="Oman" '.($country=='Oman'? 'selected="selected"':'').'>Oman</option>
                            <option value="Pakistan" '.($country=='Pakistan'? 'selected="selected"':'').'>Pakistan</option>
                            <option value="Palau" '.($country=='Palau'? 'selected="selected"':'').'>Palau</option>
                            <option value="Palestinian Territory, Occupied" '.($country=='Palestinian Territory, Occupied'? 'selected="selected"':'').'>Palestinian Territory, Occupied</option>
                            <option value="Panama" '.($country=='Panama'? 'selected="selected"':'').'>Panama</option>
                            <option value="Papua New Guinea" '.($country=='Papua New Guinea'? 'selected="selected"':'').'>Papua New Guinea</option>
                            <option value="Paraguay" '.($country=='Paraguay'? 'selected="selected"':'').'>Paraguay</option>
                            <option value="Peru" '.($country=='Peru'? 'selected="selected"':'').'>Peru</option>
                            <option value="Philippines" '.($country=='Philippines'? 'selected="selected"':'').'>Philippines</option>
                            <option value="Pitcairn" '.($country=='Pitcairn'? 'selected="selected"':'').'>Pitcairn</option>
                            <option value="Poland" '.($country=='Poland'? 'selected="selected"':'').'>Poland</option>
                            <option value="Portugal" '.($country=='Portugal'? 'selected="selected"':'').'>Portugal</option>
                            <option value="Puerto Rico" '.($country=='Puerto Rico'? 'selected="selected"':'').'>Puerto Rico</option>
                            <option value="Qatar" '.($country=='Qatar'? 'selected="selected"':'').'>Qatar</option>
                            <option value="Reunion" '.($country=='Reunion'? 'selected="selected"':'').'>Reunion</option>
                            <option value="Romania" '.($country=='Romania'? 'selected="selected"':'').'>Romania</option>
                            <option value="Russian Federation" '.($country=='Russian Federation'? 'selected="selected"':'').'>Russian Federation</option>
                            <option value="Rwanda" '.($country=='Rwanda'? 'selected="selected"':'').'>Rwanda</option>
                            <option value="Saint Helena" '.($country=='Saint Helena'? 'selected="selected"':'').'>Saint Helena</option>
                            <option value="Saint Kitts and Nevis" '.($country=='Saint Kitts and Nevis'? 'selected="selected"':'').'>Saint Kitts and Nevis</option>
                            <option value="Saint Lucia" '.($country=='Saint Lucia'? 'selected="selected"':'').'>Saint Lucia</option>
                            <option value="Saint Pierre and Miquelon" '.($country=='Saint Pierre and Miquelon'? 'selected="selected"':'').'>Saint Pierre and Miquelon</option>
                            <option value="Saint Vincent and The Grenadines" '.($country=='Saint Vincent and The Grenadines'? 'selected="selected"':'').'>Saint Vincent and The Grenadines</option>
                            <option value="Samoa" '.($country=='Samoa'? 'selected="selected"':'').'>Samoa</option>
                            <option value="San Marino" '.($country=='San Marino'? 'selected="selected"':'').'>San Marino</option>
                            <option value="Sao Tome and Principe" '.($country=='Sao Tome and Principe'? 'selected="selected"':'').'>Sao Tome and Principe</option>
                            <option value="Saudi Arabia" '.($country=='Saudi Arabia'? 'selected="selected"':'').'>Saudi Arabia</option>
                            <option value="Senegal" '.($country=='Senegal'? 'selected="selected"':'').'>Senegal</option>
                            <option value="Serbia" '.($country=='Serbia'? 'selected="selected"':'').'>Serbia</option>
                            <option value="Seychelles" '.($country=='Seychelles'? 'selected="selected"':'').'>Seychelles</option>
                            <option value="Sierra Leone" '.($country=='Sierra Leone'? 'selected="selected"':'').'>Sierra Leone</option>
                            <option value="Singapore" '.($country=='Singapore'? 'selected="selected"':'').'>Singapore</option>
                            <option value="Slovakia" '.($country=='Slovakia'? 'selected="selected"':'').'>Slovakia</option>
                            <option value="Slovenia" '.($country=='Slovenia'? 'selected="selected"':'').'>Slovenia</option>
                            <option value="Solomon Islands" '.($country=='Solomon Islands'? 'selected="selected"':'').'>Solomon Islands</option>
                            <option value="Somalia" '.($country=='Somalia'? 'selected="selected"':'').'>Somalia</option>
                            <option value="South Africa" '.($country=='South Africa'? 'selected="selected"':'').'>South Africa</option>
                            <option value="South Georgia and The South Sandwich Islands" '.($country=='South Georgia and The South Sandwich Islands'? 'selected="selected"':'').'>South Georgia and The South Sandwich Islands</option>
                            <option value="Spain" '.($country=='Spain'? 'selected="selected"':'').'>Spain</option>
                            <option value="Sri Lanka" '.($country=='Sri Lanka'? 'selected="selected"':'').'>Sri Lanka</option>
                            <option value="Sudan" '.($country=='Sudan'? 'selected="selected"':'').'>Sudan</option>
                            <option value="Suriname" '.($country=='Suriname'? 'selected="selected"':'').'>Suriname</option>
                            <option value="Svalbard and Jan Mayen" '.($country=='Svalbard and Jan Mayen'? 'selected="selected"':'').'>Svalbard and Jan Mayen</option>
                            <option value="Swaziland" '.($country=='Swaziland'? 'selected="selected"':'').'>Swaziland</option>
                            <option value="Sweden" '.($country=='Sweden'? 'selected="selected"':'').'>Sweden</option>
                            <option value="Switzerland" '.($country=='Switzerland'? 'selected="selected"':'').'>Switzerland</option>
                            <option value="Syrian Arab Republic" '.($country=='Syrian Arab Republic'? 'selected="selected"':'').'>Syrian Arab Republic</option>
                            <option value="Taiwan, Province of China" '.($country=='Taiwan, Province of China'? 'selected="selected"':'').'>Taiwan, Province of China</option>
                            <option value="Tajikistan" '.($country=='Tajikistans'? 'selected="selected"':'').'>Tajikistan</option>
                            <option value="Tanzania, United Republic of" '.($country=='Tanzania, United Republic of'? 'selected="selected"':'').'>Tanzania, United Republic of</option>
                            <option value="Thailand" '.($country=='Thailand'? 'selected="selected"':'').'>Thailand</option>
                            <option value="Timor-leste" '.($country=='Timor-leste'? 'selected="selected"':'').'>Timor-leste</option>
                            <option value="Togo" '.($country=='Togo'? 'selected="selected"':'').'>Togo</option>
                            <option value="Tokelau" '.($country=='Tokelau'? 'selected="selected"':'').'>Tokelau</option>
                            <option value="Tonga" '.($country=='Tonga'? 'selected="selected"':'').'>Tonga</option>
                            <option value="Trinidad and Tobago" '.($country=='Trinidad and Tobago'? 'selected="selected"':'').'>Trinidad and Tobago</option>
                            <option value="Tunisia" '.($country=='Tunisia'? 'selected="selected"':'').'>Tunisia</option>
                            <option value="Turkey" '.($country=='Turkey'? 'selected="selected"':'').'>Turkey</option>
                            <option value="Turkmenistan" '.($country=='Turkmenistan'? 'selected="selected"':'').'>Turkmenistan</option>
                            <option value="Turks and Caicos Islands" '.($country=='Turks and Caicos Islands'? 'selected="selected"':'').'>Turks and Caicos Islands</option>
                            <option value="Tuvalu" '.($country=='Tuvalu'? 'selected="selected"':'').'>Tuvalu</option>
                            <option value="Uganda" '.($country=='Uganda'? 'selected="selected"':'').'>Uganda</option>
                            <option value="Ukraine" '.($country=='Ukraine'? 'selected="selected"':'').'>Ukraine</option>
                            <option value="United Arab Emirates" '.($country=='United Arab Emirates'? 'selected="selected"':'').'>United Arab Emirates</option>
                            <option value="United Kingdom" '.($country=='United Kingdom'? 'selected="selected"':'').'>United Kingdom</option>
                            <option value="United States" '.($country=='United States'? 'selected="selected"':'').'>United States</option>
                            <option value="United States Minor Outlying Islands" '.($country=='United States Minor Outlying Islands'? 'selected="selected"':'').'>United States Minor Outlying Islands</option>
                            <option value="Uruguay" '.($country=='Uruguay'? 'selected="selected"':'').'>Uruguay</option>
                            <option value="Uzbekistan" '.($country=='Uzbekistan'? 'selected="selected"':'').'>Uzbekistan</option>
                            <option value="Vanuatu" '.($country=='Vanuatu'? 'selected="selected"':'').'>Vanuatu</option>
                            <option value="Venezuela" '.($country=='Venezuela'? 'selected="selected"':'').'>Venezuela</option>
                            <option value="Viet Nam" '.($country=='Viet Nam'? 'selected="selected"':'').'>Viet Nam</option>
                            <option value="Virgin Islands, British" '.($country=='Virgin Islands, British'? 'selected="selected"':'').'>Virgin Islands, British</option>
                            <option value="Virgin Islands, U.S." '.($country=='Virgin Islands, U.S.'? 'selected="selected"':'').'>Virgin Islands, U.S.</option>
                            <option value="Wallis and Futuna" '.($country=='Wallis and Futuna'? 'selected="selected"':'').'>Wallis and Futuna</option>
                            <option value="Western Sahara" '.($country=='Western Sahara'? 'selected="selected"':'').'>Western Sahara</option>
                            <option value="Yemen" '.($country=='Yemen'? 'selected="selected"':'').'>Yemen</option>
                            <option value="Zambia" '.($country=='Zambia'? 'selected="selected"':'').'>Zambia</option>
                            <option value="Zimbabwe" '.($country=='Zimbabwe'? 'selected="selected"':'').'>Zimbabwe</option>
                            </select>
	';

	return $buf;
}
function MonthSelect($id='', $month=''){
	if($id==''){
		$id="month";
	}
	$buf='
	<select style="width:80px;" name="form_month" id="'.$id.'">
		<option value="" '.($month==''? 'selected="selected"':'').'></option>
        <option value="01" '.($month=='01'? 'selected="selected"':'').'>January</option>
        <option value="02" '.($month=='02'? 'selected="selected"':'').'>Febuary</option>
        <option value="03" '.($month=='03'? 'selected="selected"':'').'>March</option>
        <option value="04" '.($month=='04'? 'selected="selected"':'').'>April</option>
        <option value="05" '.($month=='05'? 'selected="selected"':'').'>May</option>
        <option value="06" '.($month=='06'? 'selected="selected"':'').'>June</option>
        <option value="07" '.($month=='07'? 'selected="selected"':'').'>July</option>
        <option value="08" '.($month=='08'? 'selected="selected"':'').'>August</option>
        <option value="09" '.($month=='09'? 'selected="selected"':'').'>September</option>
        <option value="10" '.($month=='10'? 'selected="selected"':'').'>October</option>
        <option value="11" '.($month=='11'? 'selected="selected"':'').'>November</option>
        <option value="12" '.($month=='12'? 'selected="selected"':'').'>December</option>
    </select>
	';
	return $buf;
}
function YearSelect($start, $end, $id='', $year=''){
	if($id==''){
		$id="year";
	}
	$buf='
	<select style="width:80px;" name="form_year" id="'.$id.'">
		<option value=""></option>';
	for($i = 0; $start+$i <= $end; $i++){
		$buf.='<option value="'.($start+$i).'" '.($year==($start+$i)? 'selected="selected"':'').'>'.($start+$i).'</option>';
	}

    $buf .= '</select>';
	return $buf;
}

