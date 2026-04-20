<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    protected $signature = 'user:make-admin
        {email : The email address of the user to promote}
        {--password= : If the user does not exist, create them with this password}
        {--name= : If the user does not exist, create them with this name}';

    protected $description = 'Promote an existing user to superadmin, or create a new admin if none exists.';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $password = $this->option('password') ?? $this->secret('Password for new admin');
            if (! $password) {
                $this->error('Password is required to create a new admin.');
                return self::FAILURE;
            }

            $user = User::create([
                'name' => $this->option('name') ?? strstr($email, '@', true),
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]);

            $this->info("Created new admin: {$user->email}");

            return self::SUCCESS;
        }

        if ($user->is_admin) {
            $this->comment("{$user->email} is already an admin.");
            return self::SUCCESS;
        }

        $user->forceFill(['is_admin' => true])->save();
        $this->info("Promoted {$user->email} to admin.");

        return self::SUCCESS;
    }
}
