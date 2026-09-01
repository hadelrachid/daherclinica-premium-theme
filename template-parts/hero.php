<?php
/**
 * Template Part: Hero Section
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_options = get_option('daher_home_options', []);
$media_options = get_option('daher_media_options', []);

$hero_desktop = !empty($media_options['hero_home_desktop']) 
    ? esc_url($media_options['hero_home_desktop']) 
    : get_template_directory_uri() . '/assets/images/hero.webp';
    
$hero_mobile = !empty($media_options['hero_home_mobile']) 
    ? esc_url($media_options['hero_home_mobile']) 
    : get_template_directory_uri() . '/assets/images/hero-mob.webp';

$hero_title = !empty($home_options['hero_title']) ? $home_options['hero_title'] : 'Cuidado Especializado para<br>Sua Saúde e Beleza';
$hero_subtitle = !empty($home_options['hero_subtitle']) ? $home_options['hero_subtitle'] : 'Unimos Cirurgia Vascular e Dermatologia com tecnologia de ponta, oferecendo tratamentos personalizados que realçam seu bem-estar e autoestima.';

$stat_years = $home_options['stat_years'] ?? '+20';
$stat_years_label = $home_options['stat_years_label'] ?? 'Anos de Excelência';
$stat_patients = $home_options['stat_patients'] ?? '+5.000';
$stat_patients_label = $home_options['stat_patients_label'] ?? 'Pacientes Atendidos';
$stat_team = $home_options['stat_team'] ?? '100%';
$stat_team_label = $home_options['stat_team_label'] ?? 'Equipe Especializada';
?>

<section id="inicio" class="hero">
    <div class="hero-bg">
        <div class="hero-overlay"></div>
        <picture>
            <source media="(max-width: 992px)" srcset="<?php echo $hero_mobile; ?>">
            <source media="(min-width: 993px)" srcset="<?php echo $hero_desktop; ?>">
            <img src="<?php echo $hero_desktop; ?>" alt="Daher Clínica" class="hero-image" width="1920" height="1080" loading="eager" fetchpriority="high" decoding="sync">
        </picture>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-pulse">✦ <?php _e('Excelência Médica', 'daherclinica'); ?></span>
            </div>
            <h1 class="hero-title">
                <?php echo wp_kses_post($hero_title); ?>
            </h1>
            <p class="hero-subtitle">
                <?php echo wp_kses_post($hero_subtitle); ?>
            </p>
            <div class="hero-buttons">
                <a href="#contactForm" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-check"></i> <?php _e('Agendar Consulta', 'daherclinica'); ?>
                </a>
                <a href="#especialidades" class="btn btn-outline btn-lg">
                    <i class="fas fa-stethoscope"></i> <?php _e('Nossas Especialidades', 'daherclinica'); ?>
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stat_years); ?></span>
                    <span class="stat-label"><?php echo esc_html($stat_years_label); ?></span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stat_patients); ?></span>
                    <span class="stat-label"><?php echo esc_html($stat_patients_label); ?></span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo esc_html($stat_team); ?></span>
                    <span class="stat-label"><?php echo esc_html($stat_team_label); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <a href="#sobre" class="scroll-link" aria-label="<?php esc_attr_e('Rolar para a seção Sobre', 'daherclinica'); ?>">
            <span class="scroll-text"><?php _e('Explore', 'daherclinica'); ?></span>
            <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </a>
    </div>
</section>