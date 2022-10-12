<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Traits;

use Psr\Http\Message\ResponseInterface;
use Signalise\PhpClient\enum\StatusCodes;
use Signalise\PhpClient\Exception\ResponseException;

trait FailedResponse
{
    /**
     * @throws ResponseException
     */
    public function unableProcessResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== StatusCodes::STATUS_CREATED) {
            throw new ResponseException(
                sprintf('Unable to process response: %s', $response->getReasonPhrase())
            );
        }
    }
}
