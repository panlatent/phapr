<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

if (!function_exists('phapr')) {
    /**
     * @param string|null $expr
     * @return Phapr\Phapr|\Phapr\Module\Build|mixed
     */
    function phapr(string $expr = null)
    {
        if ($expr === null) {
            return \Phapr\Phapr::$phapr->get('build');
        }

        return \Phapr\Phapr::$phapr->getExpression()->evaluate($expr);
    }
}