<?php 

$languages_array = array("af"=>"Afrikaans","sq"=>"Albanian","am"=>"Amharic","ar"=>"Arabic","hy"=>"Armenian","az"=>"Azerbaijani","bjs"=>"Bajan","rm"=>"Balkan Gipsy","eu"=>"Basque","bem"=>"Bemba","bn"=>"Bengali","be"=>"Bielarus","bi"=>"Bislama","bs"=>"Bosnian","br"=>"Breton","bg"=>"Bulgarian","my"=>"Burmese","ca"=>"Catalan","cb"=>"Cebuano","ch"=>"Chamorro","zh"=>"Chinese (Simplified)","zh"=>"Chinese Traditional","zdj"=>"Comorian (Ngazidja)","cop"=>"Coptic","aig"=>"English (Antigua and Barbuda)","bah"=>"English (Bahamas)","gcl"=>"English (Grenadian)","gyn"=>"English (Guyanese)","xx"=>"English (Jamaican)","svc"=>"English (Vincentian)","vic"=>"English (Virgin Islands)","ht"=>"French (Haitian)","acf"=>"French (Saint Lucian)","crs"=>"French (Seselwa)","pov"=>"Portuguese (Upper Guinea)","hr"=>"Croatian","cs"=>"Czech","da"=>"Danish","nl"=>"Dutch","dz"=>"Dzongkha","en"=>"English","eo"=>"Esperanto","et"=>"Estonian","fn"=>"Fanagalo","fo"=>"Faroese","fi"=>"Finnish","fr"=>"French","gl"=>"Galician","ka"=>"Georgian","de"=>"German","el"=>"Greek","XN"=>"Greek (Classical)","gu"=>"Gujarati","ha"=>"Hausa","XN"=>"Hawaiian","he"=>"Hebrew","hi"=>"Hindi","hu"=>"Hungarian","is"=>"Icelandic","id"=>"Indonesian","kl"=>"Inuktitut (Greenlandic)","ga"=>"Irish Gaelic","it"=>"Italian","ja"=>"Japanese","jw"=>"Javanese","kea"=>"Kabuverdianu","kab"=>"Kabylian","ka"=>"Kannada","kk"=>"Kazakh","km"=>"Khmer","rw"=>"Kinyarwanda","rn"=>"Kirundi","ko"=>"Korean","ku"=>"Kurdish","ku"=>"Kurdish Sorani","ky"=>"Kyrgyz","lo"=>"Lao","la"=>"Latin","lv"=>"Latvian","lt"=>"Lithuanian","lb"=>"Luxembourgish","mk"=>"Macedonian","mg"=>"Malagasy","ms"=>"Malay","dv"=>"Maldivian","mt"=>"Maltese","gv"=>"Manx Gaelic","mi"=>"Maori","mh"=>"Marshallese","men"=>"Mende","mn"=>"Mongolian","mfe"=>"Morisyen","ne"=>"Nepali","niu"=>"Niuean","no"=>"Norwegian","ny"=>"Nyanja","ur"=>"Pakistani","pau"=>"Palauan","pa"=>"Panjabi","pap"=>"Papiamentu","ps"=>"Pashto","fa"=>"Persian","pis"=>"Pijin","pl"=>"Polish","pt"=>"Portuguese","pot"=>"Potawatomi","qu"=>"Quechua","ro"=>"Romanian","ru"=>"Russian","sm"=>"Samoan","sg"=>"Sango","gd"=>"Scots Gaelic","sr"=>"Serbian","sn"=>"Shona","si"=>"Sinhala","sk"=>"Slovak","sl"=>"Slovenian","so"=>"Somali","st"=>"Sotho Southern","es"=>"Spanish","srn"=>"Sranan Tongo","sw"=>"Swahili","sv"=>"Swedish","de"=>"Swiss German","syc"=>"Syriac (Aramaic)","tl"=>"Tagalog","tg"=>"Tajik","tmh"=>"Tamashek (Tuareg)","ta"=>"Tamil","te"=>"Telugu","tet"=>"Tetum","th"=>"Thai","bo"=>"Tibetan","ti"=>"Tigrinya","tpi"=>"Tok Pisin","tkl"=>"Tokelauan","to"=>"Tongan","tn"=>"Tswana","tr"=>"Turkish","tk"=>"Turkmen","tvl"=>"Tuvaluan","uk"=>"Ukrainian","ppk"=>"Uma","uz"=>"Uzbek","vi"=>"Vietnamese","wls"=>"Wallisian","cy"=>"Welsh","wo"=>"Wolof","xh"=>"Xhosa","yi"=>"Yiddish","zu"=>"Zulu");

