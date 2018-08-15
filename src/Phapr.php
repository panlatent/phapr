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
     * @return Build|null
     */
    public function getBuild()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('build', false);
    }

    /**
     * @return Engine|null
     */
    public function getExpression()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('expression', false);
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
     * @return Io|null
     */
    public function getIo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('io', false);
    }

    /**
     * Register core modules.
     */
    private function registerCoreModules()
    {
        $this->set('build', function() {
            return $this->container->make(Build::class);
        });
        $this->set('expression', function() {
            return $this->container->make(Engine::class);
        });
        $this->set('filesystem', function() {
            return $this->container->make(Filesystem::class);
        });
    }
}