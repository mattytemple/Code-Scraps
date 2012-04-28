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
		preg_match_all("/\[([a-zA-Z0-9-_ |=\.]+)\]/", $str, $matches);
		
		foreach ($matches as $key => $shortcode) {
			if(strstr($shortcode[0], " ")) {
				$code = substr($shortcode[0], 0, strpos($shortcode[0], " "));
				$code = str_replace("[", "", $code);
				$passed_data = str_replace("[".$code." ", "", $shortcode[0]);
				$passed_data = str_replace("]", "", $passed_data);
				$explode_passed_data = explode('|', $passed_data);
				$params = array();
				if(is_array($explode_passed_data)) {
					foreach ($explode_passed_data as $param) {
						$pair = explode("=", $param);
						$params[$pair[0]] = $pair[1];
					}
				} 
				$array = array("code" => $code, "params" => $params);
			} else { // if shortcode does not have a space it has no values
				$array = array("code" => $shortcode[0], "params" => array());	
			}
			$shortcodes_array[$matches[0][$key]] = $array;
		}
		foreach ($shortcodes_array as $key => $value) {
			$replace = $key;
			if(function_exists($value['code'])) {
				//$str = str_replace($str, $replace, $value['code']($value['params']));
				$new_text = $value['code']($value['params']);
				$str = str_replace($str, $key, $new_text);
			}				
		}
		return $str;
	}	
}
function world($args) {
	if($args['world id'] == '1') {
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