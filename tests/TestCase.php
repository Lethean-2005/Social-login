<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    /**
     * Authenticate a user and mark the two-factor step as already passed.
     */
    protected function actingAsWithTwoFactor(Authenticatable $user, ?string $guard = null): static
    {
        return $this->actingAs($user, $guard)->withSession(['two_factor.verified' => true]);
    }
}
