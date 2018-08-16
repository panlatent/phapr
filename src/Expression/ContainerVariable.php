<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Expression;

use Phapr\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class ContainerVariable
 *
 * @package Phapr\Expression
 * @author Panlatent <panlatent@gmail.com>
 */
class ContainerVariable
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var PropertyAccessor|null
     */
    private $accessor;

    /**
     * @var mixed|null
     */
    private $object;

    /**
     * ContainerVariable constructor.
     *
     * @param Container $container
     * @param string $id
     */
    public function __construct(Container $container, string $id)
    {
        $this->container = $container;
        $this->id = $id;
    }

    /**
     * @return PropertyAccessor
     */
    public function getAccessor(): PropertyAccessor
    {
        if ($this->accessor !== null) {
            return $this->accessor;
        }

        return $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        if ($this->object !== null) {
            return $this->object;
        }

        return $this->object = $this->container->get($this->id);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->getAccessor()->isReadable($this->getObject(), $name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getAccessor()->getValue($this->getObject(), $name);
    }
}