<?php
namespace App\Enums;

enum RequestStatus: string
{
    case Pending             = 'pending';
    case Approved            = 'approved';
    case Rejected            = 'rejected';
    case Cancelled           = 'cancelled';
    case PartiallyFulfilled  = 'partially_fulfilled';
    case Fulfilled           = 'fulfilled';
}