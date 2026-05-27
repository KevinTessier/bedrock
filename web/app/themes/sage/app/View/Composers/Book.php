<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Book extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single-book',
    ];

    /**
     * The 4 most recent books, excluding the one being viewed.
     */
    public function related(): array
    {
        return get_posts([
            'post_type' => 'book',
            'posts_per_page' => 4,
            'post__not_in' => [get_queried_object_id()],
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
    }
}
