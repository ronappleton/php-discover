<?php

declare(strict_types=1);

namespace RonAppleton\Discover\Helpers;

/**
 * Class PackageLoader
 * @package RonAppleton\Autoload\Helpers
 */
class PackageLoader
{
    /**
     * @var PackageLoader
     */
    protected static PackageLoader $instance;

    /**
     * @var array
     */
    protected array $packages;

    /**
     * @var string
     */
    protected string $cacheFile = __DIR__ . '/../cache/packages.php';

    public function __construct()
    {
        $this->packages = $this->load();
    }

    /**
     * @return array
     */
    private function load(): array
    {
        if (file_exists($this->cacheFile)) {
            /** @noinspection PhpIncludeInspection */
            $discovered = require $this->cacheFile;
        }

        return $discovered ?? [];
    }

    /**
     * @param string|null $name
     * @return array
     */
    private function get(string $name = null): array
    {
        if ($name) {
            return $this->packages[$name] ?? [];
        }

        return $this->packages ?? [];
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    public function __call(string $name, array $arguments): array
    {
        return $this->get($arguments[0] ?? null);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return array
     */
    public static function __callStatic(string $name, array $arguments): array
    {
        if (!isset(static::$instance)) {
            static::$instance = new self;
        }

        return static::$instance->get($arguments[0] ?? null);
    }
}
