<?php
namespace App\Enums;

enum BloodUnitStatus: string
{
    case Available = 'available';
    case Reserved  = 'reserved';
    case Used      = 'used';
    case Expired   = 'expired';
}