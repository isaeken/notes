<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Active()
 * @method static static Passive()
 * @method static static Edited()
 * @method static static Deleted()
 */
final class States extends Enum
{
    const Active    = 'active';
    const Passive   = 'passive';
    const Edited    = 'edited';
    const Deleted   = 'deleted';
}
