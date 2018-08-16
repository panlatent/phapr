<?php
/**
 * This a PHPDoc that can be parsed.
 *
 * @project Simple Build
 * @default init
 */

use Phapr\Module\Filesystem;
use Phapr\Module\Process;

$phapr = phapr('import self'); // Import Phapr, can use $phapr
$filesystem = phapr('import filesystem'); // Import filesystem module, can use $filesystem and inject task same name argument.

// The phapr() default returns build module.
phapr()->task('init', function(Filesystem $filesystem) {
    echo 'Checking composer vendor directory ... ' . PHP_EOL;
    if ($filesystem->exists('vendor')) {
        echoln('Vendor directory already exists!'); // Phapr provide a helper function.
    }
}, []);

phapr()->task('install', function() {
    echoln('Install something');
}, ['init']);

phapr()->task('build', function(Process $process) {
    echoln('Build something ...');

    $process->exec('ls -al');
}, ['install']);

phapr()->run('build');
