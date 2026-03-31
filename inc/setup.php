<?php

// if accessed directly, then exit
if (!defined('ABSPATH')) {
    exit;
}


class simplitheme_setup
{
    function __construct()
    {
        add_action('init', array($this, 'head_cleanup'));
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'load_stylesheets'));
        add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'remove_global_styles'), 100);
        add_action('wp_body_open', array($this, 'skip_link'));
        add_filter('the_content_more_link', array($this, 'read_more_link'));
        add_filter('excerpt_more', array($this, 'excerpt_read_more_link'));
        add_filter('document_title_separator', array($this, 'document_title_separator'));
        add_filter('the_title', array($this, 'post_title'));
        add_filter('wpseo_metabox_prio', array($this, 'yoasttobottom'));
        add_action('admin_menu', array($this, 'change_post_label'));
        add_action('init', array($this, 'change_post_object'));
        add_action('init', array($this, 'register_menus'));
        add_action('init', array($this, 'register_image_sizes'));
        add_action('widgets_init', array($this, 'register_widget_areas'));
        add_action('after_setup_theme', array($this, 'custom_logo'));
        add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));
        add_action('admin_init', array($this, 'disable_theme_editor'));



        // Disable gutenberg
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_filter('use_block_editor_for_post_type', '__return_false', 10);

        // ACF
        add_filter('acf/settings/enable_post_types', '__return_false');
        add_filter('acf/settings/enable_options_pages_ui', '__return_false');
    }

    function head_cleanup()
    {
        // Remove WP version and WooCommerce version
        // add_filter('the_generator', array($this, 'remove_wp_version'));
        remove_action('wp_head', 'wp_generator');

        // Remove the EditURI/RSD
        remove_action('wp_head', 'rsd_link');

        // Remove emojis
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Remove the Windows Live Writer
        remove_action('wp_head', 'wlwmanifest_link');
        remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

        // Remove page/post's short links
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Remove feed links
        remove_action('wp_head', 'feed_links', 2);

        // Remove comment feeds
        remove_action('wp_head', 'feed_links_extra', 3);

        // Remove PREV and NEXT links
        remove_action('wp_head', 'adjacent_posts_rel_link');

        // Disable REST API link tag
        remove_action('wp_head', 'rest_output_link_wp_head', 10);

        // Disable oEmbed Discovery Links
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

        // Disable REST API link in HTTP headers
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    }

    function theme_setup()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form'));
        add_theme_support('woocommerce');
        remove_theme_support('widgets-block-editor');
        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1920;
        }
    }

    function load_stylesheets()
    {
        $style_file = get_template_directory_uri() . '/style.css';
        $style_file_time = filemtime(get_template_directory() . '/style.css');
        wp_register_style('simpli-style', $style_file, array(), $style_file_time, 'all');
        wp_enqueue_style('simpli-style');
    }

    function load_scripts()
    {
        $script_file = get_template_directory_uri() . '/assets/js/script.js';
        $script_file_time = filemtime(get_template_directory() . '/assets/js/script.js');
        wp_register_script('simpli-script', $script_file, array(), $script_file_time, true);
        wp_enqueue_script('simpli-script');
    }

    function remove_global_styles()
    {
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block CSS
    }

    function remove_wp_version()
    {
        return '';
    }

    function skip_link()
    {
        echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__('Skip to the content', 'translate') . '</a>';
    }

    function read_more_link()
    {
        if (!is_admin()) {
            return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">' . sprintf(__('...%s', 'blankslate'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
        }
    }

    function excerpt_read_more_link($more)
    {
        if (!is_admin()) {
            global $post;
            return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . sprintf(__('...%s', 'blankslate'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
        }
    }

    function document_title_separator($sep)
    {
        $sep = esc_html('|');
        return $sep;
    }

    function post_title($title)
    {
        if ($title == '') {
            return esc_html('...');
        } else {
            return wp_kses_post($title);
        }
    }

    function yoasttobottom()
    {
        return 'low';
    }

    function change_post_label()
    {
        global $menu;
        global $submenu;
        $menu[5][0] = 'News';
        $submenu['edit.php'][5][0] = 'News';
        $submenu['edit.php'][10][0] = 'Add News';
        $submenu['edit.php'][16][0] = 'News Tags';
    }

    function change_post_object()
    {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'News';
        $labels->singular_name = 'News';
        $labels->add_new = 'Add News';
        $labels->add_new_item = 'Add News';
        $labels->edit_item = 'Edit News';
        $labels->new_item = 'News';
        $labels->view_item = 'View News';
        $labels->search_items = 'Search News';
        $labels->not_found = 'No News found';
        $labels->not_found_in_trash = 'No News found in Trash';
        $labels->all_items = 'All News';
        $labels->menu_name = 'News';
        $labels->name_admin_bar = 'News';
    }

    function register_menus()
    {
        $menus = MENUS;
        if (isarr($menus)) {
            foreach ($menus as $location => $description) {
                register_nav_menu($location, __($description, 'translate'));
            }
        }
    }

    function register_image_sizes()
    {
        $image_sizes = IMAGE_SIZES;
        if (isarr($image_sizes)) {
            foreach ($image_sizes as $name => $size) {
                add_image_size($name, $size['width'], $size['height'], $size['crop']);
            }
        }
    }

    function register_widget_areas() {
        $widgets = WIDGETS;
        if (isarr($widgets)) {
            foreach ($widgets as $id => $widget) {
                register_sidebar(
                    [
                        'id'            => $id,
                        'name'          => $widget,
                        'description'   => '',
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h3 class="widget-title">',
                        'after_title'   => '</h3>',
                    ]
                );
            }
        }
    }

    function custom_logo()
    {
        $logo_size = LOGO_SIZE;
        if(isarr($logo_size)) {
            $custom_logo = array(
                'height' => ifne($logo_size, 'height'),
                'width' => ifne($logo_size, 'height'),
                'flex-height' => true,
                'flex-width' => true,
                'header-text' => array('site-title', 'site-description'),
            );
            add_theme_support('custom-logo', $custom_logo);
        }
    }


    function remove_dashboard_widgets()
    {
        /**
         * Removes the "Right Now" widget that tells you post/comment counts
         * and what theme you're using.
         */
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');

        /**
         * Removes the recent comments widget
         */
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        /**
         * Removes the incoming links widget.
         */
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');

        /**
         * Removes the plugins widgets that displays the most popular,
         * newest, and recently updated plugins
         */
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');

        /**
         * Removes the quick press widget that allows you post right from the dashboard
         */
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');

        /**
         * Removes the widget containing the list of recent drafts
         */
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');

        /**
         * Removes the "WordPress Blog" widget
         */
        remove_meta_box('dashboard_primary', 'dashboard', 'side');

        /**
         * Removes the "Other WordPress News" widget
         */
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');

        /**
         * Removes the "Activity" widget
         */
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');

        /**
         * Removes the "WooCommerce Setup" widget
         */
        remove_meta_box('wc_admin_dashboard_setup', 'dashboard', 'normal');

        /**
         * Removes the "Welcome to WordpPress" widget
         */
        remove_action('welcome_panel', 'wp_welcome_panel');
    }

    function disable_theme_editor() {
        define('DISALLOW_FILE_EDIT', TRUE);
    }
}

$simplitheme_setup = new simplitheme_setup();