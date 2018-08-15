<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

/**
 * Interface ModuleInterface
 *
 * @package Phapr
 * @author Panlatent <panlatent@gmail.com>
 */
Interface ModuleInterface
{
    /**
     * The module display name.
     *
     * @return string
     */
    public static function displayName(): string;

    /**
     * The module version.
     *
     * @return string
     */
    public static function displayVersion(): string;
}