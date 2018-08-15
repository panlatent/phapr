<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Expression;

use Phapr\Phapr;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class InstructionProvider
 *
 * @package Phapr\Expression
 * @author Panlatent <panlatent@gmail.com>
 */
class InstructionProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @var Phapr
     */
    protected $phapr;

    /**
     * InstructionProvider constructor.
     *
     * @param Phapr $phapr
     */
    public function __construct(Phapr $phapr)
    {
        $this->phapr = $phapr;
    }

    /**
     * @return array|ExpressionFunction[]
     */
    public function getFunctions()
    {
        return [
            new ExpressionFunction('import', function($expr) {

            }, function(/** @noinspection PhpUnusedParameterInspection */
                $args, $expr) {
                if ($expr === 'self') {
                    return $this->phapr;
                }

                return $this->phapr->get($expr);
            }),
        ];
    }
}