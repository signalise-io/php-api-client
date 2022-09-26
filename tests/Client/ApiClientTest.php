<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Test\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Signalise\PhpClient\Client\ApiClient;
use Signalise\PhpClient\Exception\ResponseException;

/**
 * @coversDefaultClass \Signalise\PhpClient\Client\ApiClient
 */
class ApiClientTest extends TestCase
{
    /**
     * @return void
     *
     * @throws GuzzleException
     * @throws ResponseException
     * @covers ::getConnects
     */
    public function testGetConnects(): void
    {
        $subject = new ApiClient(
            $this->createClientMock()
        );

        $subject->getConnects('4232433727');
    }

    private function createClientMock(): Client
    {
        return $this->createMock(Client::class);
    }
}
