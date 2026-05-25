<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * [RGESN 2.3] Conditionally load Tarteaucitron (cookie consent)
 * Only loads when tracking services are configured.
 * To enable: define('TARTEAUCITRON_ENABLED', true) in wp-config.php
 * or filter 'theme/needs_tarteaucitron' to return true.
 */
add_filter('theme/needs_tarteaucitron', function () {
    return defined('TARTEAUCITRON_ENABLED') && TARTEAUCITRON_ENABLED;
});


/**
 * [RGESN 7.5] Disable WordPress emojis
 * Saves: 2 HTTP requests + ~15 KB of JS
 */
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('emoji_svg_url', '__return_false');
});


/**
 * [RGESN 7.5] Clean wp_head from unnecessary elements
 */
add_action('init', function () {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
});


/**
 * [RGESN 7.3] Disable XML-RPC (security + performance)
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * [RGESN 7.5] Disable extra RSS comment feeds.
 */
add_filter('feed_links_show_comments_feed', '__return_false');

/**
 * [RGESN 7.5] Disable recent comments widget inline CSS.
 */
add_filter('show_recent_comments_widget_style', '__return_false');

/**
 * [Security] Remove WordPress version from RSS feeds.
 */
add_filter('the_generator', '__return_empty_string');

/**
 * [RGESN 7.5] Disable oEmbed discovery (reduces HTTP requests).
 */
add_filter('embed_oembed_discover', '__return_false');

/**
 * [RGESN 6.7] Remove query strings from static resources (better caching).
 */
add_filter('script_loader_src', function ($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}, 15);

add_filter('style_loader_src', function ($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}, 15);

/**
 * [RGESN 7.5] Dequeue Gutenberg styles on the front-end
 * The theme uses Tailwind, block-library styles are unnecessary for visitors.
 */
add_action('wp_enqueue_scripts', function () {
    // wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
}, 100);


/**
 * [RGESN 5.3] Block Google Fonts loaded by WordPress
 */
add_filter('wp_resource_hints', function ($urls, $relation_type) {
    if ($relation_type === 'dns-prefetch') {
        $urls = array_filter($urls, function ($url) {
            return strpos($url, 'fonts.googleapis.com') === false
                && strpos($url, 'fonts.gstatic.com') === false;
        });
    }
    return $urls;
}, 10, 2);

/**
 * [RGESN 6.7 / FCP] Preload main CSS + fonts
 * CSS is injected by @vite after wp_head(), so discovered late.
 * Preload allows the browser to download it in parallel from the start of head.
 */
add_action('wp_head', function () {
    $css = \Illuminate\Support\Facades\Vite::asset('resources/css/app.css');
    echo '<link rel="preload" href="' . esc_url($css) . '" as="style">' . "\n";

    // Add your font preloads here
    $fonts = [
        // \Illuminate\Support\Facades\Vite::asset('resources/fonts/YourFont.woff2'),
    ];

    foreach ($fonts as $font) {
        echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/woff2" crossorigin>' . "\n";
    }
}, 1);

/**
 * [LCP] Preload featured image on the homepage
 * The featured-article image is the largest visible element (LCP).
 * Preload downloads it in parallel with CSS instead of waiting for HTML rendering.
 */
add_action('wp_head', function () {
    if (! is_front_page()) {
        return;
    }

    $lcp = get_transient('theme_homepage_lcp_image');

    if ($lcp === false) {
        $sticky = get_option('sticky_posts');
        $post = ! empty($sticky)
            ? get_post($sticky[0])
            : get_posts(['posts_per_page' => 1, 'post_status' => 'publish'])[0] ?? null;

        if (! $post) {
            return;
        }

        $thumbnailId = get_post_thumbnail_id($post);

        if (! $thumbnailId) {
            return;
        }

        $lcp = [
            'src' => wp_get_attachment_image_url($thumbnailId, 'large'),
            'srcset' => wp_get_attachment_image_srcset($thumbnailId, 'large'),
        ];

        set_transient('theme_homepage_lcp_image', $lcp, HOUR_IN_SECONDS);
    }

    $sizes = '(max-width: 1024px) 100vw, 50vw';

    if (! empty($lcp['src'])) {
        echo '<link rel="preload" as="image" href="' . esc_url($lcp['src']) . '"'
            . (! empty($lcp['srcset']) ? ' imagesrcset="' . esc_attr($lcp['srcset']) . '"' : '')
            . ' imagesizes="' . esc_attr($sizes) . '"'
            . ' fetchpriority="high">' . "\n";
    }
}, 2);
/**
 * [RGESN 7.4] Invalidate LCP cache when posts are updated.
 */
