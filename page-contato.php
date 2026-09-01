<?php
/**
 * Template Name: Contato
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php
$media_options = get_option('daher_media_options', []);

$hero_desktop = !empty($media_options['hero_contato_desktop']) 
    ? esc_url($media_options['hero_contato_desktop']) 
    : (!empty($media_options['hero_default_desktop']) ? esc_url($media_options['hero_default_desktop']) : get_template_directory_uri() . '/assets/images/contato-hero.webp');

$hero_mobile = !empty($media_options['hero_contato_mobile']) 
    ? esc_url($media_options['hero_contato_mobile']) 
    : (!empty($media_options['hero_default_mobile']) ? esc_url($media_options['hero_default_mobile']) : get_template_directory_uri() . '/assets/images/contato-hero-mob.webp');
?>

<!-- Hero da Página Contato -->
<section class="page-hero contact-hero">
    <div class="page-hero-bg">
        <div class="page-hero-overlay"></div>
        <picture>
            <source media="(max-width: 992px)" srcset="<?php echo $hero_mobile; ?>">
            <source media="(min-width: 993px)" srcset="<?php echo $hero_desktop; ?>">
            <img src="<?php echo $hero_desktop; ?>" alt="Contato e Localização - Daher Clínica" class="page-hero-image" width="1920" height="800" loading="eager" fetchpriority="high" decoding="sync">
        </picture>
    </div>
    <div class="container">
        <div class="page-hero-content">
            <div class="section-tag">
                <span class="tag">✦ <?php _e('Contato', 'daherclinica'); ?></span>
            </div>
            <h1><?php _e('Contato e Localização', 'daherclinica'); ?></h1>
            <p><?php _e('Estamos prontos para atender você com excelência e cuidado', 'daherclinica'); ?></p>
        </div>
    </div>
</section>

<!-- Contato Section -->
<?php get_template_part('template-parts/contato'); ?>

<!-- FAQ Section -->
<section class="section bg-light">
    <div class="container">
        <div class="faq-header text-center">
            <span class="subtitle center"><?php _e('Dúvidas Frequentes', 'daherclinica'); ?></span>
            <h2 class="section-title"><?php _e('Perguntas Frequentes', 'daherclinica'); ?></h2>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <h4><?php _e('Como funciona o agendamento?', 'daherclinica'); ?></h4>
                <p><?php _e('Você pode agendar sua consulta pelo formulário ao lado, por WhatsApp ou telefone. Nossa equipe retornará em até 1 hora útil para confirmar a data e horário disponíveis.', 'daherclinica'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php _e('Atendem convênios?', 'daherclinica'); ?></h4>
                <p><?php _e('Sim, trabalhamos com diversos convênios. Entre em contato para verificar se o seu plano é aceito. Também atendemos pacientes particulares.', 'daherclinica'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php _e('Qual a diferença entre Cirurgia Vascular e Dermatologia?', 'daherclinica'); ?></h4>
                <p><?php _e('Cirurgia Vascular trata doenças do sistema circulatório (varizes, trombose). Dermatologia cuida da pele, cabelos e unhas. Na Daher Clínica, as duas áreas trabalham integradas para seu bem-estar.', 'daherclinica'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php _e('Preciso de encaminhamento médico?', 'daherclinica'); ?></h4>
                <p><?php _e('Não necessariamente. Você pode agendar diretamente uma consulta de avaliação com nossos especialistas.', 'daherclinica'); ?></p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>