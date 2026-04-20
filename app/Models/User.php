<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'provider_name', 'provider_id', 'avatar', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function twoFactorRecentlyVerified(): bool
    {
        return $this->two_factor_verified_at
            && $this->two_factor_verified_at->greaterThan(now()->subDays(30));
    }

    public function syncAdminFromConfig(): void
    {
        $shouldBeAdmin = in_array(strtolower((string) $this->email), config('auth.admin_emails', []), true);

        if ($this->is_admin !== $shouldBeAdmin) {
            $this->forceFill(['is_admin' => $shouldBeAdmin])->save();
        }
    }
}
