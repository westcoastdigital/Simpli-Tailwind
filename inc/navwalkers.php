<?php
class Tailwind_Nav_Walker extends Walker_Nav_Menu {

    private $current_index = 0;
    private $total_items   = 0;
    private $last_item     = null;

    public function walk( $elements, $max_depth, ...$args ) {
        $this->total_items = count( $elements );
        $this->last_item   = end( $elements );
        return parent::walk( $elements, $max_depth, ...$args );
    }

    // ── Dropdown wrapper (depth 1 = the panel, depth 2 = a sub-group) ────────
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        if ( $depth === 0 ) {
            // The floating panel that appears on hover
            $output .= '<div class="absolute top-full left-1/2 -translate-x-1/2 pt-3 hidden group-hover:block z-50 min-w-[180px]">'
                     . '<div class="bg-stone-50 dark:bg-stone-900 shadow-xl border border-stone-200/60 dark:border-stone-700/60 py-2 flex flex-col gap-0.5">';
        } elseif ( $depth === 1 ) {
            // A visually-separated sub-group under a category header
            $output .= '<div class="pl-3 flex flex-col gap-0.5">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( $depth === 0 ) {
            $output .= '</div></div>';
        } elseif ( $depth === 1 ) {
            $output .= '</div>';
        }
    }

    // ── Items ─────────────────────────────────────────────────────────────────
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $this->current_index++;

        $item_classes = ! empty( $item->classes ) ? (array) $item->classes : array();

        // Last item becomes the CTA button — skip here, rendered by render_button()
        if ( $item === $this->last_item || in_array( 'menu-button', $item_classes ) ) {
            return;
        }

        $is_current  = in_array( 'current-menu-item', $item_classes );
        $has_children = in_array( 'menu-item-has-children', $item_classes );

        if ( $depth === 0 ) {
            // ── Top-level nav item ────────────────────────────────────────────
            // Wrap in a relative group so hover works without JS
            $output .= '<div class="relative group">';

            $link_classes = $is_current
                ? "font-['Lexend'] font-medium tracking-tight text-sm uppercase text-orange-700 dark:text-orange-500 border-b-2 border-orange-700 dark:border-orange-500 pb-1 hover:scale-[1.02] transition-transform duration-300 ease-out flex items-center gap-1"
                : "font-['Lexend'] font-medium tracking-tight text-sm uppercase text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 border-b-2 border-b-transparent pb-1 transition-colors hover:scale-[1.02] transition-transform duration-300 ease-out flex items-center gap-1";

            $output .= sprintf(
                '<a class="%s" href="%s">%s%s</a>',
                esc_attr( $link_classes ),
                esc_url( $item->url ),
                esc_html( $item->title ),
                $has_children
                    ? '<svg class="w-3 h-3 opacity-50 group-hover:opacity-100 transition-all group-hover:rotate-180 duration-200" fill="none" viewBox="0 0 10 10" stroke="currentColor" stroke-width="1.5"><path d="M2 3.5L5 6.5L8 3.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                    : ''
            );

        } elseif ( $depth === 1 ) {
            // ── Dropdown row: category header (has children) or direct link ───
            if ( $has_children ) {
                // Category label — not a link, just a visual divider/header
                $output .= sprintf(
                    '<div class="px-4 pt-2 pb-1"><span class="font-[\'Lexend\'] text-[10px] font-semibold uppercase tracking-widest text-stone-400 dark:text-stone-500">%s</span></div>',
                    esc_html( $item->title )
                );
            } else {
                // Direct link inside the dropdown
                $link_classes = $is_current
                    ? "block px-4 py-1.5 font-['Lexend'] text-sm font-medium text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 rounded-lg mx-1"
                    : "block px-4 py-1.5 font-['Lexend'] text-sm text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-lg mx-1 transition-colors";

                $output .= sprintf(
                    '<a class="%s" href="%s">%s</a>',
                    esc_attr( $link_classes ),
                    esc_url( $item->url ),
                    esc_html( $item->title )
                );
            }

        } elseif ( $depth === 2 ) {
            // ── Nested link under a category header ───────────────────────────
            $link_classes = $is_current
                ? "block px-4 py-1.5 font-['Lexend'] text-sm font-medium text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 rounded-lg mx-1"
                : "block px-4 py-1.5 font-['Lexend'] text-sm text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-lg mx-1 transition-colors";

            $output .= sprintf(
                '<a class="%s" href="%s">%s</a>',
                esc_attr( $link_classes ),
                esc_url( $item->url ),
                esc_html( $item->title )
            );
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $item_classes = ! empty( $item->classes ) ? (array) $item->classes : array();

        // Close the group wrapper for depth-0 items
        if ( $depth === 0
             && $item !== $this->last_item
             && ! in_array( 'menu-button', $item_classes ) ) {
            $output .= '</div>';
        }
    }

    // ── CTA button (unchanged) ────────────────────────────────────────────────
    public function render_button() {
        if ( ! $this->last_item ) return '';
        return '<div class="hidden lg:flex items-center gap-4">
            <button class="bg-primary text-on-primary px-8 py-3.5 rounded-full font-bold text-sm hover:scale-[1.02] active:opacity-70 active:scale-95 transition-all shadow-lg shadow-primary/10 uppercase tracking-wider"
                    onclick="location.href=\'' . esc_url( $this->last_item->url ) . '\'">'
                 . esc_html( $this->last_item->title ) .
            '</button>
        </div>';
    }
}

