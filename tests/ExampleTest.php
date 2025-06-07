<?php

// A basic test file to ensure PHPUnit runs correctly.

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test.
     * This test simply asserts that true is indeed true,
     * which will always pass and make the CI pipeline green.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}