<?php


namespace Hestia\MqttGateway;


class Events
{
    const STARTUP    = 'hestia.mqtt.ir.event.startup';
    const SHUTDOWN   = 'hestia.mqtt.ir.event.shutdown';
    const CONNECT    = 'hestia.mqtt.ir.event.connect';

    const MESSAGE_RECEIVED = 'hestia.mqtt.ir.event.message_received';

    const PUBLISH_MESSAGE = 'hestia.mqtt.ir.event.publish.message';
    const PUBLISH_DEVICE_STATE = 'hestia.mqtt.ir.event.publish_device_state';
}
