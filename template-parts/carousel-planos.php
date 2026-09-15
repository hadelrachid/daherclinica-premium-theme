<?php
/**
 * Componente: Carrossel de Planos de Saúde
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_carousel = get_theme_mod('show_insurance_carousel', true);
if (!$show_carousel) return;

$speed = get_theme_mod('carousel_speed', 20);

// Busca os convênios cadastrados no CPT health_insurance
$args = [
    'post_type'      => 'health_insurance',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
];

$insurances = new WP_Query($args);

if ($insurances->have_posts()) :
?>
    <section class="health-insurance-carousel-section">
        <div class="carousel-container">
            <div class="carousel-track" style="animation-duration: <?php echo esc_attr($speed); ?>s;">
                <?php 
                // Exibe os itens duplicados para criar o loop infinito sem quebra
                for ($i = 0; $i < 2; $i++) {
                    while ($insurances->have_posts()) : $insurances->the_post();
                        if (has_post_thumbnail()) {
                            echo '<div class="carousel-item">';
                            the_post_thumbnail('medium', ['alt' => get_the_title(), 'loading' => 'lazy']);
                            echo '</div>';
                        }
                    endwhile;
                    // Reseta o ponteiro para o segundo loop (necessário para o efeito infinito)
                    $insurances->rewind_posts();
                }
                ?>
            </div>
        </div>
    </section>

    <style>
        .health-insurance-carousel-section {
            background-color: #f8fafc;
            padding: 30px 0;
            border-top: 1px solid #e2e8f0;
            overflow: hidden;
            width: 100%;
        }
        
        .carousel-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Efeito de fade nas bordas para suavizar a entrada e saída */
        .carousel-container::before,
        .carousel-container::after {
            content: "";
            position: absolute;
            top: 0;
            width: 100px;
            height: 100%;
            z-index: 2;
        }
        .carousel-container::before {
            left: 0;
            background: linear-gradient(to right, #f8fafc 0%, transparent 100%);
        }
        .carousel-container::after {
            right: 0;
            background: linear-gradient(to left, #f8fafc 0%, transparent 100%);
        }

        .carousel-track {
            display: flex;
            align-items: center;
            width: max-content;
            animation: scroll-insurance linear infinite;
        }

        /* Pausa a animação quando o mouse passa por cima */
        .carousel-track:hover {
            animation-play-state: paused;
        }

        .carousel-item {
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            filter: grayscale(100%) opacity(0.7);
            transition: all 0.3s ease;
        }
        
        .carousel-item:hover {
            filter: grayscale(0%) opacity(1);
            transform: scale(1.05);
        }

        .carousel-item img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        @keyframes scroll-insurance {
            0% { transform: translateX(0); }
            /* Como duplicamos os itens, -50% é exatamente metade (1 ciclo completo original) */
            100% { transform: translateX(-50%); }
        }

        @media (max-width: 768px) {
            .carousel-item {
                padding: 0 20px;
            }
            .carousel-item img {
                max-height: 40px;
            }
            .carousel-container::before,
            .carousel-container::after {
                width: 40px;
            }
        }
    </style>

<?php
endif;
wp_reset_postdata();
?>
