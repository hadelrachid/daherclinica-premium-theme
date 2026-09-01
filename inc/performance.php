<?php
/**
 * Módulo de Performance Nativa (Daher Speed)
 * Otimizações de velocidade sem dependência de plugins externos.
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. LIMPEZA DE BLOAT DO WORDPRESS
 * Remove scripts obsoletos ou desnecessários.
 */
function daherclinica_speed_cleanup() {
    // Remove Emojis nativos (Navegadores atuais já suportam nativamente)
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove Links RSD e Windows Live Writer (obsoletos)
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove wp_generator (versão do WP - melhora segurança e limpa header)
    remove_action('wp_head', 'wp_generator');
    
    // Remove links relacionais antigos do head
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
}
add_action('init', 'daherclinica_speed_cleanup');

/**
 * Remove o script wp-embed do frontend
 */
function daherclinica_speed_deregister_scripts() {
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'daherclinica_speed_deregister_scripts');

/**
 * 2. DESATIVAR XML-RPC
 * Aumenta a segurança bloqueando ataques de força bruta e reduz carga do servidor.
 */
add_filter('xmlrpc_enabled', '__return_false');


/**
 * 3. VERSIONAMENTO INTELIGENTE DE ASSETS
 * Em vez de remover o ?ver= (que impedia o navegador de detectar atualizações),
 * substituímos por uma versão baseada na DAHER_THEME_VERSION.
 * Assim o cache funciona CORRETAMENTE: mantém em cache até a versão mudar.
 */
function daherclinica_smart_asset_version($src) {
    // Só aplica aos assets do nosso tema
    if (strpos($src, get_template_directory_uri()) !== false) {
        $file_path = str_replace(get_template_directory_uri(), get_template_directory(), $src);
        // Pega apenas a base sem query strings caso existam
        $file_path = explode('?', $file_path)[0];
        
        if (file_exists($file_path)) {
            $version = date('YmdHis', filemtime($file_path));
            $src = remove_query_arg('ver', $src);
            $src = add_query_arg('v', $version, $src);
        }
    }
    return $src;
}
add_filter('style_loader_src', 'daherclinica_smart_asset_version', 20);
add_filter('script_loader_src', 'daherclinica_smart_asset_version', 20);

/**
 * 3.1. OTIMIZAR PRECONNECTS
 * Limita as pré-conexões às origens estritamente necessárias, evitando duplicidades.
 */
function daherclinica_resource_hints_optimized($urls, $relation_type) {
    if ($relation_type === 'preconnect') {
        $essential = [
            'https://www.googletagmanager.com', 
            'https://cdnjs.cloudflare.com'
        ];
        return array_unique(array_merge($urls, $essential));
    }
    return $urls;
}
add_filter('wp_resource_hints', 'daherclinica_resource_hints_optimized', 20, 2);


/**
 * 4. DEFER SEGURO EM SCRIPTS NÃO-CRÍTICOS
 * Adiciona o atributo defer aos scripts do tema para não bloquearem a renderização (Render-Blocking).
 * Nossa arquitetura main.js foi feita ouvindo DOMContentLoaded, então suporta defer nativamente.
 */
function daherclinica_defer_scripts($tag, $handle, $src) {
    // Não aplica defer no admin ou no jQuery (se for crítico)
    if (is_admin() || strpos($handle, 'jquery') !== false) {
        return $tag;
    }
    
    // Evita duplicar se já houver defer ou async
    if (strpos($tag, 'defer') !== false || strpos($tag, 'async') !== false) {
        return $tag;
    }
    
    // Agora aplica defer globalmente para resolver bloqueios de terceiros (Google Site Kit etc)
    return str_replace(' src', ' defer="defer" src', $tag);
}
add_filter('script_loader_tag', 'daherclinica_defer_scripts', 10, 3);

/**
 * 6. BOTÃO DE LIMPAR CACHE (DAHER SPEED)
 * Adiciona um botão na barra de ferramentas do WordPress para limpar
 * Object Cache, OPcache e integração com o cache de servidor da Hostinger.
 */
