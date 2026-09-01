<?php
/**
 * Template Name: Especialidades
 * Description: Página com detalhes das especialidades, tecnologias e diferenciais
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Carrega as configurações do painel
$especialidades_options = get_option('daher_especialidades_options', []);
$media_options = get_option('daher_media_options', []);

$hero_desktop = !empty($media_options['hero_especialidades_desktop']) 
    ? esc_url($media_options['hero_especialidades_desktop']) 
    : (!empty($media_options['hero_default_desktop']) ? esc_url($media_options['hero_default_desktop']) : get_template_directory_uri() . '/assets/images/especialidades-hero.webp');

$hero_mobile = !empty($media_options['hero_especialidades_mobile']) 
    ? esc_url($media_options['hero_especialidades_mobile']) 
    : (!empty($media_options['hero_default_mobile']) ? esc_url($media_options['hero_default_mobile']) : get_template_directory_uri() . '/assets/images/especialidades-hero-mob.webp');

// Hero
$hero_title = !empty($especialidades_options['especialidades_hero_title']) 
    ? $especialidades_options['especialidades_hero_title'] 
    : __('Nossas <span class="text-primary">Especialidades</span>', 'daherclinica');
$hero_subtitle = !empty($especialidades_options['especialidades_hero_subtitle']) 
    ? $especialidades_options['especialidades_hero_subtitle'] 
    : __('Tratamentos avançados com tecnologia de ponta e equipe especializada', 'daherclinica');

// Tecnologias
$tech_title = !empty($especialidades_options['tech_title']) 
    ? $especialidades_options['tech_title'] 
    : __('Equipamentos de Última Geração', 'daherclinica');
$tech_subtitle = !empty($especialidades_options['tech_subtitle']) 
    ? $especialidades_options['tech_subtitle'] 
    : __('Investimos continuamente em tecnologia...', 'daherclinica');
$tech_items = isset($especialidades_options['tech_items']) && is_array($especialidades_options['tech_items']) 
    ? $especialidades_options['tech_items'] 
    : [];

// Diferenciais
$diff_title = !empty($especialidades_options['diff_title']) 
    ? $especialidades_options['diff_title'] 
    : __('Nossos Diferenciais', 'daherclinica');
$diff_items = isset($especialidades_options['diff_items']) && is_array($especialidades_options['diff_items']) 
    ? $especialidades_options['diff_items'] 
    : [];

// Layouts dos Cards
$tech_layout = $especialidades_options['tech_layout'] ?? 'simple';
$diff_layout = $especialidades_options['diff_layout'] ?? 'simple';

$grid_styles = [
    'simple' => 'repeat(auto-fit, minmax(280px, 1fr))',
    'expanded' => 'repeat(auto-fit, minmax(400px, 1fr))',
    'compact' => 'repeat(auto-fit, minmax(200px, 1fr))',
    'centered' => 'repeat(auto-fit, minmax(320px, 1fr)); justify-content: center;'
];

$tech_grid_style = $grid_styles[$tech_layout] ?? $grid_styles['simple'];
$diff_grid_style = $grid_styles[$diff_layout] ?? $grid_styles['simple'];

$contato_page = get_page_by_path('contato');
$contato_url = $contato_page ? get_permalink($contato_page) : esc_url(home_url('/contato'));
?>

    <!-- Hero da Página Especialidades -->
    <section class="page-hero specialties-hero">
        <div class="page-hero-bg">
            <div class="page-hero-overlay"></div>
            <picture>
                <source media="(max-width: 992px)" srcset="<?php echo $hero_mobile; ?>">
                <source media="(min-width: 993px)" srcset="<?php echo $hero_desktop; ?>">
                <img src="<?php echo $hero_desktop; ?>" alt="Especialidades Daher Clínica" class="page-hero-image" width="1920" height="800" loading="eager" fetchpriority="high" decoding="sync">
            </picture>
        </div>
        <div class="container">
            <div class="page-hero-content">
                <div class="section-tag">
                    <span class="tag">✦ <?php _e('Especialidades', 'daherclinica'); ?></span>
                </div>
                <h1><?php echo wp_kses_post($hero_title); ?></h1>
                <p><?php echo esc_html($hero_subtitle); ?></p>
            </div>
        </div>
    </section>

    <!-- Lista de Especialidades Dinâmicas (Usa o template-part da Home) -->
    <?php get_template_part('template-parts/especialidades'); ?>

    <!-- Tecnologias -->
    <section class="section tech-section bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="subtitle center"><?php _e('Estrutura', 'daherclinica'); ?></span>
                <h2 class="section-title"><?php echo wp_kses_post($tech_title); ?></h2>
                <p class="section-subtitle"><?php echo esc_html($tech_subtitle); ?></p>
            </div>
            
            <div class="tech-grid" style="display: grid; grid-template-columns: <?php echo esc_attr($tech_grid_style); ?>; gap: 30px; margin-top: 40px;">
                <?php if (!empty($tech_items)) : ?>
                    <?php foreach ($tech_items as $tech) : ?>
                        <div class="tech-card text-center" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div class="tech-icon" style="margin: 0 auto 15px auto;"><i class="<?php echo esc_attr($tech['icon'] ?? 'fas fa-microscope'); ?>"></i></div>
                            <h3><?php echo esc_html($tech['title']); ?></h3>
                            <p><?php echo nl2br(esc_html($tech['description'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Fallback padrão caso não haja tecnologias cadastradas -->
                    <div class="tech-card text-center" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="tech-icon" style="margin: 0 auto 15px auto;"><i class="fas fa-radiation"></i></div>
                        <h3><?php _e('Laser Nd:YAG', 'daherclinica'); ?></h3>
                        <p><?php _e('Tecnologia padrão-ouro para tratamento de varizes e vasinhos, oferecendo resultados rápidos e seguros.', 'daherclinica'); ?></p>
                    </div>
                    <div class="tech-card text-center" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="tech-icon" style="margin: 0 auto 15px auto;"><i class="fas fa-search-plus"></i></div>
                        <h3><?php _e('Dermatoscópio Digital', 'daherclinica'); ?></h3>
                        <p><?php _e('Avaliação precisa de lesões e pintas, essencial para prevenção e diagnóstico de câncer de pele.', 'daherclinica'); ?></p>
                    </div>
                    <div class="tech-card text-center" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="tech-icon" style="margin: 0 auto 15px auto;"><i class="fas fa-magic"></i></div>
                        <h3><?php _e('Tecnologias a Laser', 'daherclinica'); ?></h3>
                        <p><?php _e('Equipamentos modernos para rejuvenescimento, tratamento de manchas, cicatrizes e melasma.', 'daherclinica'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Diferenciais -->
    <section class="section diff-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title"><?php echo wp_kses_post($diff_title); ?></h2>
            </div>
            
            <div class="diff-grid" style="display: grid; grid-template-columns: <?php echo esc_attr($diff_grid_style); ?>; gap: 30px; margin-top: 40px;">
                <?php if (!empty($diff_items)) : ?>
                    <?php foreach ($diff_items as $diff) : ?>
                        <div class="diff-card text-center" style="padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div class="diff-icon" style="color: var(--primary); font-size: 2.5rem; margin: 0 auto 20px auto;"><i class="<?php echo esc_attr($diff['icon'] ?? 'fas fa-star'); ?>"></i></div>
                            <h3><?php echo esc_html($diff['title']); ?></h3>
                            <p><?php echo nl2br(esc_html($diff['description'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Fallback -->
                    <div class="diff-card text-center" style="padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="diff-icon" style="color: var(--primary); font-size: 2.5rem; margin: 0 auto 20px auto;"><i class="fas fa-user-md"></i></div>
                        <h3><?php _e('Corpo Clínico Titulado', 'daherclinica'); ?></h3>
                        <p><?php _e('Médicos com especialização sólida e Registro de Qualificação de Especialista (RQE).', 'daherclinica'); ?></p>
                    </div>
                    <div class="diff-card text-center" style="padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="diff-icon" style="color: var(--primary); font-size: 2.5rem; margin: 0 auto 20px auto;"><i class="fas fa-heart"></i></div>
                        <h3><?php _e('Atendimento Integrado', 'daherclinica'); ?></h3>
                        <p><?php _e('Avaliação completa unindo Cirurgia Vascular, Dermatologia e Clínica Geral.', 'daherclinica'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section cta-section">
        <div class="cta-content text-center">
            <h2><?php _e('Pronto para cuidar da sua saúde?', 'daherclinica'); ?></h2>
            <div class="cta-buttons" style="margin-top: 30px;">
                <a href="<?php echo esc_url($contato_url . '#contactForm'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-check"></i> <?php _e('Agendar Avaliação', 'daherclinica'); ?>
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>