<?php

abstract class ContextVariableSet
{
    protected static $library = [];

    public string $prefix;
    public array $default_data;
    public ?string $label;
    public array $vars = [];

    public function __construct(string $prefix, array $default_data = [])
    {
        $this->prefix = $prefix;
        $this->default_data = $default_data;
    }

    abstract public function display();

    public function inputs()
    {
    }

    protected function getRawData()
    {
        $data = $this->default_data;

        if ($hvalue = getallheaders()['X-Cvs'] ?? null) {
            foreach (explode(',', $hvalue) as $rawheader) {
                [$fname, $fvalue] = explode('=', $rawheader, 2);
                [$fmajor, $fminor] = explode('__', $fname, 2);

                if ($fmajor == $this->prefix) {
                    $data[$fminor] = str_replace('|', ',', $fvalue);
                }
            }
        } else {
            $prefix_du = $this->prefix . '__';

            foreach ($_GET as $qname => $qvalue) {
                if (strpos($qname, $prefix_du) === 0) {
                    $name = substr($qname, strlen($prefix_du));
                    $data[$name] = $qvalue;
                }
            }
        }

        return $data;
    }

    public static function get($name)
    {
        return @static::$library[$name];
    }

    public static function getAll()
    {
        return @static::$library;
    }

    public static function getValues()
    {
        $data = [];

        foreach (static::getAll() as $cvs) {
            foreach ($cvs->getRawData() as $name => $value) {
                $data[$cvs->prefix . '__' . $name] = $value;
            }
        }

        return $data;
    }

    public static function put($name, $object)
    {
        static::$library[$name] = $object;
    }

    public static function dump()
    {
        $function = implode('_', ['var', 'dump']);

        $function(static::$library);
    }

    public function constructQuery($changes)
    {
        $data = static::getValues();

        foreach ($changes as $name => $value) {
            $data[$this->prefix . '__' . $name] = $value;
        }

        $data = array_filter($data, function($e){ return (bool) $e; });

        return implode('&', array_map(function($v, $k){ return "{$k}={$v}"; }, array_values($data), array_keys($data)));
    }
}
