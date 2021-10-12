<?php

declare(strict_types=1);

namespace RonAppleton\Discover\Helpers;

use JsonException;

/**
 * Class Manager
 *
 * @package RonAppleton\Discover\Helpers
 */
class Manager
{
    /**
     * @var array
     */
    private array $discoverPaths;

    /**
     * @var string
     */
    private string $cacheFilePath = '/cache/packages.php';

    /**
     * Initialise the class by constructing the full cache file path.
     */
    public function __construct(array $discoverPaths)
    {
        $this->discoverPaths = $discoverPaths;
        $this->cacheFilePath = dirname(__DIR__, 2) . $this->cacheFilePath;
    }

    /**
     * @return array
     */
    public function cacheGet(): array
    {
        if (file_exists($this->cacheFilePath)) {
            return (array) require($this->cacheFilePath);
        }

        return [];
    }

    /**
     * Store our discovered package details.
     *
     * @param array $dataToCache
     */
    protected function store(array $dataToCache): void
    {
        file_put_contents($this->cacheFilePath, '<?php return ' . var_export($dataToCache, true) . ';');
    }

    /**
     * @param string $vendorDirectory
     * @return void
     * @throws JsonException
     */
    public function discover(string $vendorDirectory): void
    {
        $discovered = [];

        $installedPackages = $this->getInstalledPackages($vendorDirectory);

        if (isset($installedPackages['packages'])) {
            /** @var array $package */
            foreach ($installedPackages['packages'] as $package) {
                /** @var string $path */
                foreach ($this->discoverPaths as $path) {
                    if (isset($package['extra'][$path])) {
                        $discovered[(string)$package['name']] = (array) $package['extra'][$path];

                        echo sprintf("Discovered Package: %s", (string) $package['name']) . PHP_EOL;
                    }
                }
            }
        }

        $this->store($discovered);
    }

    /**
     * @throws JsonException
     */
    private function getInstalledPackages(string $vendorDirectory): ?array
    {
        if (file_exists($path = $vendorDirectory . '/composer/installed.json')) {
            return (array) json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