define(LANGUAGES, serialize($languages_array));

$api_lang_arr = array();

function tr_box_scripts() {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'tr-box-request', plugin_dir_url( __FILE__ ) . 'js/api_ajax.js', array( 'jquery' ) );
	wp_localize_script( 'tr-box-request', 'tr_box_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php'),'security_check' => wp_create_nonce( 'tr_box_check' ), ) );

}    


function tr_box_translate ($atts)
	{

		extract(shortcode_atts( array(
		'languages' => 'default',
		'width' => '100%',
		'height' => '110px',
		'bgcolor' => '#ffffff',
		'txtcolor' => '#000000'
	), $atts ) );
		if ($languages=='default') 
		{
			$languages = array_values(unserialize(LANGUAGES));
		}
		else 
		{
			$languages = explode(',', $languages);
				foreach ($languages as $key => $value) {
					$value = ucfirst(trim($value));
					if (in_array(ucfirst($value), unserialize(LANGUAGES))) {
						$languages[$key] = $value;
					} else {unset($languages[$key]);}
				}
		}

		$api_lang_arr = array_flip(unserialize(LANGUAGES));

		echo "<textarea id='text_to' style='width:{$width}; height:{$height};background-color:{$bgcolor}';color:{$txtcolor}';></textarea>";
		echo "<select id=\"from\">";
		foreach ($languages as $value) {
			echo	"<option value=\"{$api_lang_arr[$value]}\">$value</option>";	
		}
		echo "</select>&nbsp;<a href='javascript:swap_langs()'>".__(' To: ')."&nbsp;</a>";
		echo "<select id=\"to\">";
		foreach ($languages as $key => $value) {
			echo	"<option value=\"{$api_lang_arr[$value]}\">$value</option>";	
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;<input type=\"submit\" id='translate' style='height:27px' value=\"".__('Translate')."\" onclick=\"get_translation($('#text_to').val(),$('#from').val(),$('#to').val(),'{$width}','{$height}','{$bgcolor}','{$txtcolor}');return false;\"><br>";
		echo base64_decode(get_option('trbox_important'));
	}
function tr_box_ajax_call(
){
	$nonce = $_POST['security_check'];

   if ( ! wp_verify_nonce($nonce, 'tr_box_check' ))
   	{	die ( 'CSRF Check Failed !');	}
 
	$text = $_POST['text_to_translate'];
	$from = $_POST['from_language'];
	$to = $_POST['to_language'];
	$ch = curl_init();
	$text = urlencode($text);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_URL, "http://mymemory.translated.net/api/get?q={$text}&langpair={$from}|{$to}");
	$response = curl_exec($ch);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}

function translation_box_options (){
	add_options_page('Translation Box','Translation Box','manage_options', 'translation_box_options','translation_box_page');
	update_option('trbox_important', 'PGEgaHJlZj0iaHR0cDovL3d3dy50cmFuc2xhdG9yYm94LmNvbS8iIHRhcmdldD0iX2JsYW5rIiBzdHlsZT0icG9zaXRpb246cmVsYXRpdmU7bGVmdDo4MCUiPlRyYW5zbGF0b3Jib3g8L2E+');
}

