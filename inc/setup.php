<?php
/**
 * Theme Setup Module
 * Configurações iniciais e suportes do tema
 * 
 * @package DaherClinica
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Saída direta se acessado diretamente
}

/**
 * Classe de configuração do tema
 * Responsável apenas por registrar suportes e configurações iniciais
 */
class DaherClinica_Setup {
    
    /**
     * Construtor - adiciona os hooks
     */
    public function __construct() {
        add_action('after_setup_theme', [$this, 'setup_theme']);
    }
    
    /**
     * Configurações principais do tema
     */
    public function setup_theme() {
        
        // 1. Suporte a título dinâmico (SEO)
        add_theme_support('title-tag');
        
        // 2. Suporte a imagens destacadas (post thumbnail)
        add_theme_support('post-thumbnails');
        
        // 3. Suporte a HTML5 para elementos específicos
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        
        // 4. Suporte a logo personalizável
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ]);
        
        // 5. Suporte a estilos do editor Gutenberg
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
        
        // 6. Suporte a alinhamento largo/largo no Gutenberg
        add_theme_support('align-wide');
        
        // 7. Suporte a embeds responsivos
        add_theme_support('responsive-embeds');
        
        // 8. Suporte a widgets no Gutenberg (block widgets)
        add_theme_support('widgets-block-editor');
        
        // 9. Tamanhos customizados de imagem (Otimização Responsiva PageSpeed)
        add_image_size('team-photo', 400, 400, true);
        add_image_size('specialty-card', 600, 400, true);
        
        add_image_size('consultorio-small', 400, 300, true);
        add_image_size('consultorio-medium', 800, 600, true);
        add_image_size('consultorio-large', 1200, 900, true);
        
        add_image_size('medico-small', 200, 200, true);
        add_image_size('medico-medium', 400, 400, true);
    }
}

/**
 * Forçar WebP para uploads mantendo uma qualidade otimizada (80%)
 */
add_filter('wp_editor_set_quality', function($quality, $type) {
    if ($type === 'image/webp') {
        return 80;
    }
    return $quality;
}, 10, 2);

/**
 * Inicializa a classe
 */
new DaherClinica_Setup();