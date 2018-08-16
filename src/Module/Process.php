<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Module;

use Phapr\Error\AbortScriptException;
use Phapr\Io;
use Phapr\Module;
use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Class Process
 *
 * @package Phapr\Module
 * @author Panlatent <panlatent@gmail.com>
 */
class Process extends Module
{
    /**
     * @var Io
     */
    protected $io;

    /**
     * Process constructor.
     *
     * @param Io $io
     */
    public function __construct(Io $io)
    {
        $this->io = $io;
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Process';
    }

    /**
     * @inheritdoc
     */
    public static function displayVersion(): string
    {
        return '0.1';
    }

    /**
     * @param array|string $commandline
     * @param bool $checkReturn
     * @return int
     */
    public function exec($commandline, bool $checkReturn = true)
    {
        $process = new SymfonyProcess($commandline);
        $process->run();

        if ($checkReturn && !$process->isSuccessful()) {
            $this->io->writeln("<error>[Exec]</error> $commandline");
            throw new AbortScriptException("exec failed", $process->getExitCode());
        }
        $this->io->writeln("<info>[Exec]</info> $commandline");

        return $process->getExitCode();
    }
}