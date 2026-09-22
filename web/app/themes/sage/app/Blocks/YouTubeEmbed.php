<?php

namespace App\Blocks;

/**
 * YouTube embed -> click-to-load facade.
 *
 * - RGPD  : zero request to Google until the user clicks (self-hosted thumbnail).
 * - RGESN : no third-party iframe/JS loaded up front, only one local image.
 * - RGAA  : real <button> with an explicit name, keyboard-operable; the iframe
 *           injected on click carries a title and receives focus (youtube-facade.js).
 */
class YouTubeEmbed
{
    /**
     * Register the block hooks.
     */
    public function register(): void
    {
        add_filter('render_block_core/embed', [$this, 'render'], 10, 2);
    }

    /**
     * Replace the YouTube embed block output with the facade.
     */
    public function render(string $content, array $block): string
    {
        if (($block['attrs']['providerNameSlug'] ?? '') !== 'youtube') {
            return $content;
        }

        $url = $block['attrs']['url'] ?? '';

        if (! preg_match('~(?:youtu\.be/|v=|/embed/|/shorts/)([\w-]{11})~', $url, $m)) {
            return $content;
        }

        $id = $m[1];
        $data = $this->data($id, $url);

        $label = $data['title']
            ? sprintf(__('Play the video: %s', 'sage'), $data['title'])
            : __('Play the YouTube video', 'sage');

        $thumb = $data['thumb']
            ? sprintf(
                '<img class="yt-facade__thumb" src="%s" alt="" loading="lazy" decoding="async">',
                esc_url($data['thumb'])
            )
            : '';

        return sprintf(
            '<figure class="wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio">'
            . '<div class="wp-block-embed__wrapper">'
            . '<button type="button" class="yt-facade" data-yt-id="%1$s" data-yt-title="%2$s">'
            . '%3$s'
            . '<span class="yt-facade__play" aria-hidden="true"></span>'
            . '<span class="sr-only">%4$s</span>'
            . '</button>'
            . '</div>'
            . '</figure>',
            esc_attr($id),
            esc_attr($data['title'] ?: 'YouTube'),
            $thumb,
            esc_html($label)
        );
    }

    /**
     * Resolve a video's title + a SELF-HOSTED thumbnail (cached one month).
     *
     * The thumbnail is downloaded server-side once and served from our own
     * domain, so the visitor's browser never contacts Google before any click.
     *
     * @return array{title: string, thumb: string}
     */
    protected function data(string $id, string $url): array
    {
        $cache_key = 'yt_facade_' . $id;
        $cached = get_transient($cache_key);

        if (is_array($cached)) {
            return $cached;
        }

        $title = '';
        $remote_thumb = "https://i.ytimg.com/vi/{$id}/hqdefault.jpg";

        // Server-side oEmbed (cached by WP) — never hits the visitor's browser.
        $oembed = _wp_oembed_get_object();
        $oembed_data = $oembed->get_data($url);

        if ($oembed_data) {
            $title = $oembed_data->title ?? '';
            $remote_thumb = $oembed_data->thumbnail_url ?? $remote_thumb;
        }

        // Sideload the thumbnail into the media library (once).
        $thumb = '';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $tmp = download_url($remote_thumb);

        if (! is_wp_error($tmp)) {
            $file = ['name' => "youtube-{$id}.jpg", 'tmp_name' => $tmp];
            $attachment_id = media_handle_sideload($file, 0, $title ?: "YouTube {$id}");

            if (is_wp_error($attachment_id)) {
                @unlink($tmp);
            } else {
                $thumb = wp_get_attachment_image_url($attachment_id, 'large') ?: '';
            }
        }

        $result = ['title' => $title, 'thumb' => $thumb];
        set_transient($cache_key, $result, MONTH_IN_SECONDS);

        return $result;
    }
}
