<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Archive extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'index',
        'search',
        'archive-book',
    ];

    /**
     * RGAA-friendly archive pagination.
     *
     * - aria-current="page" is added by WordPress on the current item.
     * - `before_page_number` gives screen readers explicit "Page N" link names.
     * - prev/next carry explicit text.
     */
    public function pagination(): string
    {
        return (string) paginate_links([
            'type' => 'list',
            'mid_size' => 1,
            'prev_text' => __('Previous page', 'sage'),
            'next_text' => __('Next page', 'sage'),
            'before_page_number' => '<span class="sr-only">'.__('Page', 'sage').' </span>',
        ]);
    }
}
