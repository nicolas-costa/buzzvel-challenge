<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getMockedJsonResponse(string $filePath): string
    {
        return file_get_contents(__DIR__ . '/Unit/mocked/' . $filePath);
    }
}
