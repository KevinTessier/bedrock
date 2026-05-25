<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Single extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single',
    ];

    /**
     * The 4 most recent posts, excluding the one being viewed.
     */
    public function related(): array
    {
        return get_posts([
            'post_type' => 'post',
            'posts_per_page' => 4,
            'post__not_in' => [get_queried_object_id()],
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
    }
}
