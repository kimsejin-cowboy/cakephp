<?php
class Util {

    public static function convertToHYDE($centimeter = 0, $precision = 0) {
        if (!is_numeric($centimeter)) return 0;
        $hydes = round(($centimeter / ONE_HYDE), $precision);
        return $hydes;
    }

    /**
     * 配列を文字列に変換する
     * @param unknown $array
     * @param string $separator
     * @param string $withKey
     */
    public static function arrayToString($array, $separator = '<br />', $withKey = false) {
    	$str = array();
    	if (!is_array($array)) $array = array($array);
    	foreach ($array as $key => $val) {
    		if (is_array($val)) {
    			$str[] = ($withKey ? ($key . ' => ') : '') . self::arrayToString($val, $separator, $withKey);
    		} else {
    			$str[] = ($withKey ? ($key . ' => ') : '') . $val;
    		}
    	}
    	return implode($separator, $str);
    }
}