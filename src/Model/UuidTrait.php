<?php

declare(strict_types=1);

namespace Hestia\MqttGateway\Model;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if(is_null($model->GUID)){
                $model->GUID = Uuid::uuid4()->toString();
            }
        });
    }
}
