<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

/**
 * Class Module
 *
 * @package Phapr
 * @author Panlatent <panlatent@gmail.com>
 */
abstract class Module implements ModuleInterface
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }

    /**
     * @inheritdoc
     */
    public static function displayVersion(): string
    {
        return '';
    }
}