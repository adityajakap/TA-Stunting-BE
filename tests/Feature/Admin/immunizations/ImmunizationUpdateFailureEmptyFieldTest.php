<?php

namespace Tests\Feature\Admin\immunizations;

use Tests\TestCase;

class ImmunizationUpdateFailureEmptyFieldTest extends TestCase
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
