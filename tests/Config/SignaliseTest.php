<?php

/**
 * Copyright Elgentos BV. All rights reserved.
 * https://www.elgentos.nl/
 */

declare(strict_types=1);

namespace Signalise\PhpClient\Test\Config;

use PHPUnit\Framework\TestCase;
use Signalise\PhpClient\Config\Signalise;

/**
 * @coversDefaultClass \Signalise\PhpClient\Config\Signalise
 */
class SignaliseTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getApiKey
     */
    public function testGetApiKey(): void
    {
        $subject = new Signalise();

        $this->assertIsString(
            $subject->getApiKey()
        );
    }

    /**
     * @return void
     *
     * @covers ::getEndpoint
     */
    public function testGetEndpoint(): void
    {
        $subject = new Signalise();

        $this->assertIsString(
            $subject->getEndpoint()
        );
    }

    /**
     * @return void
     *
     * @covers ::getConnects
     */
    public function testGetConnects(): void
    {
        $subject = new Signalise();

        $this->assertIsString(
            $subject->getConnects()
        );
    }
}
