<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Signalise\PhpClient\Exception\ResponseException;
use Signalise\PhpClient\Traits\FailedResponse;

class ApiClient
{
    use FailedResponse;

    private Client $client;

    private string $apiKey;

    private string $apiUrl;

    private const SIGNALISE_GET_CONNECTS       = 'api/v1/connects';
    private const SIGNALISE_POST_ORDER_HISTORY = 'api/v1/connects/{{connectId}}/history';
    private const SIGNALISE_GET_HISTORY_STATUS = 'api/v1/connects/{{connectId}}/history/status';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function setUp(string $apiUrl, string $apiKey): void
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    private function getHeaders(): array
    {
        return [
            'User-Agent' => 'ApiClient / PHP ' . phpversion(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $this->apiKey)
        ];
    }

    private function createConnectIdUri(string $connectId, string $param): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->apiUrl, '/'),
            str_replace('{{connectId}}', $connectId, $param)
        );
    }

    /**
     * @throws GuzzleException
     */
    private function get(string $call): ResponseInterface
    {
        return $this->client->request(
            'GET',
            sprintf(
                '%s/%s',
                rtrim($this->apiUrl, '/'),
                $call
            ),
            [
                'headers' => $this->getHeaders()
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    private function post(string $serializedData, string $connectId): ResponseInterface
    {
        return $this->client->request(
            'POST',
            $this->createConnectIdUri($connectId, self::SIGNALISE_POST_ORDER_HISTORY),
            [
                'headers' => $this->getHeaders(),
                'body' => $serializedData
            ]
        );
    }

    /**
     * @throws ResponseException|GuzzleException
     */
    public function getConnects(
        string $apiUrl,
        string $apiKey
    ): array {
        $this->setUp($apiUrl, $apiKey);

        $response = $this->get(self::SIGNALISE_GET_CONNECTS);

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws ResponseException|GuzzleException
     */
    public function getHistoryStatus(
        string $apiUrl,
        string $apiKey,
        string $connectId
    ): array {
        $this->setUp($apiUrl, $apiKey);

        $response = $this->get(
            $this->createConnectIdUri($connectId, self::SIGNALISE_GET_HISTORY_STATUS)
        );

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws ResponseException|GuzzleException
     */
    public function postOrderHistory(
        string $apiUrl,
        string $apiKey,
        string $serializedData,
        string $connectId
    ): array {
        $this->setUp($apiUrl, $apiKey);

        $response = $this->post($serializedData, $connectId);

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }
}
