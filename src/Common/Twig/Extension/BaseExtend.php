<?php

namespace App\Common\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BaseExtend extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('base64_encode', [$this, 'baseEncode']),
        ];
    }

    public function baseEncode(mixed $value): string
    {
        if (is_resource($value)) {
            rewind($value);
            $value = stream_get_contents($value);
        }

        return base64_encode((string) $value);
    }
}
