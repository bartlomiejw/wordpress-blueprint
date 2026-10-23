<?php
/**
 * Rector configuration: automated refactoring towards modern, typed PHP 8.2.
 *
 * Run `composer rector` (dry run, used in CI) or `composer rector:fix`.
 */

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/wp-content/mu-plugins',
        __DIR__ . '/wp-content/themes/custom-theme',
        __DIR__ . '/tests/phpunit',
    ])
    ->withSkip([
        __DIR__ . '/wp-content/themes/custom-theme/node_modules',
        __DIR__ . '/wp-content/themes/custom-theme/assets/dist',
        __DIR__ . '/wp-content/themes/custom-theme/build',
    ])
    ->withPhpSets(php82: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
    );
