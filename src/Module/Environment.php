<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Module;

use Dotenv\Dotenv;
use Phapr\Context;
use Phapr\Module;

class Environment extends Module
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Dotenv
     */
    protected $loader;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Environment';
    }

    /**
     * @inheritdoc
     */
    public static function displayVersion(): string
    {
        return '0.1';
    }

    /**
     * Environment constructor.
     *
     * @param Context $context
     * @param Filesystem $filesystem
     */
    public function __construct(Context $context, Filesystem $filesystem)
    {
        $this->context = $context;
        $this->loader = new Dotenv($context->root);

        if ($filesystem->exists($context->root . DIRECTORY_SEPARATOR . '.env')) {
            $this->variables = $this->loader->load();
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        if ($this->variables !== null && array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return getenv($name) ?: '';
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return ($this->variables && array_key_exists($name, $this->variables)) || getenv($name) !== false;
    }
}