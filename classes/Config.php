<?php

namespace OranFry\Tools;

use OranFry\Jars\Contract\Client as JarsClient;

abstract class Config
{
    public function boot(JarsClient $jars): ?array
    {
        return null;
    }

    public function contextVariables(): array
    {
        return [];
    }

    public function custom(object $config, ?string $httpMountPoint, ?string $cliMountPoint, array $options): void
    {
    }

    public function defaults(): array
    {
        return [];
    }

    public function includePath(): ?string
    {
        return null;
    }

    public function requires(): array
    {
        return [];
    }

    public function router(): ?string
    {
        return null;
    }

    abstract public function title(): string;
}
