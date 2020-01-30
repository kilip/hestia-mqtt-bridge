<?php


namespace Hestia\MqttGateway;


class Events
{
    const STARTUP    = 'hestia.mqtt.ir.event.startup';
    const SHUTDOWN   = 'hestia.mqtt.ir.event.shutdown';
    const CONNECT    = 'hestia.mqtt.ir.event.connect';

    const MESSAGE_RECEIVED = 'hestia.mqtt.ir.event.message_received';
}
