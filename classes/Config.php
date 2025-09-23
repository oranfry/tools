<?php

namespace Tools;

abstract class Config
{
    public function boot(): void
    {
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

    public function landingpage(): ?string
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
