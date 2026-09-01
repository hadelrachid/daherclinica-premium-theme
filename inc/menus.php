<?php
/**
 * Menus Module
 * Registro e configuração dos menus do tema
 * 
 * @package DaherClinica
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe de gerenciamento de menus
 */
class DaherClinica_Menus {
    
    public function __construct() {
        add_action('init', [$this, 'register_menus']);
        add_filter('nav_menu_link_attributes', [$this, 'add_nav_link_class'], 10, 3);
    }
    
    public function register_menus() {
        register_nav_menus([
            'primary' => __('Menu Principal', 'daherclinica'),
            'footer'  => __('Menu Rodapé', 'daherclinica'),
        ]);
    }
    
    public function add_nav_link_class($atts, $item, $args) {
        if ($args->theme_location === 'primary') {
            $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'nav-link';
        }
        return $atts;
    }
}

new DaherClinica_Menus();

/**
 * Classe Walker para Menu Mobile (submenu expansível)
 * 
 * @package DaherClinica
 */
class DaherClinica_Mobile_Walker extends Walker_Nav_Menu {
    
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu-items\">\n";
    }

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = str_repeat("\t", $depth);
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        
        if ($has_children) {
            $output .= $indent . '<li class="mobile-submenu">';
            $output .= '<div class="mobile-submenu-title">';
            
            // Link principal
            $output .= '<a href="' . esc_url($item->url) . '" class="mobile-submenu-link">';
            $output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $output .= '</a>';
            
            // Botão toggle
            $output .= '<button class="mobile-toggle-btn" aria-label="Abrir submenu">';
            $output .= '<i class="fas fa-chevron-down mobile-arrow"></i>';
            $output .= '</button>';
            $output .= '</div>';
        } else {
            $output .= $indent . '<li>';
            $output .= '<a href="' . esc_url($item->url) . '" onclick="closeMobileMenu()">';
            $output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $output .= '</a>';
        }
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }

    /**
     * Ends the list of after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}