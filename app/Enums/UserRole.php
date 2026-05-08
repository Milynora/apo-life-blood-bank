<?php
namespace App\Enums;

enum UserRole: string
{
    case Admin    = 'admin';
    case Staff    = 'staff';
    case Donor    = 'donor';
    case Hospital = 'hospital';
}