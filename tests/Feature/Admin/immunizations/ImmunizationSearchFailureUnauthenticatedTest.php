<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImmunizationSearchFailureUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

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