function translation_box_page()
{	
  wp_enqueue_script( 'tr-box-request', plugin_dir_url( __FILE__ ) . 'js/api_ajax.js', array( 'jquery' ) );
  echo "
  <div class=\"wrap\" > 
  <?php screen_icon(); ?> 
  <h2>".__('Help Page of Translation Box')."</h2><br>
  <p class='description'>".__('Thanks to this plug with one easy shortcode you are able to transform every post or page into a translation area.')."</p><br>

  <p>".__('The next shortcode is example of the simple usage of Translation Box:')."<br>
  		<p class='box-short-code'><strong>[translation_box languages=\"english,russian,german,spanish,french,chinese\"  width=\"100%\" height=\"200px\" bgcolor=\"white\" txtcolor=\"#000000\"]</strong></p>
  		
  		<p> ".__('The shortcode is')." <strong>[translation_box]</strong>.".__(' If you use it by itself it will default to showing all the languages from the full list at the end of the section and also will have')." <strong>".__('width')."</strong> ".__('of 100%').", <strong>".__('height')."</strong> ".__('of 110px').", <strong>".__('bgcolor')."</strong> ".__('of white and')." <strong>".__('txtcolor')."</strong> ".__('of black').".</p>
  		<ol>
  		<li>".__('The first attribute is called <strong>languages</strong> and is equal to the list of languages (for full list of supported langugages check the bottom section !) you would like to include in your translation box. Make sure you use comma for separation of different languages').".</li>
  		<li>".__('The second attribute is called <strong>width</strong> and it is used for setting up the width of the translation boxes. It can accept values in %, px, em, etc.')." .</li>
  		<li>".__('The third attribute is called <strong>height</strong> and it is used for setting up the width of the translation boxes. It can accept values in %, px, em, etc.')." .</li>
  		<li>".__('The fourth attribute is called <strong>bgcolor</strong> and it is used for setting up the CSS background color for the text box for translation. Make sure you suppliy valid CSS values for this property')." .</li>
  		<li>".__('The fifth attribute is called <strong>txtcolor</strong> and it is used for setting up the color of the text in the text box for translation. Make sure you suppliy valid CSS values for this property')." .</li>
  		</ol>
  		<p>
  			<h3>".__('Full list of supported languages:')."</h3>
  			<table border='1' cellpadding='2'>
  				<tbody>
  					<tr>
						<td><input type='checkbox' name='langs' value='Afrikaans' />Afrikaans</td>
						<td><input type='checkbox' name='langs' value='Albanian' />Albanian</td>
						<td><input type='checkbox' name='langs' value='Amharic' />Amharic</td>
						<td><input type='checkbox' name='langs' value='Arabic' />Arabic</td>
						<td><input type='checkbox' name='langs' value='Armenian' />Armenian</td>
						<td><input type='checkbox' name='langs' value='Azerbaijani' />Azerbaijani</td>
						<td><input type='checkbox' name='langs' value='Bajan' />Bajan</td>
						<td><input type='checkbox' name='langs' value='Balkan Gipsy' />Balkan Gipsy</td>
						<td><input type='checkbox' name='langs' value='Basque' />Basque</td>
						<td><input type='checkbox' name='langs' value='Bemba' />Bemba</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Bengali' />Bengali</td>
						<td><input type='checkbox' name='langs' value='Bielarus' />Bielarus</td>
						<td><input type='checkbox' name='langs' value='Bislama' />Bislama</td>
						<td><input type='checkbox' name='langs' value='Bosnian' />Bosnian</td>
						<td><input type='checkbox' name='langs' value='Breton' />Breton</td>
						<td><input type='checkbox' name='langs' value='Bulgarian' />Bulgarian</td>
						<td><input type='checkbox' name='langs' value='Burmese' />Burmese</td>
						<td><input type='checkbox' name='langs' value='Catalan' />Catalan</td>
						<td><input type='checkbox' name='langs' value='Cebuano' />Cebuano</td>
						<td><input type='checkbox' name='langs' value='Chamorro' />Chamorro</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Chinese (Simplified)' />Chinese (Simplified)</td>
						<td><input type='checkbox' name='langs' value='Chinese (Traditional)' />Chinese (Traditional)</td>
						<td><input type='checkbox' name='langs' value='Comorian (Ngazidja)' />Comorian (Ngazidja)</td>
						<td><input type='checkbox' name='langs' value='Coptic' />Coptic</td>
						<td><input type='checkbox' name='langs' value='English (Antigua and Barbuda)' />English (Antigua and Barbuda)</td>
						<td><input type='checkbox' name='langs' value='English (Bahamas)' />English (Bahamas)</td>
						<td><input type='checkbox' name='langs' value='English (Grenadian)' />English (Grenadian)</td>
						<td><input type='checkbox' name='langs' value='English (Guyanese)' />English (Guyanese)</td>
						<td><input type='checkbox' name='langs' value='English (Jamaican)' />English (Jamaican)</td>
						<td><input type='checkbox' name='langs' value='English (Vincentian)' />English (Vincentian)</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='English (Virgin Islands)' />English (Virgin Islands)</td>
						<td><input type='checkbox' name='langs' value='French (Haitian)' />French (Haitian)</td>
						<td><input type='checkbox' name='langs' value='French (Saint Lucian)' />French (Saint Lucian)</td>
						<td><input type='checkbox' name='langs' value='French (Seselwa)' />French (Seselwa)</td>
						<td><input type='checkbox' name='langs' value='Portuguese (Upper Guinea)' />Portuguese (Upper Guinea)</td>
						<td><input type='checkbox' name='langs' value='Croatian' />Croatian</td>
						<td><input type='checkbox' name='langs' value='Czech' />Czech</td>
						<td><input type='checkbox' name='langs' value='Danish' />Danish</td>
						<td><input type='checkbox' name='langs' value='Dutch' />Dutch</td>
						<td><input type='checkbox' name='langs' value='Dzongkha' />Dzongkha</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='English' />English</td>
						<td><input type='checkbox' name='langs' value='Esperanto' />Esperanto</td>
						<td><input type='checkbox' name='langs' value='Estonian' />Estonian</td>
						<td><input type='checkbox' name='langs' value='Fanagalo' />Fanagalo</td>
						<td><input type='checkbox' name='langs' value='Faroese' />Faroese</td>
						<td><input type='checkbox' name='langs' value='Finnish' />Finnish</td>
						<td><input type='checkbox' name='langs' value='French' />French</td>
						<td><input type='checkbox' name='langs' value='Galician' />Galician</td>
						<td><input type='checkbox' name='langs' value='Georgian' />Georgian</td>
						<td><input type='checkbox' name='langs' value='German' />German</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Greek' />Greek</td>
						<td><input type='checkbox' name='langs' value='Greek (Classical)' />Greek (Classical)</td>
						<td><input type='checkbox' name='langs' value='Gujarati' />Gujarati</td>
						<td><input type='checkbox' name='langs' value='Hausa' />Hausa</td>
						<td><input type='checkbox' name='langs' value='Hawaiian' />Hawaiian</td>
						<td><input type='checkbox' name='langs' value='Hebrew' />Hebrew</td>
						<td><input type='checkbox' name='langs' value='Hindi' />Hindi</td>
						<td><input type='checkbox' name='langs' value='Hungarian' />Hungarian</td>
						<td><input type='checkbox' name='langs' value='Icelandic' />Icelandic</td>
						<td><input type='checkbox' name='langs' value='Indonesian' />Indonesian</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Inuktitut (Greenlandic)' />Inuktitut (Greenlandic)</td>
						<td><input type='checkbox' name='langs' value='Irish Gaelic' />Irish Gaelic</td>
						<td><input type='checkbox' name='langs' value='Italian' />Italian</td>
						<td><input type='checkbox' name='langs' value='Japanese' />Japanese</td>
						<td><input type='checkbox' name='langs' value='Javanese' />Javanese</td>
						<td><input type='checkbox' name='langs' value='Kabuverdianu' />Kabuverdianu</td>
						<td><input type='checkbox' name='langs' value='Kabylian' />Kabylian</td>
						<td><input type='checkbox' name='langs' value='Kannada' />Kannada</td>
						<td><input type='checkbox' name='langs' value='Kazakh' />Kazakh</td>
						<td><input type='checkbox' name='langs' value='Khmer' />Khmer</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Kinyarwanda' />Kinyarwanda</td>
						<td><input type='checkbox' name='langs' value='Kirundi' />Kirundi</td>
						<td><input type='checkbox' name='langs' value='Korean' />Korean</td>
						<td><input type='checkbox' name='langs' value='Kurdish' />Kurdish</td>
						<td><input type='checkbox' name='langs' value='Kurdish Sorani' />Kurdish Sorani</td>
						<td><input type='checkbox' name='langs' value='Kyrgyz' />Kyrgyz</td>
						<td><input type='checkbox' name='langs' value='Lao' />Lao</td>
						<td><input type='checkbox' name='langs' value='Latin' />Latin</td>
						<td><input type='checkbox' name='langs' value='Latvian' />Latvian</td>
						<td><input type='checkbox' name='langs' value='Lithuanian' />Lithuanian</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Luxembourgish' />Luxembourgish</td>
						<td><input type='checkbox' name='langs' value='Macedonian' />Macedonian</td>
						<td><input type='checkbox' name='langs' value='Malagasy' />Malagasy</td>
						<td><input type='checkbox' name='langs' value='Malay' />Malay</td>
						<td><input type='checkbox' name='langs' value='Maldivian' />Maldivian</td>
						<td><input type='checkbox' name='langs' value='Maltese' />Maltese</td>
						<td><input type='checkbox' name='langs' value='Manx Gaelic' />Manx Gaelic</td>
						<td><input type='checkbox' name='langs' value='Maori' />Maori</td>
						<td><input type='checkbox' name='langs' value='Marshallese' />Marshallese</td>
						<td><input type='checkbox' name='langs' value='Mende' />Mende</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Mongolian' />Mongolian</td>
						<td><input type='checkbox' name='langs' value='Morisyen' />Morisyen</td>
						<td><input type='checkbox' name='langs' value='Nepali' />Nepali</td>
						<td><input type='checkbox' name='langs' value='Niuean' />Niuean</td>
						<td><input type='checkbox' name='langs' value='Norwegian' />Norwegian</td>
						<td><input type='checkbox' name='langs' value='Nyanja' />Nyanja</td>
						<td><input type='checkbox' name='langs' value='Pakistani' />Pakistani</td>
						<td><input type='checkbox' name='langs' value='Palauan' />Palauan</td>
						<td><input type='checkbox' name='langs' value='Panjabi' />Panjabi</td>
						<td><input type='checkbox' name='langs' value='Papiamentu' />Papiamentu</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Pashto' />Pashto</td>
						<td><input type='checkbox' name='langs' value='Persian' />Persian</td>
						<td><input type='checkbox' name='langs' value='Pijin' />Pijin</td>
						<td><input type='checkbox' name='langs' value='Polish' />Polish</td>
						<td><input type='checkbox' name='langs' value='Portuguese' />Portuguese</td>
						<td><input type='checkbox' name='langs' value='Potawatomi' />Potawatomi</td>
						<td><input type='checkbox' name='langs' value='Quechua' />Quechua</td>
						<td><input type='checkbox' name='langs' value='Romanian' />Romanian</td>
						<td><input type='checkbox' name='langs' value='Russian' />Russian</td>
						<td><input type='checkbox' name='langs' value='Samoan' />Samoan</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Sango' />Sango</td>
						<td><input type='checkbox' name='langs' value='Scots Gaelic' />Scots Gaelic</td>
						<td><input type='checkbox' name='langs' value='Serbian' />Serbian</td>
						<td><input type='checkbox' name='langs' value='Shona' />Shona</td>
						<td><input type='checkbox' name='langs' value='Sinhala' />Sinhala</td>
						<td><input type='checkbox' name='langs' value='Slovak' />Slovak</td>
						<td><input type='checkbox' name='langs' value='Slovenian' />Slovenian</td>
						<td><input type='checkbox' name='langs' value='Somali' />Somali</td>
						<td><input type='checkbox' name='langs' value='Sotho Southern' />Sotho Southern</td>
						<td><input type='checkbox' name='langs' value='Spanish' />Spanish</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Sranan Tongo' />Sranan Tongo</td>
						<td><input type='checkbox' name='langs' value='Swahili' />Swahili</td>
						<td><input type='checkbox' name='langs' value='Swedish' />Swedish</td>
						<td><input type='checkbox' name='langs' value='Syriac (Aramaic)' />Syriac (Aramaic)</td>
						<td><input type='checkbox' name='langs' value='Tagalog' />Tagalog</td>
						<td><input type='checkbox' name='langs' value='Tajik' />Tajik</td>
						<td><input type='checkbox' name='langs' value='Tamashek (Tuareg)' />Tamashek (Tuareg)</td>
						<td><input type='checkbox' name='langs' value='Tamil' />Tamil</td>
						<td><input type='checkbox' name='langs' value='Telugu' />Telugu</td>
						<td><input type='checkbox' name='langs' value='Tetum' />Tetum</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Thai' />Thai</td>
						<td><input type='checkbox' name='langs' value='Tibetan' />Tibetan</td>
						<td><input type='checkbox' name='langs' value='Tigrinya' />Tigrinya</td>
						<td><input type='checkbox' name='langs' value='Tok Pisin' />Tok Pisin</td>
						<td><input type='checkbox' name='langs' value='Tokelauan' />Tokelauan</td>
						<td><input type='checkbox' name='langs' value='Tongan' />Tongan</td>
						<td><input type='checkbox' name='langs' value='Tswana' />Tswana</td>
						<td><input type='checkbox' name='langs' value='Turkish' />Turkish</td>
						<td><input type='checkbox' name='langs' value='Turkmen' />Turkmen</td>
						<td><input type='checkbox' name='langs' value='Tuvaluan' />Tuvaluan</td>
  					</tr>
  					<tr>
						<td><input type='checkbox' name='langs' value='Ukrainiazn' />Ukrainiazn</td>
						<td><input type='checkbox' name='langs' value='Uma' />Uma</td>
						<td><input type='checkbox' name='langs' value='Uzbek' />Uzbek</td>
						<td><input type='checkbox' name='langs' value='Vietnamese' />Vietnamese</td>
						<td><input type='checkbox' name='langs' value='Wallisian' />Wallisian</td>
						<td><input type='checkbox' name='langs' value='Welsh' />Welsh</td>
						<td><input type='checkbox' name='langs' value='Wolof' />Wolof</td>
						<td><input type='checkbox' name='langs' value='Xhosa' />Xhosa</td>
						<td><input type='checkbox' name='langs' value='Yiddish' />Yiddish</td>
						<td><input type='checkbox' name='langs' value='Zulu' />Zulu</td>
  					</tr> 
  				</tbody>
  			</table>
  		</p>
  </p>
  </div>
  <input type='submit' id='shortcode-generator' value='Generate Shortcode' onclick='generate_shortcode()'/><br><br>
  ";
}