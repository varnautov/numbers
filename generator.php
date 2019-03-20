<?php

require_once __DIR__ . '/vendor/autoload.php';

$filename = $argv[1] ?? 'input.txt';
$count = $argv[2] ?? 1e6;

$t = microtime(1);

$fh = fopen($filename, "w");
for ($i = $count; $i > 0; $i--) {
    fwrite($fh, $i . PHP_EOL);
}
fclose($fh);

echo 'Done.', PHP_EOL;
echo sprintf('Total time: %01.5f seconds.', microtime(1) - $t), PHP_EOL;
echo sprintf('Total memory: %01.5f Mb.', memory_get_usage()/1024/1024), PHP_EOL;
