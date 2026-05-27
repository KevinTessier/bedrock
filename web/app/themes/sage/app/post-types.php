<?php

/**
 * Custom post types.
 */

namespace App;

/**
 * Register the "Book" custom post type.
 *
 * Lives under its own /books/ base, independent of the /articles/ permalink
 * structure used by regular posts:
 * - Archive: /books/         -> archive-book.blade.php
 * - Single:  /books/{slug}/  -> single-book.blade.php
 *
 * Rewrite rules are flushed on theme activation (see setup.php). After adding
 * this CPT, re-save Settings > Permalinks once if /books/ returns a 404.
 *
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @return void
 */
add_action('init', function () {
    register_post_type('book', [
        'labels' => [
            'name' => __('Books', 'sage'),
            'singular_name' => __('Book', 'sage'),
            'menu_name' => __('Books', 'sage'),
            'all_items' => __('All Books', 'sage'),
            'add_new' => __('Add Book', 'sage'),
            'add_new_item' => __('Add New Book', 'sage'),
            'edit_item' => __('Edit Book', 'sage'),
            'new_item' => __('New Book', 'sage'),
            'view_item' => __('View Book', 'sage'),
            'view_items' => __('View Books', 'sage'),
            'search_items' => __('Search Books', 'sage'),
            'not_found' => __('No books found.', 'sage'),
            'not_found_in_trash' => __('No books found in Trash.', 'sage'),
            'archives' => __('Books', 'sage'),
        ],
        'public' => true,
        'has_archive' => 'books',
        'rewrite' => [
            'slug' => 'books',
            'with_front' => false,
        ],
        'menu_icon' => 'dashicons-book-alt',
        'menu_position' => 5,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);
});
