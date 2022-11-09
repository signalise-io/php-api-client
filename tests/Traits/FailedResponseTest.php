<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Test\Traits;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Signalise\PhpClient\enum\StatusCodes;
use Signalise\PhpClient\Exception\ResponseException;
use Signalise\PhpClient\Traits\FailedResponse;

/**
 * @coversDefaultClass \Signalise\PhpClient\Traits\FailedResponse
 */
class FailedResponseTest extends TestCase
{
    /**
     * @param int $statusCode
     *
     * @return void
     *
     * @covers ::unableProcessResponse
     * @dataProvider setDataProvider
     */
    public function testUnableProcessResponse(int $statusCode): void
    {
        $subject = $this->getObjectForTrait(FailedResponse::class);

        if ($statusCode !== StatusCodes::STATUS_CREATED && $statusCode !== StatusCodes::STATUS_OK) {
            self::expectException(ResponseException::class);
        }

        $subject->unableProcessResponse(
            $this->createResponseInterfaceMock($statusCode)
        );
    }

    private function createResponseInterfaceMock(int $statusCode): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects(self::atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(
                $statusCode
            );

        return $response;
    }

    public function setDataProvider(): array
    {
        return [
            'valid_1' => [
                'statusCode' => StatusCodes::STATUS_OK
            ],
            'valid_2' => [
                'statusCode' => StatusCodes::STATUS_CREATED
            ],
            'invalid' => [
                'statusCode' => StatusCodes::STATUS_UNPROCESSABLE_ENTITY
            ]
        ];
    }
}
