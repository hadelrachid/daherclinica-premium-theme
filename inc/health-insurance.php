<?php
/**
 * Custom Post Type: Planos de Saúde (Convênios)
 */

if (!defined('ABSPATH')) {
    exit;
}

function daherclinica_register_health_insurance_cpt() {
    $labels = [
        'name'               => 'Planos de Saúde',
        'singular_name'      => 'Plano de Saúde',
        'menu_name'          => 'Planos de Saúde',
        'name_admin_bar'     => 'Plano de Saúde',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Plano',
        'new_item'           => 'Novo Plano',
        'edit_item'          => 'Editar Plano',
        'view_item'          => 'Ver Plano',
        'all_items'          => 'Todos os Planos',
        'search_items'       => 'Buscar Planos',
        'not_found'          => 'Nenhum plano encontrado.',
        'not_found_in_trash' => 'Nenhum plano encontrado na lixeira.'
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false, // Não precisa ter página individual (single)
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'daher-settings', // Coloca dentro do menu Daher Clínica
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => ['title', 'thumbnail'], // Apenas Título e Imagem Destacada (Logo)
    ];

    register_post_type('health_insurance', $args);
}
add_action('init', 'daherclinica_register_health_insurance_cpt');
