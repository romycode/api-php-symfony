<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Serializer;

class EventNameMapper
{
    private const MAP = [];

    public function getEventClassName(string $type): string
    {
        if (key_exists($type, self::MAP)) {
            return self::MAP[$type];
        }

        return $type;
    }
}
