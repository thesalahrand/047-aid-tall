<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a single admin user if none exists. Throws an error otherwise.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (User::count() > 0) {
            $this->error('An admin user already exists. Aborting.');

            return Command::FAILURE;
        }

        $admin = User::create([
            'name' => 'Test Admin User',
            'email' => config('demo.admin.email'),
            'email_verified_at' => now(),
            'password' => Hash::make(config('demo.admin.password')),
        ]);

        $this->info("Admin user '{$admin->email}' created successfully.");

        return Command::SUCCESS;
    }
}
