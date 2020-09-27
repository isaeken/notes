<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Emergency()
 * @method static static Alert()
 * @method static static Critical()
 * @method static static Error()
 * @method static static Warning()
 * @method static static Notice()
 * @method static static Info()
 * @method static static Debug()
 */
final class LogLevel extends Enum
{
    const Emergency = 0;
    const Alert = 1;
    const Critical = 2;
    const Error = 3;
    const Warning = 4;
    const Notice = 5;
    const Info = 6;
    const Debug = 7;
}
