<?php

namespace Tools;

use subsimple\Exception;

class SubsimpleConnector
{
    protected object $config;
    protected array $mounted = [];
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

    public function mount(string $point, string $path, bool $default = false, array $options = []): self
    {
        $complaint = 'Internal error, please contact the site administrator';

        if (!preg_match('@^/@', $point)) {
            throw (new Exception('Invalid mount point'))
                ->publicMessage($complaint);
        }

        $plugin_config = require APP_HOME . '/' . $path . '/tools-config.php';

        $options = $options + ($plugin_config->defaults ?? []);

        $landingpage = $plugin_config->landingpage ?? '';

        if (!$this->mounted || $default) {
            $this->config->landingpage = $point . $landingpage;
        }

        foreach (array_merge([$path], $plugin_config->requires ?? []) as $req) {
            $full = APP_HOME . '/' . $req;

            if (!in_array($full, $this->config->requires)) {
                $this->config->requires[] = $full;
            }
        }

        if ($router = $plugin_config->router ?? null) {
            $route = [
                'FORWARD' => $router,
                'TOOLS_PLUGIN' => $path,
                'TOOLS_PLUGIN_MOUNT_POINT' => $point,
                'TOOLS_PLUGIN_OPTIONS' => $options,
            ];

            if ($point !== '/') {
                $route['EAT'] = $point;
            }

            Router::add("HTTP $point.*", $route);
        }

        if (is_callable($callable = $plugin_config->custom ?? null)) {
            $callable($this->config, $point, $path, $options);
        }

        $this->mounted[] = (object) compact('point', 'path', 'options');

        return $this;
    }
}
