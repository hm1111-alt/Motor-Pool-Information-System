<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleMaintenance;

class CheckVehicleMaintenanceTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-vehicle-maintenance-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking vehicle_maintenance table...');
        
        // Check if the table exists
        $tables = DB::select("SHOW TABLES LIKE 'vehicle_maintenance'");
        
        if (empty($tables)) {
            $this->error('Table vehicle_maintenance does not exist!');
            return 1;
        }
        
        $this->info('✓ Table vehicle_maintenance exists');
        
        // Check the model
        $model = new VehicleMaintenance();
        $tableName = $model->getTable();
        
        $this->info('Model table name: ' . $tableName);
        
        if ($tableName !== 'vehicle_maintenance') {
            $this->error('Model table name does not match! Expected: vehicle_maintenance, Got: ' . $tableName);
            return 1;
        }
        
        $this->info('✓ Model uses correct table name');
        
        // Try to count records
        try {
            $count = $model->count();
            $this->info('✓ Successfully connected to table. Record count: ' . $count);
        } catch (\Exception $e) {
            $this->error('Error connecting to table: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('✓ All checks passed!');
        return 0;
    }
}
