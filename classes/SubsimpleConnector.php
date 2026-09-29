<?php

namespace OranFry\Tools;

use OranFry\Subsimple\Exception;

class SubsimpleConnector
{
    protected object $config;
    protected array $mounted = [];
    protected array $httpMounted = [];
    protected array $cliMounted = [];
    protected ?string $fallback = null;

    public function __construct(object $config, ?string $base = null)
    {
        $this->config = $config;

        if ($base && !preg_match('@^/@', $base)) {
            throw (new Exception('Tools base URL should start with a slash if not empty'))
                ->publicMessage('Internal error, please contact the site administrator');
        }

        if ($base === '/') {
            throw (new Exception('Tools base URL should be empty if the base is the root URL'))
                ->publicMessage('Internal error, please contact the site administrator');
        }

        define('TOOLS_BASE_URL', $base ?? '');

        Router::add('GET ' . (TOOLS_BASE_URL ?: '/'), [
            'PAGE' => 'tools/login',
            'AUTHSCHEME' => 'none',
            'LAYOUT' => 'login',
        ]);

        Router::add('POST ' . TOOLS_BASE_URL . '/ajax/auth/(?:login|logout)', [
            'FORWARD' => \OranFry\Jars\HTTP\HttpRouter::class,
            'EAT' => TOOLS_BASE_URL . '/ajax',
        ]);

        $this->boot();
    }

    protected function boot()
    {
        $this->config->mounted = &$this->mounted;
        $this->config->httpMounted = &$this->httpMounted;
        $this->config->cliMounted = &$this->cliMounted;
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

        if ($router = $pluginConfig->router()) {
            if ($httpMountPoint !== null) {
                if (!preg_match('@^/@', $httpMountPoint)) {
                    throw (new Exception('Invalid httpMountPoint'))
                        ->publicMessage($complaint);
                }

                if (!$this->httpMounted || $default) {
                    $this->config->landingpage = $httpMountPoint;
                }

                $route = array_filter([
                    'FORWARD' => $router,
                    'TOOLS_PLUGIN_CONFIG' => $pluginConfig,
                    'TOOLS_PLUGIN_INCLUDE_PATH' => $includePath !== null ? Helper::resolve(APP_HOME . '/' . $includePath) : null,
                    'TOOLS_PLUGIN_MOUNT_POINT' => $httpMountPoint,
                    'TOOLS_PLUGIN_OPTIONS' => $options,
                    'TOOLS_PLUGIN_TITLE' => $title,
                    'LAYOUT' => 'tools',
                ], fn ($item) => null !== $item);

                if ('/' !== $eat = TOOLS_BASE_URL . $httpMountPoint) {
                    $route['EAT'] = $eat;
                }

                Router::add('HTTP ' . TOOLS_BASE_URL . $httpMountPoint . '.*', $route);

                $this->httpMounted[] = $pluginSummary;
            }

            if ($cliMountPoint !== null) {
                if (!preg_match('/^\w*$/', $cliMountPoint)) {
                    throw (new Exception('Invalid cliMountPoint'))
                        ->publicMessage($complaint);
                }

                $route = array_filter([
                    'FORWARD' => $router,
                    'TOOLS_PLUGIN_CONFIG' => $pluginConfig,
                    'TOOLS_PLUGIN_INCLUDE_PATH' => $includePath !== null ? Helper::resolve(APP_HOME . '/' . $includePath) : null,
                    'TOOLS_PLUGIN_MOUNT_POINT' => $httpMountPoint,
                    'TOOLS_PLUGIN_OPTIONS' => $options,
                    'TOOLS_PLUGIN_TITLE' => $title,
                    'TOOLS_PLUGIN_CLI_MOUNT_POINT',
                ], fn ($item) => null !== $item);

                if ($cliMountPoint) {
                    $route['EAT'] = $cliMountPoint;
                }

                $pattern = implode(' ', array_filter([
                    'CLI',
                    $cliMountPoint,
                    '*',
                ]));

                Router::add($pattern, $route);

                $this->cliMounted[] = $pluginSummary;
            }
        }

        $this->mounted[] = $pluginSummary;

        return $this;
    }
}
