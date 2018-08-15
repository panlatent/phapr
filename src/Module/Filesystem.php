<?php

namespace Phapr\Module;

use Phapr\ModuleInterface;

class Filesystem extends \Symfony\Component\Filesystem\Filesystem implements ModuleInterface
{
    public static function displayName(): bool
    {
        return 'Symfony Filesystem';
    }
}