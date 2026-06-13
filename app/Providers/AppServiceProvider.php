<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connectors\PostgresConnector;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Chỉ áp dụng fix Neon SNI nếu host chứa "neon.tech"
        $host = env('DB_HOST', '');

        if (str_contains($host, 'neon.tech')) {
            $this->app->bind('db.connector.pgsql', function () {
                return new class extends PostgresConnector {
                    protected function getDsn(array $config): string
                    {
                        $dsn = parent::getDsn($config);
                        $endpointId = explode('.', $config['host'])[0];
                        return $dsn . ";options='endpoint=" . $endpointId . "'";
                    }
                };
            });
        }
    }

    public function boot()
    {
        //
    }
}