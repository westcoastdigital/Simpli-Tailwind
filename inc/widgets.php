<?php

if (!defined('ABSPATH')) exit;

/*
* Footer Navigation
*/
class Footernavigation_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'footernavigation_widget',
            esc_html__('Footer Navigation', 'translate')
        );
    }

    private function get_menu_options()
    {
        $menus = wp_get_nav_menus();
        $options = [];

        foreach ($menus as $menu) {
            $options[$menu->term_id] = $menu->name;
        }

        return $options;
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        if (!empty($instance['footer_nav'])) {
            echo '<nav class="flex flex-col gap-4">';

            wp_nav_menu([
                'menu' => (int) $instance['footer_nav'],
                'container' => false,
                'items_wrap' => '%3$s',
                'walker' => new Tailwind_Footer_Nav_Walker(),
                'fallback_cb' => false,
            ]);

            echo '</nav>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = $instance['title'] ?? '';
        $selected_menu = $instance['footer_nav'] ?? '';
        $menus = $this->get_menu_options();
?>

        <p>
            <label>Title:</label>
            <input class="widefat"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label>Menu:</label>
            <select class="widefat"
                name="<?php echo $this->get_field_name('footer_nav'); ?>">
                <?php foreach ($menus as $id => $name): ?>
                    <option value="<?php echo esc_attr($id); ?>"
                        <?php selected($selected_menu, $id); ?>>
                        <?php echo esc_html($name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

    <?php
    }

    public function update($new_instance, $old_instance)
    {
        return [
            'title' => sanitize_text_field($new_instance['title'] ?? ''),
            'footer_nav' => sanitize_text_field($new_instance['footer_nav'] ?? ''),
        ];
    }
}

/*
* Social Media Footer
*/
class Footersocial_Widget extends WP_Widget
{

    private static $platforms = ['facebook', 'instagram', 'twitter', 'linkedin', 'tiktok', 'youtube'];

    function __construct()
    {
        parent::__construct(
            'footersocial_widget',
            esc_html__('Footer Social', 'translate')
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['socials']) && is_array($instance['socials'])) {

            $alignment_classes = [
                'left'   => 'justify-start',
                'center' => 'justify-center',
                'right'  => 'justify-end',
            ];
            $align = $alignment_classes[$instance['alignment'] ?? 'right'];

            echo '<div class="flex gap-4 ' . $align . '">';

            $icons = sw_social_icons();

            foreach ($instance['socials'] as $social) {
                if (empty($social['url'])) continue;

                $icon = $icons[$social['platform']];
                // $icon = '';

                echo '<a href="' . esc_url($social['url']) . '" target="_blank" class="social-icon ' . $social['platform'] . ' w-12 h-12 rounded-full border border-stone-800 flex items-center justify-center text-stone-400 hover:bg-orange-500 hover:text-stone-900 transition-all ease-in-out duration-300">';
                echo '<span class="text-xl">';
                echo $icon;
                echo '</span>';
                echo '</a>';
            }

            echo '</div>';
        }

        $text_align = 'text-' . ($instance['alignment'] ?? 'right');

        if (!empty($instance['copyright'])) {
            echo '<p class="font-[\'Manrope\'] text-xs tracking-normal text-stone-500 ' . $text_align . '">&copy; ' . date('Y') . '&nbsp;' . esc_html($instance['copyright']) . '</p>';
        }


        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $copyright = $instance['copyright'] ?? '';
        $socials    = $instance['socials'] ?? [];
        $field_base = $this->get_field_name('socials');
        $widget_id  = $this->id; // unique per widget instance
    ?>

        <div class="social-repeater">
            <?php foreach ($socials as $index => $social): ?>
                <?php $this->repeater_row($index, $social); ?>
            <?php endforeach; ?>
        </div>

        <button type="button" class="button add-social">+ Add Social</button>

        <p>
            <label>Copyright</label>
            <input class="widefat"
                name="<?php echo $this->get_field_name('copyright'); ?>"
                value="<?php echo esc_attr($copyright); ?>">
        </p>

        <p style="margin-top:10px;">
            <label>Alignment</label>
            <select class="widefat" name="<?php echo $this->get_field_name('alignment'); ?>">
                <?php foreach (['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $val => $label): ?>
                    <option value="<?php echo $val; ?>" <?php selected($instance['alignment'] ?? 'right', $val); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <script>
            (function($) {

                var fieldBase = <?php echo json_encode($field_base); ?>;
                var platforms = <?php echo json_encode(self::$platforms); ?>;

                /**
                 * Build the platform <select> options HTML.
                 * @param {string} selected
                 */
                function platformOptions(selected) {
                    return platforms.map(function(p) {
                        var sel = (p === selected) ? ' selected' : '';
                        return '<option value="' + p + '"' + sel + '>' + p.charAt(0).toUpperCase() + p.slice(1) + '</option>';
                    }).join('');
                }

                /**
                 * Renumber ALL rows (both PHP-rendered and JS-added)
                 * using data-field-name to set the real name attribute.
                 */
                function updateIndexes(container) {
                    container.find('.social-row').each(function(i) {
                        $(this).find('[data-field-name]').each(function() {
                            var key = $(this).data('field-name'); // 'platform' or 'url'
                            $(this).attr('name', fieldBase + '[' + i + '][' + key + ']');
                        });
                    });
                }

                function initSocialRepeater(widget) {
                    // Use a namespaced event so re-init doesn't stack listeners.
                    widget.off('click.sw_social');

                    widget.on('click.sw_social', '.add-social', function() {
                        var container = widget.find('.social-repeater');

                        var row = $(
                            '<div class="social-row" style="margin-bottom:10px;border:1px solid #ddd;padding:10px;">' +
                            '<select data-field-name="platform">' + platformOptions('facebook') + '</select>' +
                            '<input type="text" data-field-name="url" placeholder="URL" style="width:100%;margin-top:5px;">' +
                            '<button type="button" class="button remove-social" style="margin-top:5px;">Remove</button>' +
                            '</div>'
                        );

                        container.append(row);
                        updateIndexes(container);
                    });

                    widget.on('click.sw_social', '.remove-social', function() {
                        var container = widget.find('.social-repeater');
                        $(this).closest('.social-row').remove();
                        updateIndexes(container);
                    });

                    // Set names for any PHP-rendered rows on init.
                    updateIndexes(widget.find('.social-repeater'));
                }

                function initAll() {
                    $('.widget:has(.social-repeater)').each(function() {
                        initSocialRepeater($(this));
                    });
                }

                $(document).ready(initAll);

                $(document).on('widget-added widget-updated', function(e, widget) {
                    initSocialRepeater($(widget));
                });

            })(jQuery);
        </script>

    <?php
    }

    /**
     * Renders a saved repeater row.
     * Uses data-field-name instead of a hardcoded name so updateIndexes()
     * can renumber ALL rows (PHP + JS) in one pass without index collisions.
     */
    private function repeater_row($index, $social)
    {
    ?>
        <div class="social-row" style="margin-bottom:10px; border:1px solid #ddd; padding:10px;">

            <select data-field-name="platform">
                <?php foreach (self::$platforms as $platform): ?>
                    <option value="<?php echo esc_attr($platform); ?>"
                        <?php selected($social['platform'] ?? '', $platform); ?>>
                        <?php echo esc_html(ucfirst($platform)); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text"
                data-field-name="url"
                value="<?php echo esc_attr($social['url'] ?? ''); ?>"
                placeholder="URL"
                style="width:100%; margin-top:5px;">

            <button type="button" class="button remove-social" style="margin-top:5px;">Remove</button>
        </div>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];

        $instance['copyright'] = sanitize_text_field($new_instance['copyright'] ?? '');

        $instance['alignment'] = in_array($new_instance['alignment'] ?? '', ['left', 'center', 'right'])
            ? $new_instance['alignment']
            : 'right';

        if (!empty($new_instance['socials'])) {
            foreach ($new_instance['socials'] as $social) {
                if (empty($social['url'])) continue;

                $instance['socials'][] = [
                    'platform' => sanitize_text_field($social['platform']),
                    'url'      => esc_url_raw($social['url']),
                ];
            }
        }

        return $instance;
    }
}

function sw_register_widgets()
{
    register_widget('Footernavigation_Widget');
    register_widget('Footersocial_Widget');
}
add_action('widgets_init', 'sw_register_widgets');
