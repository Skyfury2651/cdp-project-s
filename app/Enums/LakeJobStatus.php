<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LakeJobStatus extends Enum
{
    const VALIDATING = 'validating';
    const VALIDATED_ERROR = 'validated_error';
    const VALIDATED_SUCCESS = 'validated_success';
    const PROCESSING = 'processing';
    const SUCCESS = 'success';
    const ERROR = 'error';
    const STOP = 'stop';
}
