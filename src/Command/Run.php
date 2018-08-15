<?php
/**
 * @link https://phapr.com/
 * @license http://opensource.org/licenses/MIT
 */

namespace Phapr\Command;

use Phapr\Script;
use Phapr\Phapr;
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
        $this->phapr = new Phapr();

        $filename = $input->getOption('build');

        $filesystem = $this->phapr->getFilesystem();
        if (!$filesystem->exists($filename)) {
            $output->writeln("<error>Not found build file: {$filename}</error>");
            return 1;
        }

        $script = new Script($filename);
        $script->execute();

        return 0;
    }
}