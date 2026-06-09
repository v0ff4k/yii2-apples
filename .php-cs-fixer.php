<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/common',
        __DIR__ . '/console',
        __DIR__ . '/backend',
        __DIR__ . '/frontend',
    ])
    ->exclude([
        'vendor',
        'runtime',
        'web',
        'tests',
        '_output',
        '_data',
        '_support',
    ])
    ->notName('*.php~')
    ->notName('*.swp');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => true,
        'braces' => true,
        'cast_spaces' => true,
        'class_attributes_separation' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'elseif' => true,
        'encoding' => true,
        'function_typehint_space' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_string' => true,
        'no_unset_cast' => true,
        'no_useless_return' => true,
        'non_printable_character' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
        'phpdoc_align' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => [
            'ignored_tags' => ['const', 'property', 'param', 'var'], // укажите нужные теги
        ],
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'return_type_declaration' => true,
        'single_class_element_per_statement' => ['elements' => ['const', 'property']],
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setUsingCache(true)
    ->setRiskyAllowed(true);