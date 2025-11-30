<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Purok;
use App\Models\CertificateRequest;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Using a view composer to share data with specific views
        View::composer('layouts.app', function ($view) {
            // Initialize all variables with default values
            $pendingCounts = [
                'pendingPuroks' => 0,
                'pendingCertificates' => 0,
                'pendingHealthRecords' => 0,
                'pendingMaternalHealth' => 0,
                'pendingChildHealth' => 0,
                'pendingSeniorHealth' => 0,
                'pendingPwdSupport' => 0,
                'pendingDiseaseMonitoring' => 0,
                'pendingHealthAssistance' => 0,
                'pendingGovtAssistance' => 0,
            ];

            // Define the table names and their corresponding model classes
            $tables = [
                'puroks' => ['model' => Purok::class, 'column' => 'status'],
                'certificate_requests' => ['model' => CertificateRequest::class, 'column' => 'status'],
                'health_records' => ['model' => 'App\\Models\\HealthRecord', 'column' => 'status'],
                'maternal_health' => ['model' => 'App\\Models\\MaternalHealth', 'column' => 'status', 'table' => 'maternal_health'],
                'child_health' => ['model' => 'App\\Models\\ChildHealth', 'column' => 'status'],
                'senior_health' => ['model' => 'App\\Models\\SeniorHealth', 'column' => 'status'],
                'pwd_support' => ['model' => 'App\\Models\\PwdSupport', 'column' => 'status'],
                'disease_monitoring' => ['model' => 'App\\Models\\DiseaseMonitoring', 'column' => 'status'],
                'health_assistance' => ['model' => 'App\\Models\\HealthAssistance', 'column' => 'status'],
                'government_assistance' => ['model' => 'App\\Models\\GovernmentAssistance', 'column' => 'status'],
            ];

            foreach ($tables as $table => $config) {
                $varName = 'pending' . str_replace('_', '', ucwords($table, '_'));
                
                try {
                    // Use the table name from config or the key as table name
                    $tableName = $config['table'] ?? $table;
                    
                    if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, $config['column'])) {
                        $model = is_string($config['model']) ? app($config['model']) : $config['model'];
                        $pendingCounts[$varName] = $model->where($config['column'], 'pending')->count();
                    }
                } catch (\Exception $e) {
                    // Log the error but don't break the application
                    \Log::warning("Error checking pending count for {$table}: " . $e->getMessage());
                    continue;
                }
            }
            
            // Share all variables with the view
            $view->with($pendingCounts);
        });
    }
}
