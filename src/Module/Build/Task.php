<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Module\Build;

use Phapr\Container;
use Phapr\Exception;
use Phapr\Io;

/**
 * Class Task
 *
 * @package Phapr\Module\Build
 * @author Panlatent <panlatent@gmail.com>
 */
class Task
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Closure|callable
     */
    protected $definition;

    /**
     * @var array
     */
    protected $depends;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var mixed|null
     */
    protected $result;

    /**
     * @var bool
     */
    private $isDone = false;

    /**
     * Task constructor.
     *
     * @param string $name
     * @param \Closure|callable $definition
     * @param array $depends
     * @param array $options
     */
    public function __construct(string $name, $definition, array $depends = [], array $options = [])
    {
        $this->name = $name;
        $this->definition = $definition;
        $this->depends = $depends;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getDepends(): array
    {
        return $this->depends;
    }

    /**
     * @return mixed|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function hasDepends(): bool
    {
        return !empty($this->depends);
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * @param Container $container
     * @param array $params
     */
    public function run(Container $container, $params = [])
    {
        if ($this->isDone) {
            throw new Exception("cannot be executed a task again");
        }

        /** @var Io $io */
        $io = $container->get(Io::class);

        if ($this->definition instanceof \Closure) {
            $this->definition->bindTo($this, $this);
        }

        ob_start(function($content) use($io) {
            if (($pos = strpos($content, PHP_EOL)) === false) {
                return $content;
            }
            $buffer = substr($content, 0, $pos);
            $io->writeln("<info>[Echo]</info> $buffer");

            return substr($content, $pos + 1);
        }, 2);
        $this->result = $container->call($this->definition, $params);
        ob_end_clean();


        $this->isDone;
    }
}