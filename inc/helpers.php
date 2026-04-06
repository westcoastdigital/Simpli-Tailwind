<?php

// if accessed directly, then exit
if (!defined('ABSPATH')) {
    exit;
}


if (!function_exists('simpli_schema_type')) {
    /**
     * Add schema type to the body
     *
     * Usage <?php simpli_schema_type(); ?> in the body tag
     *
     */
    function simpli_schema_type()
    {
        $schema = 'https://schema.org/';
        if (is_single()) {
            $type = "Article";
        } elseif (is_author()) {
            $type = 'ProfilePage';
        } elseif (is_search()) {
            $type = 'SearchResultsPage';
        } else {
            $type = 'WebPage';
        }
        echo 'itemscope itemtype="' . esc_url($schema) . esc_attr($type) . '"';
    }
}

if (!function_exists('simpli_numbered_pagination')) {
    /**
     * Add numbered pagination to archive pages etc
     *
     * Usage <?php simpli_numbered_pagination(); ?> after the loop
     *
     */
    function simpli_numbered_pagination()
    {

        if (is_singular())
            return;

        global $wp_query;

        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1)
            return;

        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $max   = intval($wp_query->max_num_pages);

        /** Add current page to the array */
        if ($paged >= 1)
            $links[] = $paged;

        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (($paged + 2) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<nav class="flex justify-center my-8"><ul class="flex items-center gap-1">' . "\n";

        $base_item_class   = 'flex items-center justify-center w-10 h-10 rounded transition-colors duration-200';
        $page_link_class   = $base_item_class . ' text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium text-sm';
        $active_link_class = $base_item_class . ' bg-primary text-white font-semibold text-sm pointer-events-none';
        $arrow_link_class  = $base_item_class . ' text-gray-500 hover:bg-gray-100 hover:text-gray-900';
        $ellipsis_class    = 'flex items-center justify-center w-10 h-10 text-gray-400 text-sm select-none';

        $icon_prev = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>';
        $icon_next = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>';

        /** Previous Post Link */
        if (get_previous_posts_link())
            printf(
                '<li><a href="%s" class="' . $arrow_link_class . '" aria-label="Previous page">%s</a></li>' . "\n",
                get_previous_posts_page_url(),
                $icon_prev
            );

        /** Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $link_class = 1 == $paged ? $active_link_class : $page_link_class;
            printf('<li><a href="%s" class="' . $link_class . '">1</a></li>' . "\n", esc_url(get_pagenum_link(1)));

            if (!in_array(2, $links))
                echo '<li><span class="' . $ellipsis_class . '">…</span></li>' . "\n";
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $link_class = $paged == $link ? $active_link_class : $page_link_class;
            printf('<li><a href="%s" class="' . $link_class . '">%s</a></li>' . "\n", esc_url(get_pagenum_link($link)), $link);
        }

        /** Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<li><span class="' . $ellipsis_class . '">…</span></li>' . "\n";

            $link_class = $paged == $max ? $active_link_class : $page_link_class;
            printf('<li><a href="%s" class="' . $link_class . '">%s</a></li>' . "\n", esc_url(get_pagenum_link($max)), $max);
        }

        /** Next Post Link */
        if (get_next_posts_link())
            printf(
                '<li><a href="%s" class="' . $arrow_link_class . '" aria-label="Next page">%s</a></li>' . "\n",
                get_next_posts_page_url(),
                $icon_next
            );

        echo '</ul></nav>' . "\n";
    }
}

/**
 * Helper: get the URL for the previous posts page (used in simpli_numbered_pagination)
 */
if (!function_exists('get_previous_posts_page_url')) {
    function get_previous_posts_page_url()
    {
        global $wp_query;
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        return $paged > 1 ? esc_url(get_pagenum_link($paged - 1)) : '';
    }
}

/**
 * Helper: get the URL for the next posts page (used in simpli_numbered_pagination)
 */
if (!function_exists('get_next_posts_page_url')) {
    function get_next_posts_page_url()
    {
        global $wp_query;
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $max   = intval($wp_query->max_num_pages);
        return $paged < $max ? esc_url(get_pagenum_link($paged + 1)) : '';
    }
}

