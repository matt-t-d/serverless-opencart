#!/usr/bin/env php
<?php

echo "vQmod cache file generation \n";

$sourcePath = __DIR__ . '/../../upload/';
require_once($sourcePath . 'config.php');
require_once($sourcePath . 'vqmod/vqmod.php');

VQMod::bootup(null, true, false);

echo "removing cache files \n";
unlink($sourcePath . 'vqmod/checked.cache');
unlink($sourcePath . 'vqmod/mods.cache');
array_map('unlink', glob($sourcePath . 'vqmod/vqcache/*.*'));

echo "Fetching files: \n";

$files = new RegexIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourcePath, FilesystemIterator::SKIP_DOTS | FilesystemIterator::KEY_AS_PATHNAME)
    ),
    '/(?!\.\.\/\.\.\/upload\/vqmod)(^.+\.(php|twig|tpl)$)/i',
    RecursiveRegexIterator::GET_MATCH
);

echo sprintf("%d files found \n", iterator_count($files));

$sourcePathLength = strlen($sourcePath);
foreach ($files as $path => $item) {
    VQMod::modCheck(substr($path, $sourcePathLength));
}

echo "Done! \n";
