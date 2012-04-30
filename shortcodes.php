<?php
class shortcodes {
	function __construct() {
		
	}
	
	function do_shortcodes($str) {
		// if no shortcodes are found return string
		if(!strstr($str, "[")) {
			return $str;
		}
		
		// process shortcodes
		// [code var1=1|var2=2]
		preg_match_all("/\[([a-zA-Z0-9_=-|\s]*?)\]/", $str, $matches);
		//echo '<pre>'.print_r($matches, TRUE).'</pre>';
		foreach ($matches as $key => $shortcode) {
			if(!strstr($shortcode[0], "[")) {
				if(strstr($shortcode[0], " ")) {
					$code = substr($shortcode[0], 0, strpos($shortcode[0], " "));
					$passed_data = str_replace($code." ", "", $shortcode[0]);
					$explode_passed_data = explode('|', $passed_data);
					$params = array();
					if(is_array($explode_passed_data)) {
						foreach ($explode_passed_data as $param) {
							$pair = explode("=", $param);
							$params[$pair[0]] = $pair[1];
						}
					} 
					$array = array("key" => "[".$code." ".$passed_data."]","code" => $code, "params" => $params);
				} else { // if shortcode does not have a space it has no values
					$array = array("key" => "[".$code."]", "code" => $shortcode[0], "params" => array());	
				}
				$shortcodes_array[$matches[0][$shortcode[0]]] = $array;
			}
		}
		//echo '<pre>'.print_r($shortcodes_array, TRUE).'</pre>';
		foreach ($shortcodes_array as $key => $value) {
			if(function_exists($value['code'])) {
				//$str = str_replace($str, $replace, $value['code']($value['params']));
				$new_text = $value['code']($value['params']);
				$str = str_replace($value['key'], $new_text, $str);
			}				
		}
		return $str;
	}	
}
function world($args) {
	if($args['id'] == '1') {
		if($args['type'] == '1') {
			return 'World';
		} else {
			return 'moon';
		}
	} else {
		return 'nothing found for the ID '.$args['id'];
	}
}
$shortcodes = new shortcodes;
echo $shortcodes->do_shortcodes("Hello [world id=1|type=1]");

?>