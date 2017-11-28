<?php
class Util {
    
    public static function convertToHYDE($centimeter = 0, $precision = 0) {
        if (!is_numeric($centimeter)) return 0;
        $hydes = round(($centimeter / ONE_HYDE), $precision);
        return $hydes;
    }
}