if (!function_exists('ifne')) {
    /**
     * Checks to see if a key of the array exists, and returns it if it does.
     *
     * Returns '' by default.
     *
     */
    function ifne($var, $index, $default = '')
    {

        if (is_array($var)) {

            return (array_key_exists($index, $var) ? $var[$index] : $default);
        } else {

            return $default;
        }
    }
}

if (!function_exists('phoneurl')) {
    /**
     * Converts a phone number to a tel: link.
     *
     * Removes all non integer characters, strips the leading 0, and prepends the country code.
     *
     * Returns '' by default.
     *
     */
    function phoneurl($number, $country_code = '61')
    {
        if (!$number) {
            return '';
        }
        $number = preg_replace('/[^0-9]/', '', $number);
        $number = ltrim($number, '0');
        return 'tel:+' . $country_code . $number;
    }
}

if (!function_exists('isarr')) {
    /**
     * Checks to see if is valid array and is not empty
     *
     * Returns false by default.
     *
     */
    function isarr($arr, $default = false)
    {

        if (is_array($arr) && count($arr) > 0) {
            return true;
        } else {

            return $default;
        }
    }
}

if (!function_exists('get_time_ago')) {
    /**
     * Get the time ago from a post
     *
     * Returns a string
     *
     */
    function get_time_ago($post_id)
    {
        $post_date = get_the_date('Y-m-d H:i:s', $post_id);
        $post_time = strtotime($post_date);
        $current_time = current_time('timestamp');
        $time_difference = $current_time - $post_time;
        $minutes = round($time_difference / 60);
        $hours = round($time_difference / 3600);
        $days = round($time_difference / 86400);
        $weeks = round($time_difference / 604800);
        $months = round($time_difference / 2628000);
        $years = round($time_difference / 31536000);

        if ($minutes < 60) {
            if ($minutes > 1) {
                return $minutes . 'mins ago';
            } else {
                return $minutes . 'min ago';
            }
        } elseif ($hours < 24) {
            if ($hours > 1) {
                return $hours . 'hrs ago';
            } else {
                return $hours . 'hr ago';
            }
        } elseif ($days < 7) {
            if ($days > 1) {
                return $days . 'days ago';
            } else {
                return $days . 'day ago';
            }
        } elseif ($weeks < 4) {
            if ($weeks > 1) {
                return $weeks . 'wks ago';
            } else {
                return $weeks . 'wk ago';
            }
        } elseif ($months < 12) {
            if ($months > 1) {
                return $months . 'mths ago';
            } else {
                return $months . 'mth ago';
            }
        } elseif ($years < 3) {
            $remaining_months = $months % 12;
            if ($remaining_months == 0) {
                if ($years > 1) {
                    return $years . 'yrs ago';
                } else {
                    return $years . 'yr ago';
                }
            } else {
                if ($years > 1) {
                    if ($remaining_months > 1) {
                        return $years . 'yrs and ' . $remaining_months . 'mths ago';
                    } else {
                        return $years . 'yr and ' . $remaining_months . 'mth ago';
                    }
                } else {
                    if ($remaining_months > 1) {
                        return $years . 'yr and ' . $remaining_months . 'mths ago';
                    } else {
                        return $years . 'yr and ' . $remaining_months . 'mth ago';
                    }
                }
            }
        } else {
            return $years . 'yr ago';
        }
    }
}

if (!function_exists('acfimg')) {
    /**
     * Get the ACF image URL
     *
     * Returns a string
     *
     */
    function acfimg($image, $size = 'full')
    {
        if (isarr($image)) {
            if ($size == 'full') {
                return $image['url'];
            } else {
                return $image['sizes'][$size];
            }
        } else {
            return $image;
        }
    }
}

