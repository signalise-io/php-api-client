<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Traits;

use _PHPStan_3bfe2e67c\Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Signalise\PhpClient\Exception\ResponseException;

trait FailedResponse
{
    /**
     * @throws ResponseException
     */
    public function unableProcessResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== StatusCodeInterface::STATUS_CREATED) {
            throw new ResponseException(
                sprintf('Unable to process response: %s', $response->getReasonPhrase())
            );
        }
    }
}
