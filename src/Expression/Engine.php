<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Expression;

use Phapr\Phapr;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\ParsedExpression;

/**
 * Class Engine
 *
 * @package Phapr\Expression
 * @author Panlatent <panlatent@gmail.com>
 */
class Engine extends ExpressionLanguage
{
    /**
     * @var Phapr
     */
    protected $phapr;
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * Engine constructor.
     *
     * @param Phapr $phapr
     * @param CacheItemPoolInterface|null $cache
     * @param array $providers
     */
    public function __construct(Phapr $phapr, CacheItemPoolInterface $cache = null, $providers = [])
    {
        $this->phapr = $phapr;
        $this->cache = $cache ?: new ArrayAdapter();
        array_unshift($providers, new InstructionProvider($phapr));

        parent::__construct($this->cache, $providers);
    }

    /**
     * @param string|\Symfony\Component\ExpressionLanguage\Expression $expression
     * @param array $names
     * @return \Symfony\Component\ExpressionLanguage\Expression|mixed|string
     */
    public function parse($expression, $names)
    {
        if ($expression instanceof ParsedExpression) {
            return $expression;
        }

        asort($names);
        $cacheKeyItems = array();

        foreach ($names as $nameKey => $name) {
            $cacheKeyItems[] = is_int($nameKey) ? $name : $nameKey.':'.$name;
        }
        $cacheItem = $this->cache->getItem(rawurlencode($expression.'//'.implode('|', $cacheKeyItems)));

        if (null === $parsedExpression = $cacheItem->get()) {
            $nodes = $this->getParser()->parse($this->getLexer()->tokenize((string) $expression), $names);
            $parsedExpression = new ParsedExpression((string) $expression, $nodes);

            $cacheItem->set($parsedExpression);
            $this->cache->save($cacheItem);
        }

        return $parsedExpression;
    }

    /**
     * @return Parser
     */
    private function getParser(): Parser
    {
        if (null === $this->parser) {
            $this->parser = new Parser($this->functions);
        }

        return $this->parser;
    }

    /**
     * @return Lexer
     */
    private function getLexer(): Lexer
    {
        if (null === $this->lexer) {
            $this->lexer = new Lexer();
        }

        return $this->lexer;
    }
}