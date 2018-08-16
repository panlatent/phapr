<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

use Phapr\Expression\Engine;
use Phapr\Module\Build;
use Phapr\Module\Filesystem;
use Phapr\Module\Process;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Phapr
 *
 * @package Phapr
 * @author Panlatent <panlatent@gmail.com>
 */
class Phapr
{
    /**
     * Phapr version
     */
    const VERSION = '0.1.0';

    /**
     * @var static
     */
    public static $phapr;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Engine
     */
    protected $expression;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Io
     */
    protected $io;

    /**
     * Phapr constructor.
     *
     * @param Io $io
     */
    public function __construct(Io $io)
    {
        static::$phapr = $this;

        $this->io = $io;
        $this->container = new Container();
        $this->expression = new Engine($this);
        $this->eventDispatcher = new EventDispatcher();

        $this->set('phapr', $this);
        $this->set('io', $io);
        $this->set('expression', $this->expression);
        $this->set('event', $this->eventDispatcher);

        $this->registerCoreModules();
    }

    /**
     * @param string $name
     * @param bool $throwException
     * @return object|null
     */
    public function get(string $name, bool $throwException = true)
    {
        try {
            return $this->container->get($name);
        } catch (\Exception $e) {
            if (!$throwException) {
                return null;
            }
        }

        throw $e;
    }

    /**
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @param bool $isService
     */
    public function set(string $abstract, $concrete, bool $isService = true)
    {
        if (is_object($concrete) && ! $concrete instanceof \Closure) {
            $this->container->instance($abstract, $concrete);
            if ($abstract !== get_class($concrete)) {
                $this->container->alias($abstract, get_class($concrete));
            }
        } else {
            $this->container->bind($abstract, $concrete, $isService);
        }
    }

    /**
     * @return Engine
     */
    public function getExpression(): Engine
    {
        return $this->expression;
    }

    /**
     * @return Io
     */
    public function getIo(): Io
    {
        return $this->io;
    }

    /**
     * @return Build|null
     */
    public function getBuild()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('build', false);
    }

    /**
     * @return Filesystem|null
     */
    public function getFilesystem()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('filesystem', false);
    }

    /**
     * Register core modules.
     */
    private function registerCoreModules()
    {
        $this->set('build', function() {
            return $this->container->make(Build::class);
        });
        $this->set('filesystem', function() {
            return $this->container->make(Filesystem::class);
        });
        $this->set('process', function() {
            return $this->container->make(Process::class);
        });
    }
}