<?php

if (role(['permissions' => ['memberships' => 'view_membership_info']])) {
    $form = array();
    $form['loaded'] = new stdClass();
    $form['loaded']->title = Registry::load('strings')->billing_info;
    $form['loaded']->button = Registry::load('strings')->update;


    $form['fields'] = new stdClass();

    $form['fields']->update = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "billing_info"
    ];

    $form['fields']->billed_to = [
        "title" => Registry::load('strings')->billed_to, "tag" => 'input', "type" => "text",
        "class" => 'field'
    ];

    $form['fields']->street_address = [
        "title" => Registry::load('strings')->street_address, "tag" => 'textarea', "closetag" => true,
        "class" => 'field', "placeholder" => Registry::load('strings')->street_address,
    ];

    $form['fields']->street_address["attributes"] = ["rows" => 5];

    $form['fields']->city = [
        "title" => Registry::load('strings')->city, "tag" => 'input', "type" => "text",
        "class" => 'field'
    ];

    $form['fields']->state = [
        "title" => Registry::load('strings')->state, "tag" => 'input', "type" => "text",
        "class" => 'field'
    ];

    $countries = [
        "afghanistan" => "Afghanistan", "albania" => "Albania", "algeria" => "Algeria", "andorra" => "Andorra", "angola" => "Angola",
        "antigua_and_barbuda" => "Antigua and Barbuda", "argentina" => "Argentina", "armenia" => "Armenia", "australia" => "Australia",
        "austria" => "Austria", "azerbaijan" => "Azerbaijan", "bahamas" => "Bahamas", "bahrain" => "Bahrain", "bangladesh" => "Bangladesh",
        "barbados" => "Barbados", "belarus" => "Belarus", "belgium" => "Belgium", "belize" => "Belize", "benin" => "Benin",
        "bhutan" => "Bhutan", "bolivia" => "Bolivia", "bosnia_and_herzegovina" => "Bosnia and Herzegovina", "botswana" =>
        "Botswana", "brazil" => "Brazil", "brunei" => "Brunei", "bulgaria" => "Bulgaria", "burkina_faso" => "Burkina Faso",
        "burundi" => "Burundi", "cabo_verde" => "Cabo Verde", "cambodia" => "Cambodia", "cameroon" => "Cameroon", "canada" => "Canada",
        "central_african_republic" => "Central African Republic", "chad" => "Chad", "chile" => "Chile", "china" => "China",
        "colombia" => "Colombia", "comoros" => "Comoros", "congo_brazzaville" => "Congo (Brazzaville)", "congo_kinshasa" => "Congo (Kinshasa)",
        "costa_rica" => "Costa Rica", "cote_divoire" => "Cote d'Ivoire", "croatia" => "Croatia", "cuba" => "Cuba", "cyprus" => "Cyprus",
        "czechia" => "Czechia", "denmark" => "Denmark", "djibouti" => "Djibouti", "dominica" => "Dominica",
        "dominican_republic" => "Dominican Republic", "ecuador" => "Ecuador", "egypt" => "Egypt", "el_salvador" => "El Salvador",
        "equatorial_guinea" => "Equatorial Guinea", "eritrea" => "Eritrea", "estonia" => "Estonia", "eswatini" => "Eswatini",
        "ethiopia" => "Ethiopia", "fiji" => "Fiji", "finland" => "Finland", "france" => "France", "gabon" => "Gabon", "gambia" => "Gambia",
        "georgia" => "Georgia", "germany" => "Germany", "ghana" => "Ghana", "greece" => "Greece", "grenada" => "Grenada",
        "guatemala" => "Guatemala", "guinea" => "Guinea", "guinea_bissau" => "Guinea-Bissau", "guyana" => "Guyana", "haiti" => "Haiti",
        "honduras" => "Honduras", "hungary" => "Hungary", "iceland" => "Iceland", "india" => "India", "indonesia" => "Indonesia",
        "iran" => "Iran", "iraq" => "Iraq", "ireland" => "Ireland", "israel" => "Israel", "italy" => "Italy", "jamaica" => "Jamaica",
        "japan" => "Japan", "jordan" => "Jordan", "kazakhstan" => "Kazakhstan", "kenya" => "Kenya", "kiribati" => "Kiribati",
        "korea_north" => "Korea, North", "korea_south" => "Korea, South", "kosovo" => "Kosovo", "kuwait" => "Kuwait",
        "kyrgyzstan" => "Kyrgyzstan", "laos" => "Laos", "latvia" => "Latvia", "lebanon" => "Lebanon", "lesotho" => "Lesotho",
        "liberia" => "Liberia", "libya" => "Libya", "liechtenstein" => "Liechtenstein", "lithuania" => "Lithuania",
        "luxembourg" => "Luxembourg", "madagascar" => "Madagascar", "malawi" => "Malawi", "malaysia" => "Malaysia", "maldives" => "Maldives",
        "mali" => "Mali", "malta" => "Malta", "marshall_islands" => "Marshall Islands", "mauritania" => "Mauritania",
        "mauritius" => "Mauritius", "mexico" => "Mexico", "micronesia" => "Micronesia", "moldova" => "Moldova", "monaco" => "Monaco",
        "mongolia" => "Mongolia", "montenegro" => "Montenegro", "morocco" => "Morocco", "mozambique" => "Mozambique",
        "myanmar_burma" => "Myanmar (Burma)", "namibia" => "Namibia", "nauru" => "Nauru", "nepal" => "Nepal", "netherlands" => "Netherlands",
        "new_zealand" => "New Zealand", "nicaragua" => "Nicaragua", "niger" => "Niger", "nigeria" => "Nigeria",
        "north_macedonia" => "North Macedonia", "norway" => "Norway", "oman" => "Oman", "pakistan" => "Pakistan", "palau" => "Palau",
        "palestine" => "Palestine", "panama" => "Panama", "papua_new_guinea" => "Papua New Guinea", "paraguay" => "Paraguay", "peru" => "Peru",
        "philippines" => "Philippines", "poland" => "Poland", "portugal" => "Portugal", "qatar" => "Qatar", "romania" => "Romania",
        "russia" => "Russia", "rwanda" => "Rwanda", "saint_kitts_and_nevis" => "Saint Kitts and Nevis", "saint_lucia" => "Saint Lucia",
        "saint_vincent_and_the_grenadines" => "Saint Vincent and the Grenadines", "samoa" => "Samoa", "san_marino" => "San Marino",
        "sao_tome_and_principe" => "Sao Tome and Principe", "saudi_arabia" => "Saudi Arabia", "senegal" => "Senegal", "serbia" => "Serbia",
        "seychelles" => "Seychelles", "sierra_leone" => "Sierra Leone", "singapore" => "Singapore", "slovakia" => "Slovakia",
        "slovenia" => "Slovenia", "solomon_islands" => "Solomon Islands", "somalia" => "Somalia", "south_africa" => "South Africa",
        "south_sudan" => "South Sudan", "spain" => "Spain", "sri_lanka" => "Sri Lanka", "sudan" => "Sudan", "suriname" => "Suriname",
        "sweden" => "Sweden", "switzerland" => "Switzerland", "syria" => "Syria", "taiwan" => "Taiwan", "tajikistan" => "Tajikistan",
        "tanzania" => "Tanzania", "thailand" => "Thailand", "timor_leste" => "Timor-Leste", "togo" => "Togo", "tonga" => "Tonga",
        "trinidad_and_tobago" => "Trinidad and Tobago", "tunisia" => "Tunisia", "turkey" => "Turkey", "turkmenistan" => "Turkmenistan",
        "tuvalu" => "Tuvalu", "uganda" => "Uganda", "ukraine" => "Ukraine", "united_arab_emirates" => "United Arab Emirates",
        "united_kingdom" => "United Kingdom", "united_states_of_america" => "United States of America", "uruguay" => "Uruguay",
        "uzbekistan" => "Uzbekistan", "vanuatu" => "Vanuatu", "vatican_city" => "Vatican City", "venezuela" => "Venezuela",
        "vietnam" => "Vietnam", "yemen" => "Yemen", "zambia" => "Zambia", "zimbabwe" => "Zimbabwe"
    ];


    $form['fields']->country = [
        "title" => Registry::load('strings')->country, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->country['options'] = $countries;



    $form['fields']->postal_code = [
        "title" => Registry::load('strings')->postal_code, "tag" => 'input', "type" => "text",
        "class" => 'field'
    ];

    $columns = $join = $where = null;
    $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
    $where["billing_address.user_id"] = Registry::load('current_user')->id;
    $billing_address = DB::connect()->select('billing_address', $columns, $where);

    if (!empty($billing_address)) {
        $billing_address = $billing_address[0];
        $form['fields']->postal_code['value'] = $billing_address['postal_code'];
        $form['fields']->country['value'] = $billing_address['country'];
        $form['fields']->state['value'] = $billing_address['state'];
        $form['fields']->city['value'] = $billing_address['city'];
        $form['fields']->billed_to['value'] = $billing_address['billed_to'];
        $form['fields']->street_address['value'] = $billing_address['street_address'];
    }

}
?>