<?php
/**
 * Template Name: Sobre Nós
 * Description: Página institucional com história, diferenciais e equipe
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Carrega as configurações do painel
$sobre_options = get_option('daher_sobre_options', []);
$home_options = get_option('daher_home_options', []);
$media_options = get_option('daher_media_options', []);

$hero_desktop = !empty($media_options['hero_sobre_desktop']) 
    ? esc_url($media_options['hero_sobre_desktop']) 
    : (!empty($media_options['hero_default_desktop']) ? esc_url($media_options['hero_default_desktop']) : get_template_directory_uri() . '/assets/images/sobre-hero.webp');

$hero_mobile = !empty($media_options['hero_sobre_mobile']) 
    ? esc_url($media_options['hero_sobre_mobile']) 
    : (!empty($media_options['hero_default_mobile']) ? esc_url($media_options['hero_default_mobile']) : get_template_directory_uri() . '/assets/images/sobre-hero-mob.webp');

// Hero
$hero_title = !empty($sobre_options['sobre_hero_title']) 
    ? $sobre_options['sobre_hero_title'] 
    : __('Uma História de <span class="gradient-text">Excelência e Cuidado</span>', 'daherclinica');
$hero_subtitle = !empty($sobre_options['sobre_hero_subtitle']) 
    ? $sobre_options['sobre_hero_subtitle'] 
    : __('Há mais de 20 anos transformando vidas com saúde vascular, dermatologia e clínica geral', 'daherclinica');

// História
$history_title = !empty($sobre_options['history_title']) 
    ? $sobre_options['history_title'] 
    : __('Daher Clínica: <span class="text-primary">Cuidado que Transforma</span>', 'daherclinica');
$history_text_1 = !empty($sobre_options['history_text_1']) 
    ? $sobre_options['history_text_1'] 
    : __('A Daher Clínica nasceu do sonho de unir excelência médica com atendimento humanizado. Fundada pelo <strong>Dr. Marcelo de Azevedo Daher</strong>, especialista em Cirurgia Vascular, e pela <strong>Dra. Rosana Palmares Maciel Daher</strong>, referência em Dermatologia, a clínica cresceu com um propósito claro: oferecer cuidado integral que vai além do tratamento.', 'daherclinica');
$history_text_2 = !empty($sobre_options['history_text_2']) 
    ? $sobre_options['history_text_2'] 
    : __('Com a chegada da <strong>Dra. Caroline Linhares Machado</strong> à equipe, ampliamos nossa atuação para a <strong>Clínica Geral</strong>, consolidando um atendimento completo para toda a família.', 'daherclinica');
$history_text_3 = !empty($sobre_options['history_text_3']) 
    ? $sobre_options['history_text_3'] 
    : __('Nossa filosofia combina <strong>tecnologia de ponta, diagnósticos precisos e um ambiente acolhedor</strong>, onde cada paciente é tratado com respeito, individualidade e dedicação.', 'daherclinica');

// Stats (da Home, reutilizados)
$stat_years = isset($home_options['stat_years']) ? $home_options['stat_years'] : '+20';
$stat_years_label = isset($home_options['stat_years_label']) ? $home_options['stat_years_label'] : __('Anos de Experiência', 'daherclinica');
$stat_patients = isset($home_options['stat_patients']) ? $home_options['stat_patients'] : '+5.000';
$stat_patients_label = isset($home_options['stat_patients_label']) ? $home_options['stat_patients_label'] : __('Pacientes Atendidos', 'daherclinica');
$stat_specialties = '3';
$stat_specialties_label = __('Especialidades Integradas', 'daherclinica');

// Valores
$values_title = !empty($sobre_options['values_title']) 
    ? $sobre_options['values_title'] 
    : __('O que nos <span class="text-primary">Move</span>', 'daherclinica');
$values_subtitle = !empty($sobre_options['values_subtitle']) 
    ? $sobre_options['values_subtitle'] 
    : __('Valores que guiam nossa atuação diária e nosso relacionamento com os pacientes', 'daherclinica');
$values_items = isset($sobre_options['values_items']) && is_array($sobre_options['values_items']) 
    ? $sobre_options['values_items'] 
    : [];

// Cards de especialidades da Home (reutilizados)
$specialties_cards = isset($home_options['specialties']) && is_array($home_options['specialties']) 
    ? $home_options['specialties'] 
    : [];

// Depoimentos (fixos ou editáveis futuramente)
$testimonials = [
    [
        'text' => __('"Excelente atendimento! Fiz o tratamento de varizes com o Dr. Marcelo e o resultado foi incrível. Equipe muito atenciosa e ambiente acolhedor."', 'daherclinica'),
        'author' => __('Maria Silva', 'daherclinica'),
        'role' => __('Paciente - Cirurgia Vascular', 'daherclinica')
    ],
    [
        'text' => __('"A Dra. Rosana é fantástica! Tratou meu melasma com muito cuidado e dedicação. Minha pele nunca esteve tão bonita. Recomendo demais!"', 'daherclinica'),
        'author' => __('Ana Paula Oliveira', 'daherclinica'),
        'role' => __('Paciente - Dermatologia', 'daherclinica')
    ],
    [
        'text' => __('"Dra. Caroline é muito atenciosa. Me senti acolhida desde a primeira consulta. Atendimento humanizado que faz toda a diferença."', 'daherclinica'),
        'author' => __('Fernanda Costa', 'daherclinica'),
        'role' => __('Paciente - Clínica Geral', 'daherclinica')
    ]
];

// URL da página de contato e especialidades
$contato_page = get_page_by_path('contato');
$contato_url = $contato_page ? get_permalink($contato_page) : esc_url(home_url('/contato'));
$especialidades_url = esc_url(home_url('/especialidades'));
?>

    <!-- Hero da Página Sobre -->
    <section class="page-hero about-hero">
        <div class="page-hero-bg">
            <div class="page-hero-overlay"></div>
            <picture>
                <source media="(max-width: 992px)" srcset="<?php echo $hero_mobile; ?>">
                <source media="(min-width: 993px)" srcset="<?php echo $hero_desktop; ?>">
                <img src="<?php echo $hero_desktop; ?>" alt="Sobre a Daher Clínica" class="page-hero-image" width="1920" height="800" loading="eager" fetchpriority="high" decoding="sync">
            </picture>
        </div>
        <div class="container">
            <div class="page-hero-content">
                <div class="section-tag">
                    <span class="tag">✦ <?php _e('Sobre Nós', 'daherclinica'); ?></span>
                </div>
                <h1><?php echo wp_kses_post($hero_title); ?></h1>
                <p><?php echo esc_html($hero_subtitle); ?></p>
            </div>
        </div>
    </section>

    <!-- Nossa História -->
    <section class="section history-section">
        <div class="container">
            <div class="history-grid">
                <div class="history-content">
                    <span class="subtitle"><?php _e('Nossa Jornada', 'daherclinica'); ?></span>
                    <h2 class="section-title"><?php echo wp_kses_post($history_title); ?></h2>
                    <p><?php echo wp_kses_post($history_text_1); ?></p>
                    <p><?php echo wp_kses_post($history_text_2); ?></p>
                    <p><?php echo wp_kses_post($history_text_3); ?></p>
                    
                    <div class="history-stats">
                        <div class="stat-box">
                            <span class="stat-number"><?php echo esc_html($stat_years); ?></span>
                            <span class="stat-label"><?php echo esc_html($stat_years_label); ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo esc_html($stat_patients); ?></span>
                            <span class="stat-label"><?php echo esc_html($stat_patients_label); ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo esc_html($stat_specialties); ?></span>
                            <span class="stat-label"><?php echo esc_html($stat_specialties_label); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="history-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/consultorio.webp" alt="Consultório Daher Clínica" class="history-img" width="800" height="600" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Nossos Valores -->
    <section class="section values-section bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="subtitle center"><?php _e('Nossos Pilares', 'daherclinica'); ?></span>
                <h2 class="section-title"><?php echo wp_kses_post($values_title); ?></h2>
                <p class="section-subtitle"><?php echo esc_html($values_subtitle); ?></p>
            </div>
            
            <div class="values-grid">
                <?php if (!empty($values_items)) : ?>
                    <?php foreach ($values_items as $value) : ?>
                        <div class="value-card">
                            <div class="value-icon">
                                <i class="<?php echo esc_attr($value['icon'] ?? 'fas fa-heartbeat'); ?>"></i>
                            </div>
                            <h3><?php echo esc_html($value['title']); ?></h3>
                            <p><?php echo esc_html($value['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Fallback padrão -->
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-heartbeat"></i></div>
                        <h3><?php _e('Excelência Médica', 'daherclinica'); ?></h3>
                        <p><?php _e('Busca contínua pela atualização e pelos melhores protocolos de tratamento em Cirurgia Vascular, Dermatologia e Clínica Geral.', 'daherclinica'); ?></p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <h3><?php _e('Atendimento Humanizado', 'daherclinica'); ?></h3>
                        <p><?php _e('Cada paciente é único. Escutamos, acolhemos e construímos juntos o melhor caminho para sua saúde e bem-estar.', 'daherclinica'); ?></p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-microscope"></i></div>
                        <h3><?php _e('Tecnologia Avançada', 'daherclinica'); ?></h3>
                        <p><?php _e('Investimos em equipamentos de última geração para diagnósticos precisos e tratamentos menos invasivos.', 'daherclinica'); ?></p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-lock"></i></div>
                        <h3><?php _e('Ética e Sigilo', 'daherclinica'); ?></h3>
                        <p><?php _e('Compromisso total com a privacidade e a segurança dos dados dos nossos pacientes, em conformidade com a LGPD.', 'daherclinica'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Nossas Especialidades (Resumo) -->
    <section class="section specialties-about">
        <div class="container">
            <div class="section-header text-center">
                <span class="subtitle center"><?php _e('Áreas de Atuação', 'daherclinica'); ?></span>
                <h2 class="section-title"><?php _e('Cuidado <span class="text-primary">Integrado</span>', 'daherclinica'); ?></h2>
                <p class="section-subtitle"><?php _e('Três especialidades que se complementam para oferecer o melhor à sua saúde', 'daherclinica'); ?></p>
            </div>
            
            <div class="specialties-about-grid">
                <?php if (!empty($specialties_cards)) : ?>
                    <?php foreach ($specialties_cards as $card) : ?>
                        <div class="specialty-about-card">
                            <div class="specialty-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h3><?php echo esc_html($card['title']); ?></h3>
                            <p><?php echo esc_html($card['description']); ?></p>
                            <a href="<?php echo $especialidades_url; ?>" class="link-more"><?php _e('Saiba mais', 'daherclinica'); ?> <i class="fas fa-arrow-right"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Fallback padrão -->
                    <div class="specialty-about-card">
                        <div class="specialty-icon"><i class="fas fa-heartbeat"></i></div>
                        <h3><?php _e('Cirurgia Vascular', 'daherclinica'); ?></h3>
                        <p><?php _e('Tratamento de varizes com laser e espuma, check-up vascular, trombose, doenças arteriais e venosas. Cuidado especializado para sua circulação.', 'daherclinica'); ?></p>
                        <a href="<?php echo $especialidades_url; ?>" class="link-more"><?php _e('Saiba mais', 'daherclinica'); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="specialty-about-card">
                        <div class="specialty-icon"><i class="fas fa-leaf"></i></div>
                        <h3><?php _e('Dermatologia', 'daherclinica'); ?></h3>
                        <p><?php _e('Tratamento de acne, melasma, manchas, cicatrizes, rejuvenescimento com laser, dermatologia clínica e cirúrgica para todas as idades.', 'daherclinica'); ?></p>
                        <a href="<?php echo $especialidades_url; ?>" class="link-more"><?php _e('Saiba mais', 'daherclinica'); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="specialty-about-card">
                        <div class="specialty-icon"><i class="fas fa-stethoscope"></i></div>
                        <h3><?php _e('Clínica Geral', 'daherclinica'); ?></h3>
                        <p><?php _e('Atendimento humanizado para toda a família, medicina preventiva, diagnóstico precoce e acompanhamento integral da sua saúde.', 'daherclinica'); ?></p>
                        <a href="<?php echo $especialidades_url; ?>" class="link-more"><?php _e('Saiba mais', 'daherclinica'); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Nossa Equipe (Médicos Dedicados) -->
    <section class="section team-about bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="subtitle center"><?php _e('Corpo Clínico', 'daherclinica'); ?></span>
                <h2 class="section-title"><?php _e('Médicos <span class="text-primary">Especialistas</span>', 'daherclinica'); ?></h2>
                <p class="section-subtitle"><?php _e('Profissionais dedicados que unem ciência, experiência e humanidade no cuidado com você', 'daherclinica'); ?></p>
            </div>
            
            <div class="team-about-grid">
                <?php 
                // 1. Tenta buscar médicos do NOVO PAINEL
                $medicos_painel = get_option('daher_medicos_options', []);
                
                // Ordena os médicos por prioridade (campo 'ordem')
                if (!empty($medicos_painel) && is_array($medicos_painel)) {
                    usort($medicos_painel, function($a, $b) {
                        $ordem_a = isset($a['ordem']) ? intval($a['ordem']) : 0;
                        $ordem_b = isset($b['ordem']) ? intval($b['ordem']) : 0;
                        return $ordem_a <=> $ordem_b;
                    });
                }

                $whatsapp_options = get_option('daher_whatsapp_options', []);
                $whatsapp_geral = preg_replace('/[^0-9]/', '', $whatsapp_options['whatsapp_number'] ?? '5521977667676');

                if (!empty($medicos_painel)) : 
                    foreach ($medicos_painel as $medico) : 
                        $nome = $medico['nome'] ?? '';
                        $crm = $medico['crm'] ?? '';
                        $especialidade = $medico['especialidade'] ?? '';
                        $bio = $medico['bio'] ?? '';
                        $foto = !empty($medico['foto']) ? $medico['foto'] : get_template_directory_uri() . '/assets/images/medico-placeholder.webp';
                        $whatsapp_link = "https://wa.me/{$whatsapp_geral}";
                ?>
                    <div class="team-about-card">
                        <div class="team-about-image">
                            <img src="<?php echo esc_url($foto); ?>" alt="<?php echo esc_attr($nome); ?>" width="400" height="400" loading="lazy">
                        </div>
                        <div class="team-about-info">
                            <h3><?php echo esc_html($nome); ?></h3>
                            <span class="team-specialty-badge"><?php echo esc_html($especialidade); ?></span>
                            <?php if ($crm) : ?>
                                <div class="team-crm"><?php echo esc_html($crm); ?></div>
                            <?php endif; ?>
                            <p><?php echo esc_html($bio); ?></p>
                            
                            <?php if (!empty($medico['tags'])) : 
                                $tags = explode(',', $medico['tags']);
                            ?>
                                <div class="team-expertise">
                                    <?php foreach ($tags as $tag) : ?>
                                        <span><?php echo esc_html(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="team-social-links">
                                <?php 
                                $insta_url = !empty($medico['instagram']) ? $medico['instagram'] : "https://www.instagram.com/daherclinica/";
                                ?>
                                <a href="<?php echo esc_url($insta_url); ?>" target="_blank" class="team-social-btn"><i class="fab fa-instagram"></i></a>
                                <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" class="team-social-btn"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach; 

                // 2. Fallback: Médicos do CPT se o painel estiver vazio
                else : 
                    $medicos_query = new WP_Query([
                        'post_type'      => 'medico',
                        'posts_per_page' => -1,
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                    ]);

                    if ($medicos_query->have_posts()) : 
                        while ($medicos_query->have_posts()) : $medicos_query->the_post();
                            $crm = get_post_meta(get_the_ID(), '_medico_crm', true);
                            $especialidade = get_post_meta(get_the_ID(), '_medico_especialidade', true);
                            $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'team-photo') : get_template_directory_uri() . '/assets/images/medico-placeholder.webp';
                ?>
                    <div class="team-about-card">
                        <div class="team-about-image">
                            <img src="<?php echo esc_url($foto); ?>" alt="<?php the_title_attribute(); ?>" width="400" height="400" loading="lazy">
                        </div>
                        <div class="team-about-info">
                            <h3><?php the_title(); ?></h3>
                            <span class="team-specialty-badge"><?php echo esc_html($especialidade); ?></span>
                            <?php if ($crm) : ?>
                                <div class="team-crm"><?php echo esc_html($crm); ?></div>
                            <?php endif; ?>
                            <p><?php echo get_the_excerpt(); ?></p>
                            <div class="team-social-links">
                                <a href="https://www.instagram.com/daherclinica/" target="_blank" class="team-social-btn"><i class="fab fa-instagram"></i></a>
                                <a href="https://wa.me/<?php echo $whatsapp_geral; ?>" target="_blank" class="team-social-btn"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                <?php 
                        endwhile;
                        wp_reset_postdata();
                    endif;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="subtitle center"><?php _e('Depoimentos', 'daherclinica'); ?></span>
                <h2 class="section-title"><?php _e('O que nossos <span class="text-primary">Pacientes Dizem</span>', 'daherclinica'); ?></h2>
                <p class="section-subtitle"><?php _e('Histórias reais de quem confiou na Daher Clínica', 'daherclinica'); ?></p>
            </div>
            
            <div class="testimonials-grid">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <div class="testimonial-card">
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p><?php echo esc_html($testimonial['text']); ?></p>
                        <div class="testimonial-author">
                            <strong><?php echo esc_html($testimonial['author']); ?></strong>
                            <span><?php echo esc_html($testimonial['role']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section cta-section">
        <div class="cta-content text-center">
            <h2><?php _e('Agende sua Avaliação', 'daherclinica'); ?></h2>
            
            <div style="margin: 1.5rem 0;">
                <p><?php _e('Venha conhecer a Daher Clínica e descubra um cuidado médico que faz diferença na sua saúde e qualidade de vida.', 'daherclinica'); ?></p>
            </div>
            
            <div class="cta-buttons">
                <a href="<?php echo esc_url($contato_url . '#contactForm'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-check"></i> <?php _e('Agendar Consulta', 'daherclinica'); ?>
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>