if (!function_exists('acfposts')) {
    function acfposts(array $posts = [], string $post_type = 'post', int $min = 3, int $fallback_per_page = 10): array
    {
        // Ensure valid array of posts
        $posts = is_array($posts) ? $posts : [];

        // Filter out empty values (ACF sometimes returns false/null entries)
        $posts = array_filter($posts);

        $current_count = count($posts);

        // If we already have enough, return early
        if ($current_count >= $min) {
            return $posts;
        }

        // Calculate how many we need
        $needed = $min - $current_count;

        // Get fallback posts (exclude already selected ones if possible)
        $exclude_ids = array_map(function ($p) {
            return is_object($p) ? $p->ID : (int) $p;
        }, $posts);

        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => max($needed, $fallback_per_page),
            'post_status'    => 'publish',
            'post__not_in'   => $exclude_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $fallback = get_posts($args);

        // Merge and trim to required minimum
        $merged = array_merge($posts, $fallback);

        return array_slice($merged, 0, $min);
    }
}

if (!function_exists('acfbtn')) {
    /**
     * Get the ACF link field and convert to a button
     *
     * Returns a string
     *
     */
    function acfbtn($field = [], $classes = '')
    {

        $content = '';

        if (isarr($field)) {
            $text = ifne($field, 'title');
            $link = ifne($field, 'url');
            $target = ifne($field, 'target');

            if ($text != '' && $link != '') {
                if ($target == '') {
                    $target = '_self';
                    $content = '<a href="' . $link . '" class="' . $classes . '" target="' . $target . '">' . $text . '</a>';
                } else {
                    $content = '<a href="' . $link . '" class="' . $classes . '">' . $text . '</a>';
                }
            }
        }

        return $content;
    }
}

if (!function_exists('calculate_reading_time')) {
    /**
     * Calculate the average reading time for a post
     *
     * Usage example with the main content only (default behavior): echo <?= 'Estimated reading time: ' . calculate_reading_time(get_the_ID()); ?>
     * Usage example with a single ACF field: <?= 'Estimated reading time: ' . calculate_reading_time(get_the_ID(), array('your_acf_field_name')); ?>
     * Usage example with multiple fields, including ACF fields and/or other custom fields: <?= echo 'Estimated reading time: ' . calculate_reading_time(get_the_ID(), array('your_acf_field_name', 'another_custom_field')); ?>
     *
     * Returns a string formatted as "x minute(s)" or "x hour(s) and x minute(s)"
     *
     */
    function calculate_reading_time($post_id, $fields = array('the_content'))
    {
        // Set default content
        $content = '';

        // Average reading speed (words per minute)
        $reading_speed = 225;

        // Loop through the fields and concatenate the content
        foreach ($fields as $field) {
            if ($field == 'the_content') {
                // Get the main post content
                $content .= get_post_field('post_content', $post_id) . ' ';
            } else {
                // Get the content from ACF or other custom fields
                $content .= get_post_meta($post_id, $field, true) . ' ';
            }
        }

        // Strip shortcodes and HTML tags to get a clean word count
        $clean_content = strip_tags(strip_shortcodes($content));

        // Calculate word count
        $word_count = str_word_count($clean_content);

        // If there are words, calculate the reading time
        if ($word_count > 0) {

            // Calculate reading time in minutes
            $reading_time_minutes = ceil($word_count / $reading_speed);

            // Format reading time based on duration
            if ($reading_time_minutes > 60) {
                $hours = floor($reading_time_minutes / 60);
                $minutes = $reading_time_minutes % 60;
                $reading_time = $hours . ' hour' . ($hours > 1 ? 's' : '');
                if ($minutes > 0) {
                    $reading_time .= ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                }
            } else {
                $reading_time = $reading_time_minutes . ' minute' . ($reading_time_minutes > 1 ? 's' : '');
            }

            // Return the formatted reading time
            return $reading_time;
        } else {
            return;
        }
    }
}

