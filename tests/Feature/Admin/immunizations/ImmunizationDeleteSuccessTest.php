<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class ImmunizationDeleteSuccessTest extends TestCase
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
