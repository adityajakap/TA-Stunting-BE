<?php

namespace Tests\Feature\Orangtua\ImmunizationRecord;

use Tests\TestCase;

class ReadFailureGuestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Immunization feature removed');
    }

    public function test_placeholder()
    {
        $this->assertTrue(true);
    }
}
