<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\enum;

abstract class StatusCodes
{
    public const STATUS_OK                   = 200;
    public const STATUS_CREATED              = 201;
    public const STATUS_UNPROCESSABLE_ENTITY = 422;
}
