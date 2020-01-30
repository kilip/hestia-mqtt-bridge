<?php


namespace Hestia\MqttGateway;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\Connection;
use BinSoul\Net\Mqtt\DefaultConnection;
use Hestia\MqttGateway\Command\MqttInstallServiceCommand;
use Hestia\MqttGateway\Command\MqttRunCommand;
use Hestia\MqttGateway\Command\MqttStartCommand;
use Hestia\MqttGateway\Command\MqttStopCommand;
use Hestia\MqttGateway\Listeners\MqttTopicListener;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use React\Dns\Resolver\Factory as DnsResolverFactory;
use React\EventLoop\Factory as EventLoopFactory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectorInterface;
use React\Socket\DnsConnector;
use React\Socket\TcpConnector;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->singleton(LoopInterface::class, function($app){
            return EventLoopFactory::create();
        });

        $app->singleton(ConnectorInterface::class, function($app){
            /* @var Application $app */
            /* @var LoopInterface $loop */
            $dns = env('MQTT_DNS');
            $loop = $app->make(LoopInterface::class);
            $connector = new TcpConnector($loop);
            if(!is_null($dns)){
                $resolver = new DnsResolverFactory();
                $cached = $resolver->createCached($dns, $loop);
                $connector = new DnsConnector($connector,$cached);
            }
            return $connector;
        });

        $app->singleton(Connection::class, function($app){
            $conn = new DefaultConnection(
                env('MQTT_USERNAME'),
                env('MQTT_PASSWORD')
            );
            return $conn;
        });

        $app->singleton(ReactMqttClient::class, function($app){
            return new ReactMqttClient(
                $app->make(ConnectorInterface::class),
                $app->make(LoopInterface::class)
            );
        });

        Event::listen(Events::MESSAGE_RECEIVED, MqttTopicListener::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');

        if($this->app->runningInConsole()){
            $this->commands([
                MqttStartCommand::class,
                MqttRunCommand::class,
                MqttStopCommand::class,
                MqttInstallServiceCommand::class,
            ]);
        }
    }
}
