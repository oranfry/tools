<?php

namespace OranFry\Tools;

use OranFry\Jars\Contract\Client as JarsClient;
use OranFry\Subsimple\Config as SubsimpleConfig;

class JarsAwareLedgerConfig extends \OranFry\Ledger\Config
{
    protected JarsClient $jars;

    public function __construct(array $viewdata, ?int $version = null)
    {
        if (!@$viewdata['jars'] instanceof JarsClient) {
            throw new Exception('Please make sure $viewdata["jars"] is set to an instance of [' . JarsClient::class . ']');
        }

        $this->jars = $viewdata['jars'];
    }

    public function save(array $data): array
    {
        if (strtolower(getallheaders()['X-Differential'] ?? 'false') === 'true') {
            $data = array_values(array_filter(array_map(function ($line): ?object {
                $orig = $this->jars->get($line->type, $line->id);

                unset($line->type, $line->id);

                if (!$obvars = get_object_vars($line)) {
                    return null;
                }

                $changed = false;

                foreach (get_object_vars($line) as $prop => $value) {
                    if ($orig->$prop !== $value) {
                        $orig->$prop = $value;
                        $changed = true;
                    }
                }

                if (!$changed) {
                    return null;
                }

                return $orig;
            }, $data)));
        }

        return $this->jars->save($data, @getallheaders()['X-Base-Version']);
    }
}
