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
}
