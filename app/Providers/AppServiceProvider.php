<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Runtime fallback: If Railway provides MYSQL_* variables, use them to override
        // the MySQL connection config at runtime. This ensures the app connects even
        // when a config cache was generated during the build without DB credentials.
        $mysqlHost = env('MYSQLHOST', env('MYSQL_HOST'));
        if ($mysqlHost) {
            $mysqlPort = env('MYSQLPORT', env('MYSQL_PORT', '3306'));
            $mysqlDatabase = env('MYSQLDATABASE', env('MYSQL_DATABASE'));
            $mysqlUser = env('MYSQLUSER', env('MYSQL_USER'));
            $mysqlPassword = env('MYSQLPASSWORD', env('MYSQL_PASSWORD'));
            $mysqlUrl = env('MYSQL_URL', env('DATABASE_URL'));

            if ($mysqlUrl && !env('DB_URL')) {
                config(['database.connections.mysql.url' => $mysqlUrl]);
            }

            config(['database.connections.mysql.host' => $mysqlHost]);
            config(['database.connections.mysql.port' => $mysqlPort]);

            if ($mysqlDatabase) {
                config(['database.connections.mysql.database' => $mysqlDatabase]);
            }
            if ($mysqlUser) {
                config(['database.connections.mysql.username' => $mysqlUser]);
            }
            if ($mysqlPassword) {
                config(['database.connections.mysql.password' => $mysqlPassword]);
            }
        }
    }
}
