<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupAdmin extends Command
{
    protected $signature = 'app:setup-admin';
    protected $description = 'Set up admin role and assign to first user';

    public function handle()
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Get the first user
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in the database.');
            return 1;
        }

        // Assign admin role to the user
        $user->assignRole('admin');
        
        $this->info("Successfully assigned 'admin' role to user: {$user->email}");
        return 0;
    }
}
