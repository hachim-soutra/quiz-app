<?php

namespace App\Helper;

class Helper
{
    public function compareArray($array1, $array2)
    {
        array_multisort($array1);
        array_multisort($array2);
        return serialize($array1) === serialize($array2);
    }
}
