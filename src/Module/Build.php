<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Module;

use Phapr\Container;
use Phapr\Exception;
use Phapr\Io;
use Phapr\Module;
use Phapr\Module\Build\DependencyException;
use Phapr\Module\Build\Task;
use Phapr\ModuleInterface;
use Phapr\Phapr;

/**
 * Class Build
 *
 * @package Phapr\Module
 * @author Panlatent <panlatent@gmail.com>
 */
class Build extends Module implements ModuleInterface
{
    /**
     * @var Phapr
     */
    protected $phapr;
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var Io
     */
    protected $io;
    /**
     * @var string|null
     */
    protected $name;
    /**
     * @var string|null
     */
    protected $default;
    /**
     * @var Task[]
     */
    protected $tasks;

    public function __construct(Phapr $phapr, Container $container, Io $io)
    {
        $this->phapr = $phapr;
        $this->container = $container;
        $this->io = $io;
    }

    public static function displayVersion(): string
    {
        return '0.1-dev';
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function default($task)
    {
        $this->default = $task;

        return $this;
    }

    /**
     * Create a task.
     *
     * Method overwrites:
     *
     * Build::task($name, $task)
     * Build::task($name, $task, $depends)
     * Build::task($name, $task, $depends, $options)
     * Build::task($name, $task, $depends, $option1, $option2 ...)
     *
     * @param string $name
     * @param mixed ...$definition
     * @return $this
     */
    public function task(string $name, ...$definition)
    {
        $argc = count($definition);

        if ($argc == 1) {
            list($definition) = $definition;
            $this->tasks[$name] = new Task($name, $definition);
        } elseif ($argc == 2) {
            list($definition, $depends) = $definition;
            $this->tasks[$name] = new Task($name, $definition, $depends);
        } elseif ($argc == 3) {
            list($definition, $depends, $options) = $definition;
            $this->tasks[$name] = new Task($name, $definition, $depends, $options);
        } elseif ($argc > 3) {
            $options = array_slice($definition, 2);
            list($definition, $depends, ) = $definition;
            $this->tasks[$name] =  new Task($name, $definition, $depends, $options);
        }

        return $this;
    }

    /**
     * Run a task and depends tasks.
     *
     * @param string $name
     */
    public function run(string $name = null)
    {
        $this->io->writeln("Phapr PHP Build Framework <info>v" . Phapr::VERSION . "</info>");
        $this->io->writeln(static::displayName() . " Module " . static::displayVersion());
        $this->io->writeln('');

        if ($name === null) {
            $name = $this->default;
        }
        if (!isset($this->tasks[$name])) {
            throw new Exception("Not found task: {$name}");
        }

        $this->io->writeln("<info>Starting task $name: </info>");

        /** @var Task[] $chains */
        $chains = [];
        $this->resolve($this->tasks[$name], $chains);

        foreach ($chains as $task) {
            $this->io->writeln('');
            $this->io->writeln("=> <comment>Run {$task->getName()} task:</comment> ");
            try {
                $task->run($this->container);
            } catch (\Throwable $e) {
                $this->io->writeln('');
                $this->io->writeln("<error>Build Failed on “{$task->getName()}“ task.</error>");

                throw $e;
            }
        }
    }

    /**
     * @param Task $task
     * @param array $chains
     * @todo check circular dependencies
     */
    protected function resolve(Task $task, array &$chains)
    {
        foreach ($task->getDepends() as $name) {
            if (!isset($this->tasks[$name])) {
                throw new DependencyException("Missing a dependency task: $name");
            }
            $this->resolve($this->tasks[$name], $chains);
        }

        $chains[] = $task;
    }
}