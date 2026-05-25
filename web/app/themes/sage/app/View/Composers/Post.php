<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Post extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.page-header',
        'partials.content',
        'partials.content-*',
    ];

    /**
     * Retrieve the post title.
     */
    public function title(): string
    {
        if ($this->view->name() !== 'partials.page-header') {
            return get_the_title();
        }

        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }

            return __('Latest Posts', 'sage');
        }

        if (is_archive()) {
            return get_the_archive_title();
        }

        if (is_search()) {
            return sprintf(
                /* translators: %s is replaced with the search query */
                __('Search Results for %s', 'sage'),
                get_search_query()
            );
        }

        if (is_404()) {
            return __('Not Found', 'sage');
        }

        return get_the_title();
    }

    /**
     * Retrieve the pagination links.
     */
    public function pagination(): string
    {
        return wp_link_pages([
            'echo' => 0,
            'before' => '<p>'.__('Pages:', 'sage'),
            'after' => '</p>',
        ]);
    }
    /**
     * The first category of the current post.
     */
    public function category(): ?\WP_Term
    {
        return get_the_category(get_the_ID())[0] ?? null;
    }

    /**
     * The card thumbnail markup (empty string if no featured image).
     * Alt is intentionally empty: the title link carries the accessible name.
     */
    public function thumbnail(): string
    {
        return get_the_post_thumbnail(get_the_ID(), 'card-thumbnail', [
            'class' => 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105',
            'alt' => '',
            'loading' => 'lazy',
        ]);
    }

    /**
     * The post permalink.
     */
    public function permalink(): string
    {
        return (string) get_permalink();
    }

    /**
     * The post excerpt.
     */
    public function excerpt(): string
    {
        return get_the_excerpt();
    }

    /**
     * Human-readable publish date.
     */
    public function date(): string
    {
        return get_the_date();
    }

    /**
     * ISO 8601 publish date (for the <time datetime> attribute).
     */
    public function dateIso(): string
    {
        return get_the_date('c');
    }
}
