<?php
$finder = Symfony\CS\Finder\DefaultFinder::create();

$config = Symfony\CS\Config\Config::create();
$config->level(null);
$config->fixers(
    array(
        'indentation',
        'linefeed',
        'trailing_spaces',
        'visibility',
        'php_closing_tag',
        'braces',
        'function_declaration',
        'elseif',
        'eof_ending',
        'unused_use',
        'psr1',
        'psr2',
        'psr4',
        'strict',
        'ordered_use',
        'align_double_arrow',
        'whitespacy_lines',
        'remove_lines_between_uses',
        'multiline_array_trailing_comma',
    )
);
$config->finder($finder);
return $config;
