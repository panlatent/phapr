<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr;

use Phapr\Error\AbortScriptException;
use SplFileInfo;

/**
 * Class Script
 *
 * @package Phapr
 * @author Panlatent <panlatent@gmail.com>
 */
class Script
{
    const EVENT_SCRIPT_BEFORE_REQUIRE = 'scriptBeforeRequire';
    const EVENT_SCRIPT_AFTER_REQUIRE = 'scriptBeforeRequire';

    /**
     * @var string
     */
    protected $filename;
    /**
     * @var string|null
     */
    protected $content;

    /**
     * Script constructor.
     *
     * @param string $filename
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $info = new SplFileInfo($this->filename);
        if (!$info->isFile()) {
            if ($info->isDir()) {
                throw new Exception("{$filename} is a directory");
            }
            throw new Exception("Not found file {$filename}");
        }
        if (!$info->isReadable()) {
            throw new Exception("Unreadable $filename");
        }

        $this->prepare();
    }

    /**
     * Require script file.
     * @noinspection PhpDocRedundantThrowsInspection
     * @throws \Throwable|AbortScriptException
     */
    public function execute()
    {
       /** @noinspection PhpIncludeInspection */
       require $this->filename;
    }

    /**
     * Prepare script content.
     */
    protected function prepare()
    {
        $this->content = file_get_contents($this->filename);
    }
}