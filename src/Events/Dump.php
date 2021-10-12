<?php

declare(strict_types=1);

namespace RonAppleton\Discover\Events;

use Composer\Script\Event;
use JsonException;
use RonAppleton\Discover\Helpers\Manager;

/**
 * Class Dump
 *
 * @package RonAppleton\Autoload\Events
 */
class Dump
{
    /**
     * @var array
     */
    protected static array $discoverPaths = [
        'discover',
    ];

    /**
     * @param Event $event
     * @throws JsonException
     */
    public static function post(Event $event): void
    {
        $vendorDirectory = (string) $event->getComposer()->getConfig()->get('vendor-dir');

        $manager = new Manager(self::$discoverPaths);
        $manager->discover($vendorDirectory);
    }
}
