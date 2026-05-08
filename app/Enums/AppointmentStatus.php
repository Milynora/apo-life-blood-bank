<?php
namespace App\Enums;

enum AppointmentStatus: string
{
    case Pending   = 'pending';
    case Approved  = 'approved';
    case Rejected  = 'rejected';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case NoShow    = 'no_show';
}