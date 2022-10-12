<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Test\Client;

use Signalise\PhpClient\enum\StatusCodes;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionException;
use ReflectionMethod;
use Signalise\PhpClient\Client\ApiClient;
use Signalise\PhpClient\Exception\ResponseException;

/**
 * @coversDefaultClass \Signalise\PhpClient\Client\ApiClient
 */
class ApiClientTest extends TestCase
{
    private const SIGNALISE_POST_ORDER_HISTORY = 'api/v1/connects/{{connectId}}/history';

    /**
     * @return void
     *
     * @throws ResponseException|GuzzleException
     * @covers ::__construct
     * @covers ::getConnects
     */
    public function testGetConnects(): void
    {
        $subject = new ApiClient(
            $this->createClientMock('getConnects')
        );

        $subject->getConnects('4232433727');
    }

    /**
     *
     * @throws ResponseException|GuzzleException
     * @covers ::__construct
     * @covers ::getHistoryStatus
     */
    public function testGetHistoryStatus(): void
    {
        $subject = new ApiClient(
            $this->createClientMock('getHistoryStatus')
        );

        $subject->getHistoryStatus('438243282382', '43828388223');
    }

    /**
     *
     * @throws ResponseException|GuzzleException|ReflectionException
     * @covers ::__construct
     * @covers ::postOrderHistory
     * @covers ::post
     * @covers ::setApiKey
     * @covers ::getHeaders
     * @covers ::createConnectIdUri
     *
     * @dataProvider setDataProvider
     */
    public function testPostOrderHistory(
        string $data,
        string $message,
        int $statusCode,
        string $apiKey,
        string $connectId
    ): void {
        $subject = new ApiClient(
            $this->createPostClientMock($apiKey, $data, $message, $statusCode, $connectId)
        );

        if ($statusCode !== StatusCodes::STATUS_CREATED) {
            self::expectException(ResponseException::class);
        }

        $subject->postOrderHistory($apiKey, $data, $connectId);
    }

    /**
     * @throws ReflectionException
     */
    private function callPrivateFunction($subject, string $method): ReflectionMethod
    {
        $reflectionMethod = new ReflectionMethod($subject, $method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }

    private function createClientMock(string $call): Client
    {
        $client = $this->createMock(Client::class);

        $client->expects(self::atLeastOnce())
            ->method('request')
            ->willReturn(
                $this->createResponseInterfaceMock($call)
            );

        return $client;
    }

    private function createResponseInterfaceMock(string $call): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects(self::atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(
                StatusCodes::STATUS_CREATED
            );

        $response->expects(self::atLeastOnce())
            ->method('getBody')
            ->willReturn(
                $this->createStreamInterfaceMock($call)
            );

        return $response;
    }

    private function createStreamInterfaceMock(string $call): StreamInterface
    {
        $streamInterface = $this->createMock(StreamInterface::class);

        $streamInterface->expects(
            self::atLeastOnce()
        )
            ->method('getContents')
            ->willReturn(
                $this->returnResponseValue($call)
            );

        return $streamInterface;
    }

    /**
     * @throws ReflectionException
     */
    private function createPostClientMock(
        string $apiKey,
        string $data,
        string $message,
        int $statusCode,
        string $connectId
    ): Client {
        $client = $this->createMock(Client::class);

        $subject = $this->createMock(ApiClient::class);

        $this->callPrivateFunction($subject, 'setApiKey')
            ->invoke($subject, $apiKey);

        $client->expects(self::atLeastOnce())
            ->method('request')
            ->with(
                'POST',
                $this->callPrivateFunction($subject, 'createConnectIdUri')
                    ->invoke($subject, $connectId, self::SIGNALISE_POST_ORDER_HISTORY),
                [
                    'headers' => $this->callPrivateFunction($subject, 'getHeaders')
                        ->invoke($subject),
                    'body' => $data
                ]
            )
            ->willReturn(
                $this->createPostResponseInterfaceMock($message, $statusCode)
            );

        return $client;
    }

    private function createPostResponseInterfaceMock(
        string $message,
        int $statusCode
    ): ResponseInterface {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(
                $statusCode
            );

        $response->expects(
            $statusCode !== StatusCodes::STATUS_CREATED ? self::never() : self::once()
        )
            ->method('getBody')
            ->willReturn(
                $this->createPostStreamInterfaceMock($message, $statusCode)
            );

        return $response;
    }

    private function createPostStreamInterfaceMock(
        string $message,
        int $statusCode
    ): StreamInterface {
        $streamInterface = $this->createMock(StreamInterface::class);

        $streamInterface->expects(
            $statusCode !== StatusCodes::STATUS_CREATED ? self::never() : self::once()
        )
            ->method('getContents')
            ->willReturn(
                $message
            );

        return $streamInterface;
    }

    private function returnResponseValue(string $call): string
    {
        $returnValue = [
            'getConnects' => '[
                {"id": "{{1234-5678-9012}}", "name":"shop_1"},
                {"id": "{{9012-1234-5678}", "name":"shop_2"}
            ]',
            'getHistoryStatus' => '{
                    "last_post_date": "2021-02-18 16:00:04",
                    "last_order_id": 18
                }'
        ];

        return $returnValue[$call];
    }

    public function setDataProvider(): array
    {
        return [
            'successful' => [
                'data' => '{
                    "records": [
                        {
                            "id": 16,
                            "total_products": 25,
                            "total_costs": 124.6500,
                            "valuta": "EUR",
                            "tax": 1.15,
                            "payment_method": "mollie_methods_ideal",
                            "payment_costs": 0.05,
                            "shipping_method": "Flat Rate - Fixed",
                            "shipping_costs": 5.0000,
                            "zip": "1000AA",
                            "street": "Dam",
                            "house_number": "1",
                            "city": "Amsterdam",
                            "country": "NL",
                            "status": "complete",
                            "date": "2021-02-11 18:24:45",
                            "tag": ""
                        }
                    ]
                }',
                'message' => '{ "message": "processed: 1 records" }',
                'statusCode' => StatusCodes::STATUS_CREATED,
                'apiKey' => '43224352',
                'connectId' => '7e618144-3e5f-11ed-b878-0242ac120002'
            ],
            'failed' => [
                'data' => 'unprocessable entry',
                'message' => '{ "message": "Error while uploading" }',
                'statusCode' => StatusCodes::STATUS_UNPROCESSABLE_ENTITY,
                'apiKey' => '23526382',
                'connectId' => '928a61d6-3e5f-11ed-b878-0242ac120002'
            ]
        ];
    }
}
