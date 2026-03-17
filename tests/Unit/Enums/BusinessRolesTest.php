<?php

namespace Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Shared\Enums\BusinessRoles;

class BusinessRolesTest extends TestCase
{
    public function test_admin_has_correct_value(): void
    {
        $this->assertSame('admin', BusinessRoles::Admin->value);
    }

    public function test_lector_has_correct_value(): void
    {
        $this->assertSame('lector', BusinessRoles::Lector->value);
    }

    public function test_can_be_created_from_value(): void
    {
        $this->assertSame(BusinessRoles::Admin, BusinessRoles::from('admin'));
        $this->assertSame(BusinessRoles::Lector, BusinessRoles::from('lector'));
    }

    public function test_try_from_invalid_value_returns_null(): void
    {
        $this->assertNull(BusinessRoles::tryFrom('invalid'));
    }

    public function test_has_exactly_two_cases(): void
    {
        $this->assertCount(2, BusinessRoles::cases());
    }
}