add_action('save_post', function () {
    delete_transient('theme_homepage_lcp_image');
});

/**
 * [RGESN 4.11] Add native lazy loading on images
 */
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    if (!isset($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }
    return $attr;
});

/**
 * [RGESN 6.7] Add fetchpriority on LCP images
 */
add_filter('wp_content_img_tag', function ($image) {
    if (strpos($image, 'loading="eager"') !== false) {
        $image = str_replace('<img', '<img fetchpriority="high"', $image);
    }
    return $image;
});

/**
 * Clean archive title (remove "Category:" prefix)
 */
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    }

    return $title;
});

/**
 * [RGESN 7.5] Optimize WordPress Heartbeat
 * Disable on front-end (useless for visitors), slow down in admin.
 */
add_action('init', function () {
    if (! is_admin()) {
        wp_deregister_script('heartbeat');
    }
});

add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 120;

    return $settings;
});

/**
 * Register block pattern category and patterns.
 */
add_action('init', function () {
    register_block_pattern_category('ome', [
        'label' => __('Sage', 'sage'),
    ]);

    // Load all pattern files from the patterns/ directory
    $pattern_dir = get_theme_file_path('patterns');
    if (is_dir($pattern_dir)) {
        foreach (glob($pattern_dir . '/*.php') as $file) {
            $headers = get_file_data($file, [
                'title'         => 'Title',
                'slug'          => 'Slug',
                'categories'    => 'Categories',
                'description'   => 'Description',
                'viewportWidth' => 'Viewport Width',
            ]);

            if (empty($headers['slug']) || empty($headers['title'])) {
                continue;
            }

            ob_start();
            include $file;
            $content = ob_get_clean();

            register_block_pattern($headers['slug'], [
                'title'         => $headers['title'],
                'categories'    => array_map('trim', explode(',', $headers['categories'])),
                'description'   => $headers['description'],
                'viewportWidth' => (int) $headers['viewportWidth'] ?: 1280,
                'content'       => $content,
            ]);
        }
    }
});

/**
 * Register the custom block category for ACF blocks.
 *
 * @return array
 */
add_filter('block_categories_all', function ($categories) {
    return array_merge([
        [
            'slug' => 'custom',
            'title' => __('Custom Blocks', 'sage'),
            'icon' => 'star-filled',
        ],
        [
            'slug' => 'sage',
            'title' => __('Sage', 'sage'),
            'icon' => 'format-quote',
        ],
    ], $categories);
});


/**
 * Restrict Gutenberg blocks to essentials + custom ACF blocks.
 *
 * @return array
 */
add_filter('allowed_block_types_all', function () {
    $core = [
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/columns',
        'core/column',
        'core/buttons',
        'core/button',
        'core/group',
        'core/image',
        'core/gallery',
        'core/embed',
        'core/media-text',
        'core/quote',
    ];

    // Autorise tous les blocs custom du thème, lus depuis resources/blocks/*/block.json
    // (robuste : indépendant du préfixe de namespace choisi).
    $custom = [];

    foreach (glob(get_theme_file_path('resources/blocks/*/block.json')) as $file) {
        $meta = json_decode(file_get_contents($file), true);

        if (! empty($meta['name'])) {
            $custom[] = $meta['name'];
        }
    }

    return array_merge($core, $custom);
});


/**
 * [RGESN 3.6] CDN support for uploaded media.
 * To enable: define('CDN_URL', 'https://cdn.example.com') in wp-config.php
 */
if (defined('CDN_URL') && CDN_URL) {
    add_filter('wp_get_attachment_url', function ($url) {
        return str_replace(home_url(), CDN_URL, $url);
    });
}

/**
 * [RGAA 1.1 / 6.1] Guarantee an alternative text on the custom logo.
 * The logo image is the only content of the home link: without an alt,
 * the link is announced without a label by screen readers.
 */
add_filter('get_custom_logo_image_attributes', function ($attr) {
    if (empty($attr['alt'])) {
        $attr['alt'] = get_bloginfo('name', 'display');
    }

    return $attr;
});

/**
 * [RGAA 5.3] Add scope="col" to <th> elements in content.
 */
add_filter('the_content', function ($content) {
    if (! empty($content)) {
        $content = preg_replace('/<th(?![^>]*scope)/', '<th scope="col"', $content);
    }
    return $content;
}, 20);
