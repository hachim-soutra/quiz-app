<?php
namespace App\Enum;

enum PayementTypeEnum:string
{
    case FREE = 'free';
    case PAYED = 'paid';
    case NONAPPLICABLE = 'Non applicable';
}

?>
