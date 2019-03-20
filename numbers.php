<?php

require_once __DIR__ . '/vendor/autoload.php';

use varnautov\numbers\classes\Parser;
use varnautov\numbers\classes\Form;
use varnautov\numbers\classes\ErrorException;

/**
 * Code
 */
if (count($argv) < 3) {
    echo 'Params:', PHP_EOL, '  inputFile needle [sort]', PHP_EOL;
    echo 'Example:', PHP_EOL, '  php numbers.php input.txt 1 asc', PHP_EOL;
    exit;
}
try {
    $t = microtime(1);

    // params
    $filename = $argv[1] ?? null;
    $needle = $argv[2] ?? null;
    $sort = $argv[3] ?? null;
    $parser = Parser::class;
    // run
    $form = new Form(compact('filename', 'needle', 'sort', 'parser'));
    $form->run();

    echo 'Done.', PHP_EOL;
    echo sprintf('Total time: %01.5f seconds.', microtime(1) - $t), PHP_EOL;
    echo sprintf('Total memory: %01.5f Mb.', memory_get_usage()/1024/1024), PHP_EOL;
} catch (ErrorException $e) {
    echo 'Error: ', $e->getMessage(), PHP_EOL;
}
