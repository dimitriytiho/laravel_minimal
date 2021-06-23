<?php


namespace App\Support\Admin;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class Commands
{
    /**
     *
     * @return string
     *
     * Запустить команду без консоли, возвращает ответ из консоли.
     * @param string $command - команда для терминала.
     */
    public static function getCommand($command)
    {
        if ($command) {
            try {
                /*Artisan::call($command);
                return __('a.completed_successfully');*/

                $command = 'cd ' . base_path() . " && {$command}";
                $process = Process::fromShellCommandline($command);
                //$process = new Process($command);
                $process->run();
                if (!$process->isSuccessful()) {
                    Log::error('Error in try ' . __METHOD__);
                    return __('s.whoops');
                }
                return $process->getOutput();

            } catch (\Exception $e) {
                Log::error("Error {$e->getMessage()}. Error in catch " . __METHOD__);
                return $e->getMessage();
            }
        }
        return null;
    }
}
