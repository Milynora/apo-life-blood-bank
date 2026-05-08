<?php
namespace App\Enums;

enum EligibilityStatus: string
{
    case Fit   = 'fit';
    case Unfit = 'unfit';
}