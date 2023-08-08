<?php

declare(strict_types=1);

/**
 * @author https://github.com/jskowronski39, modified by https://github.com/veteran29
 * @license MIT
 */
use Symfony\Component\Finder\Finder;

require_once __DIR__.'/../vendor/autoload.php';

$testDirs = [
    'functional'.DIRECTORY_SEPARATOR,
    'unit'.DIRECTORY_SEPARATOR,
];

$testFileSuffixes = [
    'Test.php',
];

$finder = (new Finder())
    ->files()
    ->name(array_map(fn ($x) => "*{$x}", $testFileSuffixes))
    ->notName(['Abstract*', '*ScenarioTest.php'])
    ->in(__DIR__)
;

$mismatchedTestNamespaces = 0;

foreach ($finder as $testFile) {
    $testFilePath = $testFile->getPathname();
    $testSubjectFilePath = $testFilePath;

    $testSubjectFilePath = str_replace('tests'.DIRECTORY_SEPARATOR, 'src'.DIRECTORY_SEPARATOR, $testSubjectFilePath);

    $testSubjectFilePath = preg_replace('#'.DIRECTORY_SEPARATOR.'\w+?ActionTest\.php$#', '.php', $testSubjectFilePath);

    $testSubjectFilePath = str_replace($testDirs, '', $testSubjectFilePath);
    $testSubjectFilePath = str_replace($testFileSuffixes, '.php', $testSubjectFilePath);

    if (!file_exists($testSubjectFilePath)) {
        $testFilePath = str_replace(dirname(__DIR__), '', $testFilePath);
        $testSubjectFilePath = str_replace(dirname(__DIR__), '', $testSubjectFilePath);

        echo 'Subject of test:'.PHP_EOL;
        echo $testFilePath.PHP_EOL.PHP_EOL;
        echo 'Is expected to be found in:'.PHP_EOL;
        echo $testSubjectFilePath.PHP_EOL;
        echo str_repeat('-', 100).PHP_EOL.PHP_EOL;

        ++$mismatchedTestNamespaces;
    }
}

if ($mismatchedTestNamespaces > 0 && ('true' !== ($argv[1] ?? false))) {
    exit(1);
}

exit(0);
