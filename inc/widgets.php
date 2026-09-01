<?php
/**
 * Widgets Module (Simplificado)
 * Apenas a sidebar principal do blog
 */

if (!defined('ABSPATH')) {
    exit;
}

function daherclinica_register_sidebars() {
    // Apenas a sidebar principal (opcional)
    register_sidebar([
        'name'          => __('Sidebar Principal', 'daherclinica'),
        'id'            => 'sidebar-main',
        'description'   => __('Sidebar padrão para páginas de blog.', 'daherclinica'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'daherclinica_register_sidebars');