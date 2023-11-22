<?php

declare(strict_types=1);

namespace Tests;

use Gajus\Dindent\Indenter;
use Front-app-merger\Front-app-merger\Front-app-mergerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            Front-app-mergerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