if (!function_exists('simpli_breadcrumbs')) {    
    /**
     * Custom breadcrumbs for site
     * 
     * Usage echo simpli_breadcrumbs();
     *
     * @return void
     */
    function simpli_breadcrumbs()
    {
        $breadcrumbs = '';

        // return empty if home or blog page
        if (is_home() || is_front_page()) {
            return;
        }

        $breadcrumbs .= '<nav aria-label="breadcrumb">';
        $breadcrumbs .= '<ol class="breadcrumb justify-content-center">';

        // Home link
        $breadcrumbs .= '<li class="breadcrumb-item"><a class="text-white" href="' . home_url() . '">Home</a></li>';

        if (is_category() || is_single()) {
            $category = get_the_category();
            if ($category) {
                $breadcrumbs .= '<li class="breadcrumb-item"><a class="text-white" href="' . get_category_link($category[0]->term_id) . '">' . esc_html($category[0]->name) . '</a></li>';
            }
            if (is_single()) {
                $breadcrumbs .= '<li class="breadcrumb-item text-white active" aria-current="page">' . get_the_title() . '</li>';
            }
        } elseif (is_page()) {
            global $post;
            if ($post->post_parent) {
                $ancestors = array_reverse(get_post_ancestors($post->ID));
                foreach ($ancestors as $ancestor) {
                    $breadcrumbs .= '<li class="breadcrumb-item"><a class="text-white" href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                }
            }
            $breadcrumbs .= '<li class="breadcrumb-item text-white active" aria-current="page">' . get_the_title() . '</li>';
        } elseif (is_archive()) {
            $breadcrumbs .= '<li class="breadcrumb-item text-white active" aria-current="page">' . post_type_archive_title('', false) . '</li>';
        } elseif (is_search()) {
            $breadcrumbs .= '<li class="breadcrumb-item text-white active" aria-current="page">Search results for "' . get_search_query() . '"</li>';
        } elseif (is_404()) {
            $breadcrumbs .= '<li class="breadcrumb-item text-white active" aria-current="page">404 Not Found</li>';
        }

        $breadcrumbs .= '</ol>';
        $breadcrumbs .= '</nav>';

        return $breadcrumbs;
    }
}

function render_stars(float $rating): void
{
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $icon = 'star';
            $fill = 1;
        } elseif ($rating >= $i - 0.5) {
            $icon = 'star_half';
            $fill = 1;
        } else {
            $icon = 'star';
            $fill = 0;
        }
        echo '<span class="material-symbols-outlined text-3xl" style="font-variation-settings: \'FILL\' ' . $fill . ';">' . $icon . '</span>';
    }
}

function sw_social_icons()
{
    $icons = [
        'twitter' => '<svg width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M16.8198 20.7684L3.75317 3.96836C3.44664 3.57425 3.72749 3 4.22678 3H6.70655C6.8917 3 7.06649 3.08548 7.18016 3.23164L20.2468 20.0316C20.5534 20.4258 20.2725 21 19.7732 21H17.2935C17.1083 21 16.9335 20.9145 16.8198 20.7684Z" stroke="currentColor" stroke-width="1.5"/><path d="M20 3L4 21" stroke="currentColor" stroke-linecap="round"/></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800" fill="currentColor" viewBox="0 0 512 512"><path d="M412.19 118.66a109 109 0 0 1-9.45-5.5 133 133 0 0 1-24.27-20.62c-18.1-20.71-24.86-41.72-27.35-56.43h.1C349.14 23.9 350 16 350.13 16h-82.44v318.78c0 4.28 0 8.51-.18 12.69 0 .52-.05 1-.08 1.56 0 .23 0 .47-.05.71v.18a70 70 0 0 1-35.22 55.56 68.8 68.8 0 0 1-34.11 9c-38.41 0-69.54-31.32-69.54-70s31.13-70 69.54-70a68.9 68.9 0 0 1 21.41 3.39l.1-83.94a153.14 153.14 0 0 0-118 34.52 161.8 161.8 0 0 0-35.3 43.53c-3.48 6-16.61 30.11-18.2 69.24-1 22.21 5.67 45.22 8.85 54.73v.2c2 5.6 9.75 24.71 22.38 40.82A167.5 167.5 0 0 0 115 470.66v-.2l.2.2c39.91 27.12 84.16 25.34 84.16 25.34 7.66-.31 33.32 0 62.46-13.81 32.32-15.31 50.72-38.12 50.72-38.12a158.5 158.5 0 0 0 27.64-45.93c7.46-19.61 9.95-43.13 9.95-52.53V176.49c1 .6 14.32 9.41 14.32 9.41s19.19 12.3 49.13 20.31c21.48 5.7 50.42 6.9 50.42 6.9v-81.84c-10.14 1.1-30.73-2.1-51.81-12.61"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>',
    ];

    return $icons;
}
