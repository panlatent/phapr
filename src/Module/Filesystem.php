<?php

namespace Phapr\Module;

use Phapr\ModuleInterface;

class Filesystem extends \Symfony\Component\Filesystem\Filesystem implements ModuleInterface
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Symfony Filesystem';
    }

    /**
     * @inheritdoc
     */
    public static function displayVersion(): string
    {
        return '0.1 [4.2]';
    }
}