<?php
// functions.php - Apenas carrega os módulos essenciais
if (!defined('ABSPATH')) exit;

define('DAHER_THEME_VERSION', '2.7.0');
define('DAHER_THEME_DIR', get_template_directory());
define('DAHER_THEME_URI', get_template_directory_uri());

// Módulos essenciais (ordem importa)
$modules = [
    'inc/setup.php',           // Configurações básicas
    'inc/menus.php',           // Registro de menus
    'inc/icons.php',           // SVG helpers
    'inc/customizer.php',      // Opções do customizer
    'inc/widgets.php',         // Sidebars
    'inc/assets.php',          // ✅ Enfileira APENAS main.css + main.js
    'inc/performance.php',     // Módulo de Otimização e Velocidade Nativa (Daher Speed)
    'inc/doctors.php',         // CPT Médicos
];

foreach ($modules as $module) {
    if (file_exists(DAHER_THEME_DIR . '/' . $module)) {
        require_once DAHER_THEME_DIR . '/' . $module;
    }
}

// Carrega o painel administrativo apenas no wp-admin (Performance)
if (is_admin()) {
    require_once DAHER_THEME_DIR . '/inc/class-settings-api.php';
}

// Os módulos acima já se auto-inicializam ou registram seus hooks ao serem carregados.

/**
 * Callback personalizado para exibir comentários
 */
function daherclinica_comment_callback($comment, $args, $depth) {
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author">
                <?php echo get_avatar($comment, 50); ?>
                <div class="comment-author-info">
                    <span class="fn"><?php comment_author(); ?></span>
                    <div class="comment-metadata">
                        <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php printf(__('%s às %s', 'daherclinica'), get_comment_date(), get_comment_time()); ?>
                            </time>
                        </a>
                    </div>
                </div>
            </div>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="reply">
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'reply_text' => '<i class="fas fa-reply"></i> ' . __('Responder', 'daherclinica')
                )));
                ?>
            </div>
        </div>
    <?php
}

/**
 * Formata um número de telefone para exibição
 * Converte 5521977667676 para (21) 97766-7676
 */
function daherclinica_get_whatsapp_clean() {
    $options = get_option('daher_whatsapp_options', []);
    $number = !empty($options['whatsapp_number']) ? $options['whatsapp_number'] : '5521977667676';
    $clean = preg_replace('/[^0-9]/', '', $number);
    if (strlen($clean) === 10 || strlen($clean) === 11) {
        $clean = '55' . $clean;
    }
    return $clean;
}

function daherclinica_format_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Se começar com 55 (Brasil), remove para formatar o resto
    if (strlen($phone) > 11 && substr($phone, 0, 2) === '55') {
        $phone = substr($phone, 2);
    }
    
    $len = strlen($phone);
    if ($len === 11) { // Celular com DDD: 21977667676
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    } elseif ($len === 10) { // Fixo com DDD: 2124159263
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
    }
    
    return $phone; // Retorna original se não encaixar nos padrões
}

/**
 * Adiciona Schema.org (JSON-LD) para otimização de SEO Local (MedicalClinic + Physicians + Geolocation)
 */
