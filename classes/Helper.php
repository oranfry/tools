<?php

namespace Tools;

use subsimple\Exception;

class Helper
{
    public static function resolve(string $absolutePath): string
    {
        $pieces = explode('/', substr($absolutePath, 1));

        for ($i = 0; $i < count($pieces); $i++) {
            switch ($pieces[$i]) {
                case '':
                    throw new Exception('Encountered an invalid path: double slash not allowed [' . $absolutePath . ']');

                case '.':
                    unset($pieces[$i--]);
                    $pieces = array_values($pieces);
                    break;

                case '..':
                    if (!$i) {
                        throw new Exception('Encountered an invalid path: backtracked past filesystem root [' . $absolutePath . ']');
                    }

                    unset($pieces[$i - 1], $pieces[$i]);

                    $pieces = array_values($pieces);
                    $i -= 2;
            }
        }

        return '/' . implode('/', $pieces);
    }
}
