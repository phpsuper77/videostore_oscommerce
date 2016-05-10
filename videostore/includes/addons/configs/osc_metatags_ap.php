<?php
	define('FILENAME_OSC_METATAGS_REDIRECT', 'osc_metatags_ap.php');
	define('FILENAME_OSC_METATAGS', 'addons/'.FILENAME_OSC_METATAGS_REDIRECT);
	define('TABLE_OSC_METATAGS_PREFIX', 'osc_');
	define('TABLE_OSC_METATAGS', TABLE_OSC_METATAGS_PREFIX.'metatags');

	define('BOX_TOOLS_OSC_METATAGS', 'OSC:<br>&nbsp;&nbsp;&nbsp;Meta Tags Auto-Update Tool');


	function table_exists($table) { 
		return mysql_num_rows( mysql_query("SHOW TABLES LIKE '".$table."'"));
	}
	
	function checkField($tableName,$columnName){
		//Global variable, $db for database name, these variable are common in whole application so stored in a global variable, alternatively you can pass this as parameters of function.
		global $db;

		//Getting table fields through mysql built in function, passing db name and table name
		$tableFields = mysql_list_fields(DB_DATABASE, $tableName);

		//loop to traverse tableFields result set
		for($i=0;$i<mysql_num_fields($tableFields);$i++){

			//Using mysql_field_name function to compare with column name passed. If they are same function returns 1
			if(mysql_field_name($tableFields, $i)==$columnName)
				return 1;
		} //end of loop
	} //end of function 
	
	if(!function_exists('endsWith')){
		function endsWith($string, $char)
		{
			$length = strlen($char);
			$start =  $length *-1; //negative
			return (substr($string, $start, $length) === $char);
		}
	}
	function remove_html_garbage($string) {
		return $string;
	}

	function clear_junk($string){
		$replace_strings = array(
		'
	',
		'
',
		// ' ',
		'%0a',
		'%0d',
		'2&quot;',
		'8&quot;',
		',',
		'.',
		'	',
		'\n',
		'\r'
		);
		// print_r($replace_strings);
		// exit;
		$i=0;
		while(isset($replace_strings[$i])){
			$string = str_replace($replace_strings[$i],"",$string);
			$i++;
		}
		return $string;
	} 
	function osc_format_string($string,$extra=""){
		$string = clear_common_words(
			trim_non_alphanum(
				remove_html_garbage(
					clear_junk(
						strip_tags(
							trim(
								html_entity_decode(
									$string
								)
							)
						)
					)
				)
			,$extra)
		,$extra);

		
		while(($string>0) && (!preg_match("/[^".$extra."\$\%\/0-9A-Za-z]/",substr($string, -1, 1)))){
			$string = substr($string, 0, -1);
		}
		if( endsWith($string, ", ") ) {
			$string = substr($string, 0, -2);
		}
		return $string;
	}
	function trim_non_alphanum($string,$extra=""){
		$string = preg_replace('/\s+/', ' ', $string);
		while((!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",substr($string, 1, 1))) && ($string != "")){
			$string = substr($string, 2, (strlen($string)-1));
		}
		while((!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",substr($string, -1, 1))) && ($string != "")){
			$string = substr($string, 0, -1);
		}
		return $string;
	}
	function strip_non_alphanum($string,$extra=""){
		$string = preg_replace('/\s+/', ' ', $string);
		if (isset($string[0])){
			// echo substr($string, 0, 1)."<br>";
			while((!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",substr($string, 0, 1))) && ($string != "")){
				$string = substr($string, 1, (strlen($string)-1));
			}
			while((!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",substr($string, -1, 1))) && ($string != "")){
				$string = substr($string, 0, -1);
			}
			if (strlen($string) > 0){
				$string2 = array();
				$iii = 0;
				while(isset($string[$iii])){
					$string2[] = $string[$iii];
					$iii++;
				}
				$temp_string = "";
				foreach($string2 as $key => $value){
					if(preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",$value)){
						$temp_string .= $value;
					}else
						$temp_string .= " ";
				}
				$string = $temp_string;
			}
			if (strlen($string) > 0){
				// Why do these fail? Empty string more then likely, we did our job.
				if(!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",$string[0])){
					echo "fail: \"".$string[0]."\"<br>";
					return "";
				}
				if(!preg_match("/[".$extra."\$\%\/0-9A-Za-z]/",substr($string, -1, 1))){
					echo "fail2: \"".substr($string, -1, 1)."\"<br>";
					return "";				
				}
				return $string;
			}
			return "";
		}
		return "";
	}
	// clear common words
	function clear_common_words($sentence,$extra) {
		static $words;
		$sentence = strtolower($sentence);
	  
		if (!isset($words)){
			// $words = file(dirname(__FILE__) . '/stopwords.inc');
		}
		$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
		$stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as', 'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
		$stopwords2 = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
		$words = array(
			'in', 
			'the', 
			'this', 
			'us',
			'has',
			'is', 
			'for',
			'on', 
			'&nbsp;', 
			'and', 
			'wherever', 
			'not', 
			'you',
			'with',
			'click',
			'it’s');
		$words = array_merge($words,$commonWords);
		$words = array_merge($words,$stopwords);
		$words = array_merge($words,$stopwords2);
		$sentence = strip_non_alphanum($sentence,$extra);
		$sen_words = explode(" ", $sentence);
	  
		$sword = "";
		foreach( $sen_words as $aa ) {
			
			$aa = trim(strip_non_alphanum($aa,$extra));
			// don't allow words on the filter list
			// don't allow words less than 3 characters long
			// don't allow duplicates

			if( !in_array($aa, $words) && strlen($aa) > 2 && !isset($repeat_word[$aa]) ) { 
				// echo '"'.$aa.'"<br>';
				$sword .= $aa . OSC_METATAG_SEPERATOR;
				$repeat_word[$aa] = 1;
			}
		}
		return $sword;
	}	
?>