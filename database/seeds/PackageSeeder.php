<?php


use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;
use Hestia\MqttGateway\Service\CommandMapGenerator;
use Hestia\MqttGateway\Service\ImportRemoteCode;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;

class PackageSeeder extends Seeder
{
    public static function importGateway()
    {
        $json = file_get_contents(__DIR__.'/data/gateway/remote1.json');
        $json = json_decode($json, true);

        $gateway = new IRGateway($json);
        $gateway->save();
        $gatewayGUID = $json['GUID'];

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        $finder = Finder::create()->name('*.json');
        foreach($finder->in(__DIR__.'/data/devices') as $file){
            $json = file_get_contents($file->getRealPath());
            $json = json_decode($json, true);
            $json['Gateway'] = $gatewayGUID;

            unset($json['Commands']);
            $device = new IRDevice($json);
            $device->saveOrFail();

            $device = IRDevice::find($json['GUID']);
            $generator = new CommandMapGenerator();
            $generator->generate($device);
        }
    }

    public static function run()
    {
        $finder = Finder::create();
        $import = new ImportRemoteCode();

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach($finder->in(__DIR__.'/data/ir-codes') as $file){
            $import->fromJSON($file);
        }

        static::importGateway();

    }
}
