<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Listing;
use App\Models\VerificationHistory;
use Illuminate\Support\Facades\DB;

class ResetUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all users except admin, removing all non-admin accounts and related data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will delete ALL users except admin. Are you sure?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Starting user reset process...');

        // Count users before reset
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $nonAdminUsers = $totalUsers - $adminUsers;

        $this->info("Found {$totalUsers} total users ({$adminUsers} admin, {$nonAdminUsers} non-admin)");

        if ($nonAdminUsers === 0) {
            $this->info('No non-admin users to delete.');
            return;
        }

        // Start transaction
        DB::beginTransaction();

        try {
            // Get non-admin users
            $usersToDelete = User::where('role', '!=', 'admin')->get();

            // Delete related data first
            foreach ($usersToDelete as $user) {
                // Delete verification histories
                VerificationHistory::where('user_id', $user->id)->delete();

                // Delete listings and related media
                $listings = Listing::where('user_id', $user->id)->get();
                foreach ($listings as $listing) {
                    // Delete media files if they exist
                    if ($listing->media) {
                        foreach ($listing->media as $media) {
                            // You might want to delete actual files here
                            // Storage::delete($media->path);
                        }
                    }
                    $listing->delete();
                }

                // Delete the user
                $user->delete();

                $this->line("Deleted user: {$user->email} ({$user->role})");
            }

            DB::commit();

            $this->info("Successfully deleted {$nonAdminUsers} non-admin users.");
            $this->info("Remaining users: " . User::count());

            // Ensure admin user exists
            $admin = User::where('email', 'admin@gmail.com')->first();
            if (!$admin) {
                $this->warn('Admin user not found! Running admin seeder...');
                $this->call('db:seed', ['--class' => 'AdminUserSeeder']);
            } else {
                $this->info('Admin user verified: ' . $admin->email);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error during reset: ' . $e->getMessage());
            return 1;
        }

        $this->info('User reset completed successfully.');
        return 0;
    }
}
