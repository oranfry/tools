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

    public function mount(string $point, string $configClass, bool $default = false, array $options = []): self
    {
        $complaint = 'Internal error, please contact the site administrator';

        if (!preg_match('@^/@', $point)) {
            throw (new Exception('Invalid mount point'))
                ->publicMessage($complaint);
        }

        $pluginConfig = new $configClass();

        $options = $options + $pluginConfig->defaults();

        if (!$this->mounted || $default) {
            $this->config->landingpage = $point . $pluginConfig->landingpage();
        }

        $title = $options['title'] ?? $pluginConfig->title();
        $includePath = $pluginConfig->includePath();

        $includePaths = array_merge($includePath !== null ? [$includePath] : [], $pluginConfig->requires());
        $reqs = array_map(fn ($path) => Helper::resolve(APP_HOME . '/' . $path), $includePaths);

        foreach ($reqs as $req) {
            if (
                $req !== APP_HOME
                && !in_array($req, $this->config->requires, true)
            ) {
                $this->config->requires[] = $req;
            }
        }

        if ($router = $pluginConfig->router()) {
            $route = array_filter([
                'FORWARD' => $router,
                'TOOLS_PLUGIN_CONFIG' => $pluginConfig,
                'TOOLS_PLUGIN_INCLUDE_PATH' => $includePath !== null ? Helper::resolve(APP_HOME . '/' . $includePath) : null,
                'TOOLS_PLUGIN_MOUNT_POINT' => $point,
                'TOOLS_PLUGIN_OPTIONS' => $options,
                'TOOLS_PLUGIN_TITLE' => $title,
            ], fn ($item) => null !== $item);

            if ($point !== '/') {
                $route['EAT'] = $point;
            }

            Router::add("HTTP $point.*", $route);
        }

        $pluginConfig->custom($this->config, $point, $options);

        $this->mounted[] = (object) compact('point', 'configClass', 'includePath', 'options', 'title');

        return $this;
    }
}
