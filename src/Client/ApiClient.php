<?php

declare(strict_types=1);

namespace Signalise\Client;

use GuzzleHttp\Client;
use Signalise\Config\Signalise;

class ApiClient extends Client
{
    public function __construct(
        array $config,
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
    }

    public function pushData()
    {
    }
}