function daherclinica_add_schema_markup() {
    if (is_front_page() || is_home()) {
        $media_options = get_option('daher_media_options', []);
        $clinica_options = get_option('daher_clinica_options', []);
        
        $logo_principal = !empty($media_options['logo_principal']) 
            ? $media_options['logo_principal'] 
            : get_template_directory_uri() . '/assets/images/logo.png';

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "MedicalClinic",
            "name" => "Daher Clínica",
            "url" => home_url('/'),
            "logo" => $logo_principal,
            "image" => !empty($media_options['og_default_image']) ? $media_options['og_default_image'] : get_template_directory_uri() . '/assets/images/og-default.jpg',
            "telephone" => !empty($clinica_options['daher_phone']) ? $clinica_options['daher_phone'] : '(21) 2415-9263',
            "email" => !empty($clinica_options['daher_email']) ? $clinica_options['daher_email'] : 'contato@daherclinica.com',
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "Estrada dos Bandeirantes, 8591, Sala 308 MAP BAND SHOPPING",
                "addressLocality" => "Rio de Janeiro",
                "addressRegion" => "RJ",
                "postalCode" => "22783-115",
                "addressCountry" => "BR"
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => "-22.9744918",
                "longitude" => "-43.4165382"
            ],
            "medicalSpecialty" => [
                "Angiologia",
                "Cirurgia Vascular",
                "Dermatologia",
                "Clínica Geral"
            ],
            "employee" => [
                [
                    "@type" => "Physician",
                    "name" => "Dr. Marcelo de Azevedo Daher",
                    "medicalSpecialty" => [
                        "@type" => "MedicalSpecialty",
                        "name" => "Cirurgião Vascular"
                    ]
                ],
                [
                    "@type" => "Physician",
                    "name" => "Dra. Rosana Palmares Maciel Daher",
                    "medicalSpecialty" => [
                        "@type" => "MedicalSpecialty",
                        "name" => "Dermatologista"
                    ]
                ],
                [
                    "@type" => "Physician",
                    "name" => "Dra. Caroline Linhares Machado",
                    "medicalSpecialty" => [
                        "@type" => "MedicalSpecialty",
                        "name" => "Clínico Geral"
                    ]
                ]
            ]
        ];

        echo '<!-- Schema.org by Daher Clínica (Local SEO) -->';
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo '</script>';
    } elseif (is_single()) {
        // Schema.org para Artigos de Blog (E-E-A-T)
        $media_options = get_option('daher_media_options', []);
        
        $author_id = get_post_field('post_author', get_the_ID());
        $author_name = get_the_author_meta('display_name', $author_id);
        
        $article_image = '';
        if (has_post_thumbnail()) {
            $article_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        } elseif (!empty($media_options['og_default_image'])) {
            $article_image = $media_options['og_default_image'];
        }
        
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => get_the_title(),
            "description" => wp_trim_words(get_the_excerpt(), 20, '...'),
            "image" => $article_image,
            "datePublished" => get_the_date('c'),
            "dateModified" => get_the_modified_date('c'),
            "author" => [
                "@type" => "Person",
                "name" => $author_name,
                "url" => get_author_posts_url($author_id)
            ],
            "publisher" => [
                "@type" => "MedicalClinic",
                "name" => "Daher Clínica",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => !empty($media_options['logo_principal']) ? $media_options['logo_principal'] : get_template_directory_uri() . '/assets/images/logo.png'
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => get_permalink()
            ]
        ];
        
        echo '<!-- Schema.org by Daher Clínica (Article SEO) -->';
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo '</script>';
    }
    echo "\n";
}
add_action('wp_head', 'daherclinica_add_schema_markup');

// ============================================================
// SUPORTE A WEBP
// ============================================================
function daherclinica_allow_webp_upload($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'daherclinica_allow_webp_upload');

function daherclinica_webp_is_displayable($result, $path) {
    if ($result === false && function_exists('exif_imagetype')) {
        $type = @exif_imagetype($path);
        if ($type === IMAGETYPE_WEBP) {
            $result = true;
        }
    }
    return $result;
}
add_filter('file_is_displayable_image', 'daherclinica_webp_is_displayable', 10, 2);

// ============================================================
// OTIMIZAÇÃO DO SITEMAP NATIVO DO WORDPRESS
// ============================================================
// Remove a página de autores do sitemap (evita conteúdo duplicado no Google)
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
    if ('users' === $name) {
        return false;
    }
    return $provider;
}, 10, 2);

/* ============================================================
   INJEÇÃO DE DEPENDÊNCIAS (SOLID ARCHITECTURE)
   ============================================================ */
