<?php

namespace Tests\Feature\Orangtua;

use Tests\TestCase;

class UpdateImmunizationRecordTest extends TestCase
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
