<?php
/**
 * This a PHPDoc that can be parsed.
 *
 * @project Simple Build
 * @default init
 */

use Phapr\Module\Filesystem;

$phapr = phapr('import self'); // Import Phapr, can use $phapr
$filesystem = phapr('import filesystem'); // Import filesystem module, can use $filesystem and inject task same name argument.

// The phapr() default returns build module.
phapr()->task('init', function(Filesystem $filesystem) {
    if ($filesystem->exists('vendor')) {
        echo 'Vendor directory already exists!' . PHP_EOL;
    }
}, []);

phapr()->task('install', function() {
    echo 'Install something' . PHP_EOL;
}, ['init']);

phapr()->task('build', function() {
    echo 'Build something' . PHP_EOL;
}, ['install']);

phapr()->run('build');



