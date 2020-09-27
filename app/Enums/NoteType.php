<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Public()
 * @method static static Archive()
 * @method static static Private()
 */
final class NoteType extends Enum
{
    const Public    = 'public';
    const Archive   = 'archive';
    const Private   = 'private';
}
