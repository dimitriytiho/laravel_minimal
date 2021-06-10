<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        /*
         * Настроим крон на работу каждый час в 00 минуту.
         * По документации: * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
         * На сервере для php 7.4:
         * 1 * * * *
         * cd ~/site.ru/public_html && /usr/local/bin/php7.3 artisan schedule:run >> /dev/null 2>&1
         */

        // Если продакшин
        /*if (app()->environment() === 'production') {

            // Запустить Job каждые 5 минут
            $schedule->job(app()->make('\App\Jobs\TestJob'))->everyFiveMinutes();
        }*/

        // Резервное копирование веб-сайта (в первый день месяца)
        /*$schedule->command('backup:clean')->monthlyOn(1, '02:00');
        $schedule->command('backup:run')->monthlyOn(1, '03:00');*/

        // Запустить метод (в первый день месяца)
        /*$schedule->call(function () {
            // Указать метод
        })->monthlyOn(1, '04:00');*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
