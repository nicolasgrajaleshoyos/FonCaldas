<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Los correos se envían con defer() (tras responder); en las pruebas se ejecutan al instante.
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutDefer();
    }
}
