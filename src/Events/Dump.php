<?php

declare(strict_types=1);

namespace RonAppleton\Discover\Events;

use Composer\Script\Event;

/**
 * Class Dump
 * @package RonAppleton\Autoload\Events
 */
class Dump
{
    /**
     * @var Event
     */
    protected static Event $event;

    /**
     * @var array|string[]
     */
    protected static array $discoverPaths = [
        'discover',
    ];

    /**
     * @param Event $event
     */
    public static function post(Event $event)
    {
        static::$event = $event;
        static::cache(static::getDiscovers());
    }

    /**
     * Store our discovered package details.
     *
     * @param array $dataToCache
     */
    protected static function cache(array $dataToCache)
    {
        file_put_contents(__DIR__ . '/../cache/packages.php', '<?php return ' . var_export($dataToCache, true) . ';');
    }

    /**
     * @return array
     */
    protected static function getDiscovers(): array
    {
        $discovered = [];

        $vendorDir = static::$event->getComposer()->getConfig()->get('vendor-dir');

        if (file_exists($path = $vendorDir . '/composer/installed.json')) {
            $installedPackages = json_decode(file_get_contents($path), true);
        }

        if (isset($installedPackages['packages'])) {
            foreach ($installedPackages['packages'] as $package) {
                foreach (static::$discoverPaths as $path) {
                    if (isset($package['extra'][$path])) {
                        echo "Discovered Package: " . $package['name'] . PHP_EOL;
                        $discovered[$package['name']] = $package['extra'][$path];
                    }
                }
            }
        }

        return $discovered;
    }
}