class Tailwind_Mobile_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '<ul class="hidden flex-col gap-1 pl-4 mt-1 border-l border-white/20 ml-2" data-submenu>';
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $item_classes = ! empty( $item->classes ) ? (array) $item->classes : array();
        $is_current   = in_array( 'current-menu-item', $item_classes );
        $has_children = in_array( 'menu-item-has-children', $item_classes );
        $is_button    = in_array( 'menu-button', $item_classes );

        $output .= '<li>';

        if ( $is_button ) {
            $output .= sprintf(
                '<a href="%s" class="block mt-6 text-center font-[\'Lexend\'] font-bold text-sm uppercase tracking-wider bg-white/10 hover:bg-white/20 text-white px-6 py-4 rounded-2xl transition-colors">%s</a>',
                esc_url( $item->url ),
                esc_html( $item->title )
            );
            return;
        }

        if ( $has_children ) {
            $output .= sprintf(
                '<button type="button" data-toggle class="w-full flex items-center justify-between py-4 font-[\'Lexend\'] font-semibold text-2xl text-white/90 hover:text-white transition-colors" aria-expanded="false">
                    <span>%s</span>
                    <svg class="w-5 h-5 opacity-50 transition-transform duration-200 shrink-0" fill="none" viewBox="0 0 10 10" stroke="currentColor" stroke-width="1.5">
                        <path d="M2 3.5L5 6.5L8 3.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>',
                esc_html( $item->title )
            );
        } else {
            $base = $depth === 0
                ? "block py-4 font-['Lexend'] font-semibold text-2xl transition-colors"
                : "block py-2 font-['Lexend'] text-lg transition-colors";

            $colour = $is_current
                ? 'text-orange-400'
                : 'text-white/80 hover:text-white';

            $output .= sprintf(
                '<a href="%s" class="%s">%s</a>',
                esc_url( $item->url ),
                esc_attr( $base . ' ' . $colour ),
                esc_html( $item->title )
            );
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '</li>';
    }
}

class Tailwind_Footer_Nav_Walker extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = null) {
        // Remove <ul>
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // Remove </ul>
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {

        $classes = "font-['Manrope'] text-sm tracking-normal text-stone-400 dark:text-stone-500 hover:text-stone-50 dark:hover:text-stone-200 hover:translate-x-1 transition-transform duration-200 underline-offset-8 hover:underline";

        $output .= '<a href="' . esc_url($item->url) . '" class="' . $classes . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        // nothing
    }
}