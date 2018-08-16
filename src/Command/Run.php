<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Command;

use Phapr\Error\AbortScriptException;
use Phapr\Io;
use Phapr\Script;
use Phapr\Phapr;
use Phapr\Stopwatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Run
 *
 * @package Phapr\Command
 * @author Panlatent <panlatent@gmail.com>
 */
class Run extends Command
{
    /**
     * @var Phapr|null
     */
    protected $phapr;

    /**
     * Configure.
     */
    protected function configure()
    {
        $this->setDescription('Run the build')
            ->addArgument('task', InputArgument::OPTIONAL, 'task to be run');

        $this->addOption('build', 'b', InputOption::VALUE_REQUIRED, 'Build script file', 'phapr.php');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();

        $io = new Io($input, $output);
        $this->phapr = new Phapr($io);
        $filename = $input->getOption('build');

        $filesystem = $this->phapr->getFilesystem();
        if (!$filesystem->exists($filename)) {
            $output->writeln("<error>Not found build file: {$filename}</error>");
            return 1;
        }

        $stopwatch->start('script:execute');
        try {
            $script = new Script($filename);
            $script->execute();
        } catch (AbortScriptException $e) {
            $exitCode = $e->getCode();
        }

        $sEvent = $stopwatch->stop('script:execute');
        $io->writeln('');
        $io->writeln(sprintf("Time: %sms, Memory: %sMB",
            $sEvent->getDuration(),
            round($sEvent->getMemory() / 1024 / 1024, 2)
        ));

        return $exitCode ?? 0;
    }
}