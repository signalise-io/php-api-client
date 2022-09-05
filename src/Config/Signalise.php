<?php

declare(strict_types=1);

namespace Signalise\Config;

use Dotenv\Dotenv;

class Signalise
{
    private function loadConfig(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
    }

    public function getApiKey(): string
    {
        $this->loadConfig();
        return $_ENV['SIGNALISE_API_KEY'];
    }

    public function getEndpoint(): string
    {
        $this->loadConfig();
        return $_ENV['SIGNALISE_ENDPOINT'];
    }
}