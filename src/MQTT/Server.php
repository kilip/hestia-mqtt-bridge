<?php


namespace Hestia\MqttGateway\MQTT;


use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class Server
{
    const STARTED = 0;
    const STOPPED = 1;

    private $pidFile;

    public function __construct(string $pidFile = null)
    {
        if(is_null($pidFile)){
            $pidFile = storage_path('mqtt-server.lck');
        }
        $this->pidFile = $pidFile;
    }

    public function isRunning()
    {
        return file_exists($this->pidFile);
    }

    public function getProcessID()
    {
        return file_get_contents($this->pidFile);
    }

    public function start()
    {
        if($this->isRunning()){
            throw new \RuntimeException(sprintf(
                'A process already run with process id: %s ',
                $this->getProcessID()
            ));
        }

        $pid = pcntl_fork();
        $pidFile = $this->pidFile;

        if ($pid < 0) {
            throw new \RuntimeException('Unable to start the server process.');
        }

        if ($pid > 0) {
            return self::STARTED;
        }

        if (posix_setsid() < 0) {
            throw new \RuntimeException('Unable to set the child process as session leader.');
        }


        $process = $this->createServerProcess();
        $process->disableOutput();
        $process->start();
        Log::info("Server Booted");
        if (!$process->isRunning()) {
            throw new \RuntimeException('Unable to start the server process.');
        }
        file_put_contents($pidFile, $process->getPid());
        Log::info('Process ID: '.$process->getPid());
        Log::info('PIDFile: '.$pidFile);

        // stop the web server when the lock file is removed
        while ($process->isRunning()) {
            if (!file_exists($pidFile)) {
                $process->stop(10, SIGTERM);
            }

            sleep(1);
        }
        return self::STOPPED;
    }

    public function stop()
    {
        if(!$this->isRunning()){
            return false;
        }
        unlink($this->pidFile);
    }

    private function createServerProcess(): Process
    {
        $finder = new PhpExecutableFinder();
        if (false === $binary = $finder->find(false)) {
            throw new \RuntimeException('Unable to find the PHP binary.');
        }

        $cwd = base_path();
        $artisan = base_path('artisan');
        if(!file_exists($artisan)){
            $artisan = __DIR__.'/../../artisan';
        }
        $cmd = array_merge_recursive([
            $binary,
            $artisan,
            'mqtt:run'
        ]);
        $process = new Process($cmd);

        $process->setWorkingDirectory($cwd);
        $process->setTimeout(null);

        return $process;
    }
}
