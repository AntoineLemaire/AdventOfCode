<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PER-CS' => true,
        '@PHP82Migration' => true,
        'ordered_class_elements' => true,
        'concat_space' => [
            'spacing' => 'one'
        ]
    ])
    ->setFinder($finder)
    ;
