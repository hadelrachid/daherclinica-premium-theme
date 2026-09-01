<?php
/**
 * Template Part: Sobre Section
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_options = get_option('daher_home_options', []);
$about_title = !empty($home_options['about_title']) ? $home_options['about_title'] : 'Cuidado Integral para<br>o seu Bem-Estar';
$about_text_1 = !empty($home_options['about_text_1']) ? $home_options['about_text_1'] : 'A Daher Clínica nasceu do propósito de unir duas áreas médicas essenciais para a saúde, bem-estar e autoestima: a Cirurgia Vascular e a Dermatologia.';
$about_text_2 = !empty($home_options['about_text_2']) ? $home_options['about_text_2'] : 'Contamos com tecnologia de ponta e uma equipe médica altamente qualificada para oferecer procedimentos clínicos, cirúrgicos e estéticos com máxima segurança e excelência.';
?>

<section id="sobre" class="section about">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <div class="section-tag">
                    <span class="tag">✦ <?php _e('Sobre Nós', 'daherclinica'); ?></span>
                </div>
                <h2 class="section-title">
                    <?php echo $about_title; ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(esc_html($about_text_1)); ?>
                </p>
                <p class="section-text">
                    <?php echo nl2br(esc_html($about_text_2)); ?>
                </p>
                
                <div class="about-features">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-user-md"></i></div>
                        <div class="feature-content">
                            <h3><?php _e('Especialistas Titulados', 'daherclinica'); ?></h3>
                            <p><?php _e('Médicos com RQE e vasta experiência', 'daherclinica'); ?></p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-microscope"></i></div>
                        <div class="feature-content">
                            <h3><?php _e('Tecnologia Avançada', 'daherclinica'); ?></h3>
                            <p><?php _e('Equipamentos de última geração', 'daherclinica'); ?></p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-heart"></i></div>
                        <div class="feature-content">
                            <h3><?php _e('Atendimento Humanizado', 'daherclinica'); ?></h3>
                            <p><?php _e('Cuidado personalizado e acolhedor', 'daherclinica'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="about-media">
                <div class="about-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/consultorio.webp" 
                         srcset="<?php echo get_template_directory_uri(); ?>/assets/images/consultorio-sm.webp 568w, 
                                 <?php echo get_template_directory_uri(); ?>/assets/images/consultorio.webp 1355w"
                         sizes="(max-width: 768px) 100vw, 568px"
                         alt="Consultório Daher Clínica" width="568" height="454" loading="lazy" decoding="async">
                    <div class="experience-card">
                        <div class="exp-number">+20</div>
                        <div class="exp-text"><?php _e('Anos de<br>Excelência', 'daherclinica'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>