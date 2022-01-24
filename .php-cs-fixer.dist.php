<?php

return (new PhpCsFixer\Config())
    ->setIndent('  ')
    ->setRules([
        '@Symfony'                    => true,
        'binary_operator_spaces'      => [
            'default'   => 'align',
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '|'  => 'no_space',
                '+'  => 'single_space',
                '-'  => 'single_space',
                '*'  => 'single_space',
                '/'  => 'single_space',
                '??' => 'single_space',
            ],
        ],
        'cast_spaces'                 => ['space' => 'none'],
        'class_attributes_separation' => ['elements' => ['const' => 'only_if_meta']],
        'concat_space'                => ['spacing' => 'one'],
        'increment_style'             => false,
        'ordered_imports'             => ['imports_order' => ['class', 'function', 'const']],
        'single_line_throw'           => false,
        'yoda_style'                  => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . DIRECTORY_SEPARATOR . 'src')
    );
