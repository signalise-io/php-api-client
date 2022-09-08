<?php

declare(strict_types=1);

namespace Signalise\PhpClient\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Signalise\PhpClient\Config\Signalise;

class ApiClient extends Client
{
    private Signalise $signalise;

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
        $this->signalise    = $signalise;
        parent::__construct($config);
    }

    /**
     * @throws GuzzleException
     */
    private function getConnects(): ResponseInterface
    {
        return $this->get(
            sprintf(
                '%s/%s',
                $this->signalise->getEndpoint(),
                $this->signalise->getConnects()
            )
        );
    }

    /**
     * @throws GuzzleException
     */
    public function pushData()
    {
        $getConnects = $this->getConnects()->getBody();
    }
}
