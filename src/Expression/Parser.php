<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Expression;

use Symfony\Component\ExpressionLanguage\Node\ConstantNode;
use Symfony\Component\ExpressionLanguage\Node\Node;
use Symfony\Component\ExpressionLanguage\Token;
use Symfony\Component\ExpressionLanguage\TokenStream;

/**
 * Class Parser
 *
 * @package Phapr\Expression
 * @author Panlatent <panlatent@gmail.com>
 */
class Parser extends \Symfony\Component\ExpressionLanguage\Parser
{
    /**
     * @var TokenStream
     */
    private $stream;

    /**
     * @var array
     */
    private $instructions;

    /**
     * @var array
     */
    private $names;

    /**
     * Parser constructor.
     *
     * @param array $functions
     */
    public function __construct(array $functions)
    {
        parent::__construct($functions);

        $this->instructions = [
            'import',
        ];
    }

    /**
     * @param TokenStream $stream
     * @param array $names
     * @return Node
     */
    public function parse(TokenStream $stream, $names = array())
    {
        $this->stream = $stream;
        $this->names = $names;

        return parent::parse($stream, $names);
    }

    /**
     * @return \Symfony\Component\ExpressionLanguage\Node\Node
     */
    protected function getPrimary()
    {
        /** @var Token $token */
        $token = $this->stream->current;
        if ($token->test(Token::NAME_TYPE) && in_array($token->value, $this->instructions)) {
            $this->stream->next();
            $node = $this->parseInstructionExpression();

            return $this->parsePostfixExpression(new InstructionNode($token->value, $node));
        }

        return parent::getPrimary();
    }

    /**
     * @return Node
     */
    protected function parseInstructionExpression()
    {
        $args = [];
        while (!$this->stream->isEOF()) {
            $token = $this->stream->current;
            switch ($token->type) {
                case Token::NAME_TYPE:
                case Token::STRING_TYPE:
                case Token::NUMBER_TYPE:
                    $this->stream->next();
                    $args[] = new ConstantNode($token->value);
                    break;
            }
        }

        return new Node($args);
    }
}