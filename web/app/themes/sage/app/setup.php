<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Register custom blocks.
 */
add_action('init', function () {
    $blocks = glob(get_theme_file_path('resources/blocks/*'), GLOB_ONLYDIR);

    foreach ($blocks as $block) {
        register_block_type($block);
    }
});

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    /**
     * Custom image sizes optimized for the theme.
     * - card-thumbnail: article grid (3/4 aspect, 4 columns = ~320px)
     * - featured-image: featured article (50vw = ~640px)
     */
    add_image_size('card-thumbnail', 400, 533, true);
    add_image_size('featured-image', 800, 0, false);

    /**
     * Enable custom logo support (Appearance > Customize > Site Identity).
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#custom-logo
     */
    add_theme_support('custom-logo', [
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Enable editor styles and restrict Gutenberg customization.
     */
    add_theme_support('editor-styles');
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-font-sizes');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * ==========================================================================
 * Eco-design: Image optimizations (RGESN)
 * ==========================================================================
 */

/**
 * Remove WordPress SVG duotone filters (reduces DOM size).
 */
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

/**
 * Dequeue core-block-supports in footer.
 */
add_action('wp_footer', function () {
    wp_dequeue_style('core-block-supports');
}, 5);

/**
 * Remove unused image sizes to save disk space.
 * Keeps only: thumbnail, medium, medium_large, large + custom sizes.
 */
add_filter('intermediate_image_sizes_advanced', function ($sizes) {
    $kept_sizes = ['thumbnail', 'medium', 'medium_large', 'large', 'card-thumbnail', 'featured-image'];

    foreach ($sizes as $size => $value) {
        if (! in_array($size, $kept_sizes)) {
            unset($sizes[$size]);
        }
    }

    return $sizes;
});


/**
 * ==========================================================================
 * Eco-design: Script optimizations (RGESN)
 * ==========================================================================
 */

/**
 * Add defer to scripts for better loading performance.
 * Excludes jQuery which may be needed synchronously.
 */
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    $exclude = ['jquery', 'jquery-core', 'jquery-migrate'];

    if (in_array($handle, $exclude)) {
        return $tag;
    }

    if (! is_admin()) {
        $tag = str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}, 10, 3);

/**
 * Remove X-Pingback header.
 */
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});


/**
 * Limit post revisions to save database space.
 */
if (! defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

/**
 * Set autosave interval to 5 minutes (instead of 60 seconds).
 */
if (! defined('AUTOSAVE_INTERVAL')) {
    define('AUTOSAVE_INTERVAL', 300);
}