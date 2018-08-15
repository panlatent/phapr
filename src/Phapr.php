<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

use Phapr\Expression\Engine;
use Phapr\Module\Build;
use Phapr\Module\Filesystem;
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
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Phapr constructor.
     */
    public function __construct()
    {
        static::$phapr = $this;
        $this->container = new Container();
        $this->eventDispatcher = new EventDispatcher();

        $this->container->instance('phapr', $this);
        $this->container->instance('event', $this->eventDispatcher);
        $this->container->alias('phapr', static::class);
        $this->container->alias('event', EventDispatcher::class);

        $this->registerCoreModules();
    }

    /**
     * @param string $name
     * @return object
     */
    public function get(string $name)
    {
        return $this->container->get($name);
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
        } else {
            $this->container->bind($abstract, $concrete, $isService);
        }
    }

    /**
     * @return Build
     */
    public function getBuild(): Build
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('build');
    }

    /**
     * @return Engine
     */
    public function getExpression(): Engine
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('expression');
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('filesystem');
    }

    /**
     * Register core modules.
     */
    private function registerCoreModules()
    {
        $this->container->singleton('build', function() {
            return $this->container->make(Build::class);
        });
        $this->container->singleton('expression', function() {
            return $this->container->make(Engine::class);
        });
        $this->container->singleton('filesystem', function() {
            return $this->container->make(Filesystem::class);
        });
    }
}