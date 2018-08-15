<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

abstract class Module implements ModuleInterface
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function displayName(): bool
    {
        return basename(str_replace('\\', DIRECTORY_SEPARATOR, static::class));
    }
}