<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SyncTraderPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:sync-trader-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions for the trader role';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = Role::findByName('trader');
        $permissions = Permission::whereIn('name', ['create_request', 'apply_request'])->get();
        $role->syncPermissions($permissions);

        $this->info('Permissions synced for trader role');
    }
}
