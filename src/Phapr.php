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

        $this->container->singleton('phapr', $this);
        $this->container->alias('phapr', static::class);
        $this->container->singleton('event', $this->eventDispatcher);
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
            return new Build($this, $this->container);
        });
        $this->container->singleton('expression', function() {
            return new Engine($this);
        });
        $this->container->singleton('filesystem', function() {
            return new Filesystem();
        });
    }
}