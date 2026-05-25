<?php

/**
 * Server render for the dal/citation block.
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$quote = wp_kses($attributes['quote'] ?? '', [
    'strong' => [],
    'em' => [],
    'br' => [],
]);

if (trim(strip_tags($quote)) === '') {
    return;
}

$firstName = trim((string) ($attributes['firstName'] ?? ''));
$lastName = trim((string) ($attributes['lastName'] ?? ''));
$author = trim($firstName . ' ' . $lastName);

echo \Roots\view('blocks.citation', compact('quote', 'author'))->render();
