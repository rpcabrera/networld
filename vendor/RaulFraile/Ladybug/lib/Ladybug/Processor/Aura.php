<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Processor / Aura
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Processor;

class Aura implements ProcessorInterface
{

    public function isProcessable($str)
    {
        return strpos($str, 'Aura') !== false;
    }

    public function process($str)
    {
        $matches = array();
        $result = $str;

        if (preg_match_all('/\(Aura[\\\\A-Za-z]+\)/', $str, $matches)) {
            $matches = array_unique($matches[0]);

            foreach ($matches as $m) {
                $class = str_replace('(', '',str_replace(')', '', $m));

                $result = str_replace($m, '(<a href="#" class="doc aura" target="_blank" title="'.$class.'"></a>'.$class.')', $result);
            }

        }

        return $result;
    }
}
