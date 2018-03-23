
<?php
//From http://stackoverflow.com/a/25526473/1831783
require('TypedEnum.php');

final class RajdhaniPowerHashTagsEnum extends TypedEnum
{
    public static function None() { return self::_create(0); }
    public static function NC() { return self::_create(1); }    
    //public static function PayBill() { return self::_create(2); } 
}

?>