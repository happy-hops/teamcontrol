<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    '@fontsource/barlow-condensed/700.css' => [
        'version' => '5.2.8',
        'type' => 'css',
    ],
    '@fontsource/barlow-condensed/800.css' => [
        'version' => '5.2.8',
        'type' => 'css',
    ],
    '@fontsource/dm-sans/400.css' => [
        'version' => '5.2.8',
        'type' => 'css',
    ],
    '@fontsource/dm-sans/500.css' => [
        'version' => '5.2.8',
        'type' => 'css',
    ],
    '@fontsource/dm-sans/600.css' => [
        'version' => '5.2.8',
        'type' => 'css',
    ],
    'bootstrap-icons/font/bootstrap-icons.css' => [
        'version' => '1.13.1',
        'type' => 'css',
    ],
];
