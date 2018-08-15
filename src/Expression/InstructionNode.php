<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Expression;

use Symfony\Component\ExpressionLanguage\Compiler;
use Symfony\Component\ExpressionLanguage\Node\Node;

/**
 * Class InstructionNode
 *
 * @package Phapr\Expression
 * @author Panlatent <panlatent@gmail.com>
 */
class InstructionNode extends Node
{
    /**
     * InstructionNode constructor.
     *
     * @param string $name
     * @param Node $arguments
     */
    public function __construct(string $name, Node $arguments)
    {
        parent::__construct(
            ['arguments' => $arguments],
            ['name' => $name]
        );
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $arguments = array();
        foreach ($this->nodes['arguments']->nodes as $node) {
            $arguments[] = $compiler->subcompile($node);
        }

        $function = $compiler->getFunction($this->attributes['name']);

        $compiler->raw(call_user_func_array($function['compiler'], $arguments));
    }

    /**
     * @param $functions
     * @param $values
     * @return array|mixed
     */
    public function evaluate($functions, $values)
    {
        $arguments = array($values);
        /** @var Node $node */
        foreach ($this->nodes['arguments']->nodes as $node) {
            $arguments[] = $node->evaluate($functions, $values);
        }

        return call_user_func_array($functions[$this->attributes['name']]['evaluator'], $arguments);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $array[] = $this->attributes['name'];

        foreach ($this->nodes['arguments']->nodes as $node) {
            $array[] = ', ';
            $array[] = $node;
        }
        $array[1] = '(';
        $array[] = ')';

        return $array;
    }
}