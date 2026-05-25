<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Card extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.card-post',
    ];

    /**
     * Le post de la card : passé explicitement via @include(..., ['post' => $post]),
     * sinon le post global (boucle principale). Aucun setup_postdata requis.
     */
    protected function post(): ?\WP_Post
    {
        return get_post($this->data->get('post'));
    }

    public function category(): ?\WP_Term
    {
        return get_the_category($this->post()?->ID)[0] ?? null;
    }

    public function thumbnail(): string
    {
        return get_the_post_thumbnail($this->post(), 'card-thumbnail', [
            'class' => 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105',
            'alt' => '',
            'loading' => 'lazy',
        ]);
    }

    public function permalink(): string
    {
        return (string) get_permalink($this->post());
    }

    public function title(): string
    {
        return get_the_title($this->post());
    }

    public function excerpt(): string
    {
        return get_the_excerpt($this->post());
    }

    public function date(): string
    {
        return get_the_date('', $this->post());
    }

    public function dateIso(): string
    {
        return get_the_date('c', $this->post());
    }
}
