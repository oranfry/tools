<?php

namespace Tools;

use subsimple\Exception;

class SubsimpleConnector
{
    protected object $config;
    protected array $mounted = [];
    protected array $httpMounted = [];
    protected array $cliMounted = [];
    protected ?string $fallback = null;

    public function __construct(object $config)
    {
        $this->config = $config;

        $this->boot();
    }

    protected function boot()
    {
        $this->config->mounted = &$this->mounted;
        $this->config->router = Router::class;
        $this->config->requires ??= [];

        $requires = [
            'oranfry/tools',
            'oranfry/jars-http',
            'oranfry/context-variable-sets',
        ];

        foreach ($requires as $plugin) {
            if (!in_array($path = APP_HOME . '/vendor/' . $plugin, $this->config->requires)) {
                $this->config->requires[] = $path;
            }
        }
    }

    public function fallback(string $router): self
    {
        $this->fallback = $router;

        return $this;
    }

    public function get(): object
    {
        if ($this->fallback) {
            Router::add("HTTP /.*", ['FORWARD' => $this->fallback]);
            Router::add("CLI *", ['FORWARD' => $this->fallback]);
        }

        return $this->config;
    }

    public function mount(?string $httpMountPoint, ?string $cliMountPoint, string $configClass, bool $default = false, array $options = []): self
    {
        $complaint = 'Internal error, please contact the site administrator';

        if ($httpMountPoint === null && $cliMountPoint === null) {
                throw (new Exception('Please set at least one of httpMountPoint and cliMountPoint'))
                    ->publicMessage($complaint);
        }

        $pluginConfig = new $configClass();
        $options = $options + $pluginConfig->defaults();
        $title = $options['title'] ?? $pluginConfig->title();
        $includePath = $pluginConfig->includePath();
        $includePaths = array_merge($includePath !== null ? [$includePath] : [], $pluginConfig->requires());
        $reqs = array_map(fn ($path) => Helper::resolve(APP_HOME . '/' . $path), $includePaths);

        $pluginConfig->custom($this->config, $httpMountPoint, $cliMountPoint, $options);

        foreach ($reqs as $req) {
            if (
                $req !== APP_HOME
                && !in_array($req, $this->config->requires, true)
            ) {
                $this->config->requires[] = $req;
            }
        }

        $pluginSummary = (object) compact('httpMountPoint', 'cliMountPoint', 'configClass', 'includePath', 'options', 'title');

        if ($httpMountPoint !== null) {
            if (!preg_match('@^/@', $httpMountPoint)) {
                throw (new Exception('Invalid httpMountPoint'))
                    ->publicMessage($complaint);
            }

            if (!$this->httpMounted || $default) {
                $this->config->landingpage = $httpMountPoint . $pluginConfig->landingpage();
            }

            if ($router = $pluginConfig->router()) {
                $route = array_filter([
                    'FORWARD' => $router,
                    'TOOLS_PLUGIN_CONFIG' => $pluginConfig,
                    'TOOLS_PLUGIN_INCLUDE_PATH' => $includePath !== null ? Helper::resolve(APP_HOME . '/' . $includePath) : null,
                    'TOOLS_PLUGIN_MOUNT_POINT' => $httpMountPoint,
                    'TOOLS_PLUGIN_OPTIONS' => $options,
                    'TOOLS_PLUGIN_TITLE' => $title,
                ], fn ($item) => null !== $item);

                if ($httpMountPoint !== '/') {
                    $route['EAT'] = $httpMountPoint;
                }

                Router::add("HTTP $httpMountPoint.*", $route);
            }

            $this->httpMounted[] = $pluginSummary;
        }

        if ($cliMountPoint !== null) {
            if (!preg_match('/^[a-z0-9_-]+$/', $cliMountPoint)) {
                throw (new Exception('Invalid cliMountPoint'))
                    ->publicMessage($complaint);
            }

            if ($router = $pluginConfig->router()) {
                $route = array_filter([
                    'FORWARD' => $router,
                    'TOOLS_PLUGIN_CONFIG' => $pluginConfig,
                    'TOOLS_PLUGIN_INCLUDE_PATH' => $includePath !== null ? Helper::resolve(APP_HOME . '/' . $includePath) : null,
                    'TOOLS_PLUGIN_MOUNT_POINT' => $httpMountPoint,
                    'TOOLS_PLUGIN_OPTIONS' => $options,
                    'TOOLS_PLUGIN_TITLE' => $title,
                    'TOOLS_PLUGIN_CLI_MOUNT_POINT',
                ], fn ($item) => null !== $item);

                $route['EAT'] = $cliMountPoint;

                Router::add("CLI $cliMountPoint *", $route);
            }

            $this->cliMounted[] = $pluginSummary;
        }

        $this->mounted[] = $pluginSummary;

        return $this;
    }
}
