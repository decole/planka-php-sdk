<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->exclude(['var', 'tests/Support/_generated', 'tests/_output'])
    ->name('*.php')
    ->in([__DIR__.'/src', __DIR__.'/tests'])
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/var/.php-cs-fixer.cache')
    ->setRules([
        '@Symfony' => true,
        '@PER-CS2.0' => true,
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'function_declaration' => false,
        'declare_strict_types' => true,
        'set_type_to_cast' => true,
        'no_alternative_syntax' => ['fix_non_monolithic_code' => true],
    ])
    ->setFinder($finder)
;
