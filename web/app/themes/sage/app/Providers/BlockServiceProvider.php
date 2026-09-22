<?php

namespace App\Providers;

use App\Blocks\YouTubeEmbed;
use Illuminate\Support\ServiceProvider;

class BlockServiceProvider extends ServiceProvider
{
    /**
     * Block customisations to boot (one class per block).
     *
     * @var array<class-string>
     */
    protected array $blocks = [
        YouTubeEmbed::class,
        // VimeoEmbed::class,
        // …
    ];

    /**
     * Bootstrap the block customisations.
     */
    public function boot(): void
    {
        foreach ($this->blocks as $block) {
            $this->app->make($block)->register();
        }
    }
}
