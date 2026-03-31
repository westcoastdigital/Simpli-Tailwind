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

        $woo_class = class_exists('WooCommerce') ? 'woocommerce-pagination' : 'woocommerce-pagination';
        $woo_list_class = class_exists('WooCommerce') ? 'page-numbers' : 'page-numbers';
        echo '<nav class="' .  $woo_class . '"><ul class="'  . $woo_list_class  . '">' . "\n";

        /** Previous Post Link */
        if (get_previous_posts_link())
            printf('<li>%s</li>' . "\n", get_previous_posts_link('←'));

        /** Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? ' class="active"' : '';

            printf('<li%s><a href="%s" class="page-numbers">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');

            if (!in_array(2, $links))
                echo '<li>…</li>';
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? ' class="active"' : '';
            printf('<li%s><a href="%s" class="page-numbers">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
        }

        /** Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<li>…</li>' . "\n";

            $class = $paged == $max ? ' class="active"' : '';
            printf('<li%s><a href="%s" class="page-numbers">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
        }

        /** Next Post Link */
        if (get_next_posts_link())
            printf('<li>%s</li>' . "\n", get_next_posts_link('→'));

        echo '</ul></nav>' . "\n";
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