require_once DAHER_THEME_DIR . '/inc/Contracts/Tracker_Interface.php';
require_once DAHER_THEME_DIR . '/inc/Contracts/Admin_Page_Interface.php';
require_once DAHER_THEME_DIR . '/inc/Services/WhatsApp_Tracker.php';
require_once DAHER_THEME_DIR . '/inc/Admin/Pages/Reports_Page.php';
require_once DAHER_THEME_DIR . '/inc/Admin/Export/WhatsApp_Export.php';

use DaherClinica\Services\WhatsApp_Tracker;
use DaherClinica\Admin\Pages\Reports_Page;
use DaherClinica\Admin\Export\WhatsApp_Export;

// 1. Instancia o Serviço Base (Repositório/Lógica de Negócio)
$tracker_service = new WhatsApp_Tracker();

// 2. Registra o endpoint AJAX usando o serviço
add_action('wp_ajax_track_wa_click', function() use ($tracker_service) {
    $device = isset($_POST['device']) ? sanitize_text_field($_POST['device']) : 'unknown';
    $source = isset($_POST['source']) ? sanitize_text_field($_POST['source']) : 'unknown';
    $data = $tracker_service->track_click($device, $source);
    wp_send_json_success(['data' => $data]);
});
add_action('wp_ajax_nopriv_track_wa_click', function() use ($tracker_service) {
    // ANTI-BOT: Bloquear no backend se o User Agent for de um robô
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    if (empty($user_agent) || preg_match('/(bot|crawler|spider|crawling|googlebot|bingbot|yandex|duckduckbot|slurp)/i', $user_agent)) {
        wp_send_json_success(['message' => 'Bot ignored']);
        return;
    }

    $device = isset($_POST['device']) ? sanitize_text_field($_POST['device']) : 'unknown';
    $source = isset($_POST['source']) ? sanitize_text_field($_POST['source']) : 'unknown';
    $data = $tracker_service->track_click($device, $source);
    wp_send_json_success(['data' => $data]);
});

// 3. Área Administrativa (Lazy Loading / Apenas no wp-admin)
if (is_admin()) {
    // Injeção de Dependência da View
    $reports_page = new Reports_Page($tracker_service);
    
    // Injeção de Dependência do Exportador
    $exporter = new WhatsApp_Export($tracker_service);
    add_action('admin_init', [$exporter, 'export_csv_handler']);
    
    // Registra a página no menu existente da SettingsAPI
    add_action('admin_menu', function() use ($reports_page) {
        add_submenu_page(
            'daher-settings',
            'Cliques WhatsApp',
            'Cliques WhatsApp',
            'manage_options',
            'daher-whatsapp-reports',
            [$reports_page, 'render']
        );
    }, 99);
}

/**
 * Obtém os posts do blog de forma aleatória, renovando a cada 5 minutos
 * para não destruir a performance do banco de dados (Uso de Transients).
 */
function daherclinica_get_recent_posts($limit = 6) {
    $transient_key = 'daher_carousel_posts_' . $limit;
    $posts_data = get_transient($transient_key);
    
    // Se o cache expirou (passou 5 minutos) ou não existe, faz a query no banco
    if ($posts_data === false) {
        $args = [
            'post_type'           => 'post',
            'posts_per_page'      => $limit,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'             => 'rand' // Aleatório
        ];
        
        $query = new WP_Query($args);
        $posts_data = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $posts_data[] = [
                    'id'        => get_the_ID(),
                    'title'     => get_the_title(),
                    'excerpt'   => wp_trim_words(get_the_excerpt(), 15, '...'),
                    'permalink' => get_permalink(),
                    'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large') : '',
                    'date'      => get_the_date()
                ];
            }
            wp_reset_postdata();
        }
        
        // Salva na memória do servidor por 5 minutos (300 segundos)
        set_transient($transient_key, $posts_data, 5 * MINUTE_IN_SECONDS);
    }
    
    return $posts_data;
}



