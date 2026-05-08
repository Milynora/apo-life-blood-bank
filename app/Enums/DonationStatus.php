<?php
namespace App\Enums;

enum DonationStatus: string
{
    case Successful = 'successful';
    case Failed     = 'failed';
}