<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class NotFound extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '404',
    ];

    /**
     * Site home URL.
     */
    public function homeUrl(): string
    {
        return home_url('/');
    }

    /**
     * URL of the posts (articles) page, falling back to the home page.
     */
    public function articlesUrl(): string
    {
        $postsPage = (int) get_option('page_for_posts');

        return $postsPage
            ? (string) get_permalink($postsPage)
            : $this->homeUrl();
    }

    /**
     * URL of the Book CPT archive, falling back to the home page.
     */
    public function booksUrl(): string
    {
        return (string) (get_post_type_archive_link('book') ?: $this->homeUrl());
    }
}
