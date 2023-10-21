<?php

namespace App\Helper;

class Helper
{
    public function compareArray($array1, $array2)
    {
        foreach ($array1 as $arr) {
            if (!in_array($arr, $array2)) {
                return false;
            }
        }
        return true;
    }
}
