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
     * Our package manager.
     *
     * @var Manager
     */
    private Manager $manager;

    /**
     * @var array
     */
    protected array $discoverPaths = [
        'discover',
    ];

    public function __construct()
    {
        $this->manager = new Manager($this->discoverPaths);
    }

    /**
     * @param Event $event
     * @throws JsonException
     */
    public function post(Event $event): void
    {
        $vendorDirectory = (string) $event->getComposer()->getConfig()->get('vendor-dir');
        $this->manager->discover($vendorDirectory);
    }
}
