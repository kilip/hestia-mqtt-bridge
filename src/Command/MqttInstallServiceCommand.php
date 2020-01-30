<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\Events;
use Hestia\MqttGateway\MQTT\Client;
use Illuminate\Console\Command;
use React\EventLoop\LoopInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class MqttInstallServiceCommand extends Command
{
    protected $signature = 'mqtt:install:service';

    protected $description = 'Install MQTT Client Service';

    public function handle()
    {
        $phpFinder = new PhpExecutableFinder();
        $php = $phpFinder->find();
        $artisan = realpath(__DIR__.'/../../artisan');
        $pid = env('MQTT_SERVICE_PID');
        $execStop = sprintf('%s %s %s --pid-file=%s', $php, $artisan,'mqtt:stop', $pid);
        $execStart = sprintf('%s %s %s --pid-file=%s',$php, $artisan,'mqtt:start', $pid);
        $template = file_get_contents(__DIR__.'/../../etc/hestia-mqtt.service');

        $contents = strtr($template,[
            '{{exec_start}}' => $execStart,
            '{{exec_stop}}' => $execStop,
            '{{pid}}' => $pid
        ]);

        $target = '/etc/systemd/system/hestia-mqtt.service';
        $installed = false;

        if(file_exists($target)){
            $installed = true;
        }

        file_put_contents($target, $contents);
        $this->execSystemd([
            'systemctl',
            'daemon-reload'
        ]);

        if(!$installed){
            $this->execSystemd([
                'systemctl',
                'enable',
                'hestia-mqtt'
            ]);
        }

    }

    private function execSystemd(array $cmnd)
    {
        $process = new Process($cmnd);
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo "\nRead from stdout: ".$data;
            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        }
    }
}