function daherclinica_add_cache_clear_button($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;

    $wp_admin_bar->add_node([
        'id'    => 'daher_clear_cache',
        'title' => '<span class="ab-icon dashicons dashicons-image-rotate" style="margin-top: 2px;"></span>Limpar Cache (Daher)',
        'href'  => wp_nonce_url(admin_url('admin-post.php?action=daher_clear_cache'), 'daher_clear_cache_nonce'),
        'meta'  => ['title' => 'Força a limpeza do cache de banco de dados e servidor']
    ]);
}
add_action('admin_bar_menu', 'daherclinica_add_cache_clear_button', 100);

function daherclinica_process_clear_cache() {
    if (!current_user_can('manage_options') || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'daher_clear_cache_nonce')) {
        wp_die('Acesso negado.');
    }

    // Limpa o cache nativo do WordPress (Object Cache)
    wp_cache_flush();

    // Limpa o OPcache do PHP (Se o servidor Hostinger permitir)
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }

    // Integração com o Servidor LiteSpeed da Hostinger (Se ativo nos bastidores)
    if (class_exists('LiteSpeed\Purge')) {
        LiteSpeed\Purge::purge_all();
    } elseif (defined('LSCWP_V')) {
        do_action('litespeed_purge_all');
    }

    $redirect_url = wp_get_referer() ? wp_get_referer() : admin_url();
    $redirect_url = add_query_arg('daher_cache_cleared', '1', remove_query_arg('daher_cache_cleared', $redirect_url));
    
    wp_redirect($redirect_url);
    exit;
}
add_action('admin_post_daher_clear_cache', 'daherclinica_process_clear_cache');

function daherclinica_cache_clear_notice() {
    if (isset($_GET['daher_cache_cleared']) && $_GET['daher_cache_cleared'] == '1') {
        echo '<div class="notice notice-success is-dismissible"><p><strong>✅ Daher Speed:</strong> O cache do sistema e do servidor foi limpo com sucesso!</p></div>';
    }
}
add_action('admin_notices', 'daherclinica_cache_clear_notice');

/**
 * 7. PURGE AUTOMÁTICO AO PUBLICAR/ATUALIZAR POSTS
 * Quando um post é publicado ou atualizado, limpa TODAS as camadas de cache
 * automaticamente, sem precisar clicar em nenhum botão.
 */
function daherclinica_auto_purge_on_publish($post_id, $post = null, $update = false) {
    // Evita execução em revisões automáticas e autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    
    // Só executa para posts publicados
    $post_status = get_post_status($post_id);
    if ($post_status !== 'publish') return;
    
    // 1. Limpa Object Cache do WordPress
    wp_cache_flush();
    
    // 2. Limpa OPcache do PHP
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    
    // 3. Integração com LiteSpeed da Hostinger
    if (class_exists('LiteSpeed\\Purge')) {
        LiteSpeed\Purge::purge_all();
    } elseif (defined('LSCWP_V')) {
        do_action('litespeed_purge_all');
    }
    
    // 4. Limpa cache de transientes relacionados a queries de posts
    delete_transient('daherclinica_recent_posts');
    
    // 5. Limpa o cache de página da Hostinger via header
    if (function_exists('header_remove')) {
        header('X-LiteSpeed-Purge: *');
    }
}
add_action('save_post', 'daherclinica_auto_purge_on_publish', 10, 3);
add_action('publish_post', 'daherclinica_auto_purge_on_publish', 10, 3);
add_action('trash_post', 'daherclinica_auto_purge_on_publish');


/**
 * 9. CONTENT SECURITY POLICY (CSP) E HEADERS DE SEGURANÇA
 * Força boas práticas de segurança, bloqueando scripts maliciosos.
 */
function daherclinica_security_headers() {
    if (!is_admin() && !headers_sent()) {
        header("Content-Security-Policy: default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'; object-src 'none';");
        header("X-Content-Type-Options: nosniff");
    }
}
add_action('send_headers', 'daherclinica_security_headers');


