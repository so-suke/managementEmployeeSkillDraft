<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        return;
        // 実行クエリ
        \DB::listen(function ($query) {
            $sql = $query->sql;
            foreach ($query->bindings as $binding) {
                if (is_string($binding)) {
                    $binding = "'{$binding}'";
                } elseif ($binding === null) {
                    $binding = 'NULL';
                } elseif ($binding instanceof Carbon) {
                    $binding = "'{$binding->toDateTimeString()}'";
                }

                $sql = preg_replace("/\?/", $binding, $sql, 1);
            }
            \Log::debug($sql, ['time' => "{$query->time}ms"]);
        });

        // トランザクション開始・終了
        \Event::listen(TransactionBeginning::class, function ($event) {
            \Log::debug('START TRANSACTION');
        });

        \Event::listen(TransactionCommitted::class, function ($event) {
            \Log::debug('COMMIT');
        });

        \Event::listen(TransactionRolledBack::class, function ($event) {
            \Log::debug('ROLLBACK');
        });
    }
}
