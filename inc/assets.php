<?php
/**
 * Assets Module
 * Enfileiramento de scripts e estilos do tema
 * 
 * @package DaherClinica
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class DaherClinica_Assets {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    public function enqueue_styles() {
        // 1. Recursos Externos (Google Fonts removido para hospedagem local)
        
        // Font Awesome Assíncrono (Carrega como 'print' e muda para 'all' no client-side)
        wp_enqueue_style('daherclinica-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1', 'print');

        // 2. CSS Principal (sempre carrega) - Retornado ao modo síncrono para evitar FOUC
        wp_enqueue_style(
            'daherclinica-main',
            get_template_directory_uri() . '/assets/css/main.min.css',
            [],
            defined('DAHER_THEME_VERSION') ? DAHER_THEME_VERSION : '1.0.0'
        );
        
        // 3. CSS do Blog (apenas nas páginas de blog) - SEM DUPLICAÇÃO
        if (is_page('blog') || is_home() || is_single() || is_archive() || is_category() || is_tag()) {
            wp_enqueue_style(
                'daherclinica-blog',
                get_template_directory_uri() . '/assets/css/blog.min.css',
                ['daherclinica-main'],
                defined('DAHER_THEME_VERSION') ? DAHER_THEME_VERSION : '1.0.0',
                'print'
            );
        }
    }
    
    public function enqueue_scripts() {
        // 1. JS Principal (sempre carrega) — Vanilla JS puro, sem dependência de jQuery
        wp_enqueue_script(
            'daherclinica-main',
            get_template_directory_uri() . '/assets/js/main.min.js',
            [], // Sem dependências
            defined('DAHER_THEME_VERSION') ? DAHER_THEME_VERSION : '1.0.0',
            ['in_footer' => true, 'strategy' => 'defer']
        );

        // 2. JS do Blog (apenas nas páginas de blog)
        if (is_page('blog') || is_home() || is_single() || is_archive() || is_category() || is_tag()) {
            wp_enqueue_script(
                'daherclinica-blog',
                get_template_directory_uri() . '/assets/js/blog.min.js',
                ['daherclinica-main'],
                defined('DAHER_THEME_VERSION') ? DAHER_THEME_VERSION : '1.0.0',
                ['in_footer' => true, 'strategy' => 'defer']
            );
        }
        
        // Busca o total de cliques atual para mostrar na mensagem
        $tracker = new \DaherClinica\Services\WhatsApp_Tracker();
        $user_count = $tracker->get_overall_total() + 1;

        // Dados globais para JavaScript
        wp_localize_script('daherclinica-main', 'daherData', [
            'ajaxUrl'        => admin_url('admin-ajax.php'),
            'siteUrl'        => get_site_url(),
            'whatsappNumber' => $this->get_whatsapp_number(),
            'userCount'      => $user_count,
        ]);
    }
    
    private function get_whatsapp_number() {
        $options = get_option('daher_whatsapp_options', []);
        $number = !empty($options['whatsapp_number']) ? $options['whatsapp_number'] : get_theme_mod('whatsapp_number', '5521977667676');
        return preg_replace('/[^0-9]/', '', $number);
    }
}

// Inicializa a classe
new DaherClinica_Assets();

/**
 * Ativa o CSS assíncrono do FontAwesome via onload no link tag
 * Isso remove o bloqueio de renderização (FCP) sem aguardar DOMContentLoaded
 */
add_filter('style_loader_tag', function($html, $handle) {
    if ($handle === 'daherclinica-fontawesome' || $handle === 'daherclinica-blog') {
        return str_replace("media='print'", "media='print' onload=\"this.media='all'\"", $html) . "<noscript>" . str_replace("media='print'", "media='all'", $html) . "</noscript>";
    }
    return $html;
}, 10, 2);