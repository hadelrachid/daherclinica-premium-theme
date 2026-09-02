<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <!-- Preconnect hints for performance -->
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Google tag (gtag.js) Setup (Scripts carregados assincronamente no final) -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-18319109877');
        gtag('config', 'G-C4RJ4QJB0V');

        // Helper function to delay opening a URL until a gtag event is sent.
        function gtagSendEvent(url) {
            var callback = function () {
                if (typeof url === 'string') {
                    window.location = url;
                }
            };
            gtag('event', 'ads_conversion_Fale_conosco_1', {
                'event_callback': callback,
                'event_timeout': 2000
            });
            return false;
        }
    </script>


    
    <?php 
    $description = get_bloginfo('description', 'display');
    if (is_singular()) {
        $post_desc = get_the_excerpt();
        if (empty(trim($post_desc))) {
            $post_desc = wp_trim_words(get_post_field('post_content', get_the_ID()), 25, '');
        }
        if ($post_desc) {
            $description = $post_desc;
        }
    }
    // 1. Sanitização rigorosa da Meta Description (160 caracteres limpos)
    $description = wp_strip_all_tags(strip_shortcodes($description));
    $description = preg_replace('/\s+/', ' ', $description);
    if (mb_strlen($description, 'UTF-8') > 160) {
        $description = mb_substr($description, 0, 157, 'UTF-8') . '...';
    }
    ?>
    <meta name="description" content="<?php echo esc_attr(trim($description)); ?>">

    <!-- 2. Canonical URL -->
    <?php if (is_singular()) : ?>
        <link rel="canonical" href="<?php the_permalink(); ?>">
    <?php else : ?>
        <link rel="canonical" href="<?php echo esc_url(home_url(wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); ?>">
    <?php endif; ?>

    <!-- 3. Controle Avançado de Robôs -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:type" content="<?php echo is_singular('post') ? 'article' : 'website'; ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    
    <?php
    // ============================================================
    // OPEN GRAPH IMAGE - LÓGICA ROBUSTA COM FALLBACKS
    // ============================================================
    
    $og_image = '';

    // Pega a opção da nova aba de mídia
    $media_options = get_option('daher_media_options', []);

    // URL absoluta garantida para a imagem padrão
    $default_og = !empty($media_options['og_default_image'])
        ? $media_options['og_default_image']
        : get_template_directory_uri() . '/assets/images/og-default.jpg';

    // 1. POST INDIVIDUAL do blog: usa a imagem destacada do post
    if (is_single() && has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$og_image) {
            $og_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
    }

    // 2. PÁGINA com imagem destacada (Sobre, Contato, etc.)
    if (empty($og_image) && is_page() && has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$og_image) {
            $og_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
    }

    // 3. FALLBACK: usa og-default.jpg ou a imagem configurada no painel
    if (empty($og_image)) {
        $og_image = $default_og;
    }

    // Garante que a URL é absoluta (nunca relativa)
    if (!empty($og_image) && strpos($og_image, 'http') !== 0) {
        $og_image = home_url($og_image);
    }
    ?>
    
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:secure_url" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:alt" content="<?php echo is_singular() ? esc_attr(get_the_title()) : esc_attr(get_bloginfo('name')); ?>">
    
    <!-- 4. Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(trim($description)); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    
    <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/inter.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/playfair.woff2" as="font" type="font/woff2" crossorigin>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Header -->
<header class="header" id="header">
    <div class="reading-progress"></div>
    <div class="container">
        <div class="header-inner">
            <!-- LOGO -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                <?php
                $media_options = get_option('daher_media_options', []);
                $logo_principal = !empty($media_options['logo_principal']) 
                    ? esc_url($media_options['logo_principal']) 
                    : get_template_directory_uri() . '/assets/images/logo.png';
                ?>
                <img src="<?php echo $logo_principal; ?>" alt="Daher Clínica" class="logo-img" width="120" height="135">
                <span class="logo-text">Daher <span class="accent">Clínica</span></span>
            </a>
            
            <!-- MENU DESKTOP -->
            <nav class="desktop-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-links',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
                ?>
            </nav>
            
            <!-- BOTÃO HAMBURGUER (apenas ícone) -->
            <button class="menu-toggle" id="menuToggle" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
    
    <!-- MENU MOBILE (com botão Agendar dentro) -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-close-btn" id="mobileCloseBtn">
            <i class="fas fa-times"></i>
        </div>
        
        <div class="mobile-menu-inner">
            <!-- Botão Agendar no topo do menu mobile -->
            <div class="mobile-agendar-wrapper">
                <a href="<?php echo esc_url(home_url('/#contactForm')); ?>" 
                   class="mobile-btn-agendar" 
                   id="mobileAgendarBtn"
                   aria-label="Agendar consulta pelo menu"
                   data-home-url="<?php echo esc_url(home_url('/')); ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?php _e('Agendar Consulta', 'daherclinica'); ?></span>
                </a>
            </div>
            
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-list',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 2,
                'walker'         => new DaherClinica_Mobile_Walker(),
            ]);
            ?>
            
            <div class="mobile-divider"></div>
            

        </div>
    </div>
    
    <!-- Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
</header>

<main>
