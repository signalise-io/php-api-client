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

    private const SIGNALISE_ENDPOINT           = 'https://signalise.io';
    private const SIGNALISE_GET_CONNECTS       = '/api/v1/connects';
    private const SIGNALISE_POST_ORDER_HISTORY = '/api/v1/connects/{{connectId}}/history';
    private const SIGNALISE_GET_HISTORY_STATUS = '/api/v1/connects/{{connectId}}/history/status';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    private function getHeaders(): array
    {
        return [
            'User-Agent' => 'ApiClient / PHP ' . phpversion(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Authorization' => $this->apiKey
        ];
    }

    private function createConnectIdUri(string $connectId, string $param): string
    {
        return sprintf(
            '%s/%s',
            rtrim(self::SIGNALISE_ENDPOINT),
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
            sprintf('%s/%s', rtrim(self::SIGNALISE_ENDPOINT, '/'), $call),
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
    public function getConnects(string $apiKey): array
    {
        $this->setApiKey($apiKey);

        $response = $this->get(self::SIGNALISE_GET_CONNECTS);

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws ResponseException|GuzzleException
     */
    public function getHistoryStatus(string $apiKey, string $connectId): array
    {
        $this->setApiKey($apiKey);

        $response = $this->get(
            $this->createConnectIdUri($connectId, self::SIGNALISE_GET_HISTORY_STATUS)
        );

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws ResponseException|GuzzleException
     */
    public function postOrderHistory(string $apiKey, string $serializedData, string $connectId): array
    {
        $this->setApiKey($apiKey);

        $response = $this->post($serializedData, $connectId);

        self::unableProcessResponse($response);

        return json_decode($response->getBody()->getContents(), true);
    }
}
