<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Member()
 * @method static static Banned()
 * @method static static Administrator()
 */
final class UserType extends Enum
{
    const Member        = 'member';
    const Banned        = 'banned';
    const Administrator = 'administrator';
}
