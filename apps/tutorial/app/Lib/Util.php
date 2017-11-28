<?php
class Util {

    /**
     * センチメートルをHYDEに変換する
     * @param number $centimeter
     * @param number $precision
     * @return number
     */
    public static function convertToHYDE($centimeter = 0, $precision = 0) {
        if (!is_numeric($centimeter)) return 0;
        $hydes = round(($centimeter / ONE_HYDE), $precision);
        return $hydes;
    }
    
    public static function toUTF8($string) {
        return mb_convert_encoding($string, 'utf8', 'SJIS-win');
    }
}