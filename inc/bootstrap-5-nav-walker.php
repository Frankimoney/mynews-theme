<?php
/**
 * Bootstrap 5 Nav Walker for WordPress
 * 
 * A custom WordPress nav walker class to implement the Bootstrap 5 navigation style
 * in a custom theme using the WordPress built in menu manager.
 * 
 * @package My_News
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Mynews_Bootstrap_5_Nav_Walker' ) ) {
    class Mynews_Bootstrap_5_Nav_Walker extends Walker_Nav_Menu {
        /**
         * Start the element output.
         */
        public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
            // Restores the more descriptive, specific name for use within this method.
            $item = $data_object;
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $class_names = '';
            $value = '';
            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            
            // Add .nav-item class
            if ( $depth === 0 ) {
                $classes[] = 'nav-item';
            }

            // Add .dropdown class to items with children
            if ( $args->walker->has_children ) {
                $classes[] = 'dropdown';
            }

            if ( in_array( 'current-menu-item', $classes ) || in_array( 'current_page_item', $classes ) ) {
                $classes[] = 'active';
            }

            // Filter the arguments for a single nav menu item
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';

            $atts = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            if ( '_blank' === $item->target && empty( $item->xfn ) ) {
                $atts['rel'] = 'noopener noreferrer';
            } else {
                $atts['rel'] = $item->xfn;
            }
            $atts['href']         = ! empty( $item->url ) ? $item->url : '';
            $atts['aria-current'] = ( in_array( 'current-menu-item', $classes ) || in_array( 'current_page_item', $classes ) ) ? 'page' : '';
            
            // Add default .nav-link class
            $atts['class'] = 'nav-link';
            
            // If item has children, add .dropdown-toggle and other attributes
            if ( $args->walker->has_children && $depth === 0 ) {
                $atts['class']          .= ' dropdown-toggle';
                $atts['data-bs-toggle']  = 'dropdown';
                $atts['aria-expanded']   = 'false';
                $atts['role']            = 'button';
            }
            
            // Add .dropdown-item class if the item is a dropdown sub-item
            if ( $depth > 0 ) {
                $atts['class'] = 'dropdown-item';
            }

            // Filter the HTML attributes applied to a menu item's anchor element
            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            /** This filter is documented in wp-includes/post-template.php */
            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
        
        /**
         * Starts the list before the elements are added.
         */
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
        }
    }
}
