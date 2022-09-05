<?php

declare(strict_types=1);

namespace Signalise\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Signalise\Config\Signalise;

class ApiClient extends Client
{
    private array $config;

    private const GET_CONNECTS_URI = '/api/v1/connects';

    public function __construct(
        array $config = [],
        Signalise $signalise
    ) {
        $config['headers'] = [
            'User-Agent' => 'SignaliseApiClient / PHP ' . phpversion(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Authorization' => $signalise->getApiKey()
        ];

        $config['base_uri'] = $signalise->getEndpoint();
        parent::__construct($config);
        $this->config = $config;
    }

    /**
     * @throws GuzzleException
     */
    public function getConnects(): ResponseInterface
    {
        return $this->get(self::GET_CONNECTS_URI);
    }

    public function pushData()
    {
        /** @ todo push data to Signalise */
    }
}