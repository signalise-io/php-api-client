<?php

declare(strict_types=1);

namespace Signalise\PhpClient\Config;

use Dotenv\Dotenv;

class Signalise
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    public function getApiKey(): string
    {
        return $_ENV['SIGNALISE_API_KEY'];
    }

    public function getEndpoint(): string
    {
        return $_ENV['SIGNALISE_ENDPOINT'];
    }

    public function getConnects(): string
    {
        return $_ENV['SIGNALISE_GET_CONNECTS'];
    }
}
