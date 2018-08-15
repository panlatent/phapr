<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

/**
 * Class Container
 *
 * @package Phapr
 * @author Panlatent <panlatent@gmail.com>
 */
class Container extends \Illuminate\Container\Container
{
    public function __construct()
    {
        $this->instance(static::class, $this);
    }
}